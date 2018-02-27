<?php
/**
 * Created by PhpStorm.
 * User: mmorel
 * Date: 11/03/2016
 * Time: 11:59
 */

namespace ApiBundle\Manager;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelUserType;
use ApiBundle\Repository\ConConfigRepositoryInterface;
use ApiBundle\Repository\UsrUsersRepositoryInterface;
use ApiBundle\Security\UserToken;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use ApiBundle\Exception\LogonException;

class SessionManager
{

    /**
     * @var ConConfigRepositoryInterface
     */
    protected $configuration;

    /**
     * @var UsrUsersRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * Droits définis au niveau du profil
     *
     * @var array
     */
    protected $profileRights;

    /**
     * SessionManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->configuration = $container->get('doctrine')->getManager()->getRepository('ApiBundle:ConConfig');
        $this->userRepository = $container->get('doctrine')->getManager()->getRepository('ApiBundle:UsrUsers');
        $this->tokenStorage = $container->get('security.token_storage');
        $this->profileRights = $container->getParameter('authorizations')['profiles_rights'];
    }

    /**
     * Authentifie un utilisateur
     *
     * @param string $login
     * @param string $codesource
     * @param string $pac
     * @param array  $options
     * @throws LogonException
     */
    public function logonUser($login, $codesource, $pac, array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'numinstance' => null,
            'profil' => null,
            'password' => null,
        ));
        $options = $resolver->resolve($options);

        $codesPacs = explode(';', $this->configuration['pac']);
        if (!in_array($codesource, array('ADP_PORTAIL', 'ADP_ARC', 'ADP_AUTOMATE'))) {
            throw new LogonException('codesource inconnu', 11);
        } elseif (!in_array($pac, $codesPacs)) {
            throw new LogonException('Code pac inconnu', 3);
        }

        if ('ADP_PORTAIL' == $codesource) {
            $user = $this->userRepository->findOneByUsrLogin($login);
            if (null == $user || $user->getUsrType() == EnumLabelUserType::AUTO_USER) {
                throw new LogonException('User id inexistant pour ce pac', 8);
            }
            $this->checkAdpUser($user);
        } elseif ('ADP_ARC' == $codesource) {
            $user = $this->userRepository->findOneByUsrLogin($login);
            if ($user && $user->getUsrType() == EnumLabelUserType::AUTO_USER) {
                throw new LogonException('User id inexistant pour ce pac', 8);
            }

            $defaults = $this->getUserRightsFromProfile($options['profil']);
            $defaults['usrTypeHabilitation'] = $options['profil'];
            $defaults['usrAdp'] = true;

            $user = $this->userRepository->getOrCreateUserByLogin(
                $options['profil'].'_'.$login,
                $defaults
            );

        } elseif ('ADP_AUTOMATE' == $codesource) {
            $user = $this->userRepository->findOneByUsrLogin($login);
            if (null == $user || $user->getUsrPass() != $options['password']) {
                throw new LogonException('User id inexistant pour ce pac', 8);
            }
        }

        $token = new UserToken(
            $user,
            array(
                'roles'       => array($user->getUsrTypeHabilitation()),
                'profil'      => $options['profil'],
                'pac'         => $pac,
                'numinstance' => $options['numinstance'],
            )
        );

        $token->setAttribute('session-id', $this->generateSessionId());

        $token->setAuthenticated(true);

        $this->tokenStorage->setToken($token);
    }

    /**
     * Vérifie si un utilisateur est valide selon les normes ADP
     *
     * @param UsrUsers $user
     * @throws LogonException
     */
    protected function checkAdpUser(UsrUsers $user)
    {
        $today = new \DateTime();

        if (!$user->isUsrActif()) {
            throw new LogonException("User id non autorisé à accéder au BVE pour ce pac", 9);
        } elseif ($user->getUsrStatus() != 'ACTIV') {
            throw new LogonException("Vous n'avez pas accès à cette instance", 41);
        } elseif ($user->getUsrBeginningdate() > $today) {
            throw new LogonException("Vous n'avez pas encore accès à cette instance", 42);
        } elseif ($user->getUsrEndingdate() < $today && $user->getUsrEndingdate()->format('U') > 0) {
            throw new LogonException("Vous n'avez plus accès à cette instance", 43);
        }
    }

    /**
     * Renvoit les droits d'un utilisateur à partir de son profil
     *
     * @param $profile
     * @return array
     * @throws LogonException
     */
    protected function getUserRightsFromProfile($profile)
    {
        if (!isset($this->profileRights[$profile])) {
            throw new LogonException('Profil non géré', 10);
        }

        $results = array();
        foreach ($this->profileRights[$profile] as $name => $value) {
            $property = 'usr'.str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
            if (method_exists('\ApiBundle\Entity\UsrUsers', 'set'.ucfirst($property))) {
                $results[$property] = $value;
            }
        }
        return $results;
    }

    /**
     * Génère un id de session unique
     *
     * @return string
     */
    public function generateSessionId()
    {
        $uniqId1 = uniqid("");
        $uniqId2 = uniqid("");
        $pivot = substr($uniqId2, (strlen($uniqId2) / 2) + 2, strlen($uniqId2) - strlen($uniqId2) / 2);
        $sessionId = $pivot . substr($uniqId1, 3, strlen($uniqId2) - 3);
        return md5($sessionId);
    }
}
