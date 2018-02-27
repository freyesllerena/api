<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\PdaProfilDefAppli;
use ApiBundle\Entity\PdeProfilDef;
use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Entity\ProProfil;
use ApiBundle\Entity\PusProfilUser;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Repository\ConConfigRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use ApiBundle\Exception\LogonException;

class UserManager implements UserManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \ArrayAccess|ConConfigRepositoryInterface
     */
    protected $configuration;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var ClassificationPlanManagerInterface
     */
    protected $planManager;

    /**
     * UserManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->configuration = $container->get('api.repository.config');
        $this->tokenStorage = $container->get('security.token_storage');
        $this->planManager = $container->get('api.manager.classification_plan');
    }

    /**
     * Renvoie le plan de classement filtré pour l'utilisateur actuellement authentifié
     *
     * @param $device
     *
     * @return array
     */
    public function getFilteredClassificationPlanForCurrentUser($device)
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return array();
        }

        $classificationPlan = $this->planManager->buildCompleteClassificationPlan($device);

        if ($token->getUser() instanceof UsrUsers) {
            $classificationPlan = $this->planManager->filterClassificationPlanByApplication(
                $classificationPlan,
                $this->getApplicationFilterList()
            );
        }

        $classificationPlan = $this->planManager->filterClassificationPlanByMembershipLevel(
            $classificationPlan,
            $this->configuration['type_abo_visu']
        );

        return $classificationPlan;
    }

    /**
     * Renvoie les filtres par population pour l'utilisateur actuellement connecté
     */
    public function getPopulationFiltersForCurrentUser()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token || !$token->getUser() instanceof UsrUsers) {
            return array();
        }

        return $this->entityManager->getRepository('ApiBundle:PdhProfilDefHabi')
            ->findByUser($token->getUser());
    }

    /**
     * Renvoie la liste des tiroirs auxquels l'utilisateur a accès
     *
     * @return array
     */
    public function getApplicationFilterList()
    {
        $codeTiroirs = array();
        $filtresApplicatifs = $this->entityManager->getRepository('ApiBundle:PdaProfilDefAppli')
            ->findByUser($this->tokenStorage->getToken()->getUser());
        foreach ($filtresApplicatifs as $filtreApplicatif) {
            /** @var PdaProfilDefAppli $filtreApplicatif */
            $codeTiroirs = array_merge(explode('|', $filtreApplicatif->getPdaRefBve()), $codeTiroirs);
        }
        return $codeTiroirs;
    }

    /**
     * Authentifie un utilisateur
     *
     * @param array $data
     * @throws LogonException
     */
    public function authenticate(array $data)
    {
        $data += array('codesource' => null);

        $required = array(
            'codesource' => array('codesource non géré', 1),
            'pac' => array('absence du code pac', 2),
            'logoffurl' => array('Absence de logoffurl', 5),
            'bveurl' => array('Absence de bveurl', 6),
            'userid' => array('Absence du user id', 7)
        );

        if ($data['codesource'] == 'ADP_ARC') {
            $required += array(
                'profil' => array('Absence de profil', 18),
                'companyid' => array('Absence de companyid', 12),
                'softappid' => array('Absence de softappid', 15),
                'lastname' => array('Absence de lastname', 15),
                'firstname' => array('Absence de firstname', 16),
                'email' => array('Absence de email', 17),
                'client-context' => array('Absence de client-context', 19)
            );
        }

        $fields = array_keys($required);
        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                list($msgError, $codeError) = $required[$field];
                throw new LogonException($msgError, $codeError);
            }
        }

        $codesPacs = explode(';', $this->configuration['pac']);

        if (!in_array($data['codesource'], array('ADP_PORTAIL', 'ADP_ARC'))) {
            throw new LogonException('codesource inconnu', 11);
        } elseif (!in_array($data['pac'], $codesPacs)) {
            throw new LogonException('Code pac inconnu', 3);
        }

        $user = $this->entityManager->getRepository('ApiBundle:UsrUsers')->findOneByUsrLogin($data['userid']);
        if ($data['codesource'] == 'ADP_PORTAIL') {
            if (null === $user) {
                throw new LogonException('User id inexistant pour ce pac', 8);
            }

            $this->checkAdpUser($user);
        }
    }

    /**
     * @param UsrUsers $user
     * @throws LogonException
     */
    protected function checkAdpUser(UsrUsers $user)
    {
        if (!$user->isUsrActif()) {
            throw new LogonException("User id non autorisé à accéder au BVE pour ce pac", 9);
        }

        $today = new \DateTime();

        if ($user->getUsrStatus() != 'ACTIV') {
            throw new LogonException("Vous n'avez pas accès à cette instance", 41);
        } elseif ($user->getUsrBeginningdate() > $today) {
            throw new LogonException("Vous n'avez pas encore accès à cette instance", 42);
        } elseif ($user->getUsrEndingdate() < $today && $user->getUsrEndingdate()->format('U') > 0) {
            throw new LogonException("Vous n'avez plus accès à cette instance", 43);
        }
    }

    /**
     * Charge les profils d'un utilisateur
     *
     * @param UsrUsers $user
     *
     * @return array
     */
    public function loadProfiles(UsrUsers $user)
    {
        $mapping = [
            'appli' => 'proFiltresApplicatifs',
            'habi' => 'proFiltresPopulations'
        ];

        $filters = [];
        $profiles = $this->entityManager->getRepository('ApiBundle:ProProfil')
            ->findProfileFilterByUser($user);
        foreach ($profiles as $profile) {
            if (!isset($filters[$profile['proId']])) {
                $filters[$profile['proId']] = [
                    'proId' => $profile['proId'],
                    'proFiltresApplicatifs' => [],
                    'proFiltresPopulations' => []
                ];
            }
            if (isset($mapping[$profile['pdeType']])) {
                $filters[$profile['proId']][$mapping[$profile['pdeType']]][] = $profile['pdeIdProfilDef'];
            }
        }

        return array_values($filters);
    }

    /**
     * Entregistre les profils d'un utilisateur
     *
     * @param UsrUsers $user
     * @param array $profiles
     */
    public function saveProfiles(UsrUsers $user, array $profiles)
    {
        $existingProfils = $this->entityManager->getRepository('ApiBundle:ProProfil')->findByUser($user);
        $updatedProfils = array();

        foreach ($profiles as $profile) {
            if (isset($existingProfils[$profile['proId']])) {
                $entity = $existingProfils[$profile['proId']];
            } else {
                $entity = new ProProfil();
                $entity->setProLibelle($user->getUsrLogin());
                $relation = new PusProfilUser();
                $relation->setPusProfil($entity);
                $relation->setPusUser($user);
                $this->entityManager->persist($entity);
                $this->entityManager->persist($relation);
                $this->entityManager->flush();
            }
            $updatedProfils[] = $entity;
            $this->entityManager->getRepository('ApiBundle:PdeProfilDef')->updateByProfil(
                $entity,
                $profile['proFiltresApplicatifs'],
                $profile['proFiltresPopulations']
            );
        }

        foreach ($existingProfils as $profil) {
            if (!in_array($profil, $updatedProfils)) {
                $this->entityManager->remove($profil);
            }
            $this->entityManager->flush();
        }
    }
}
