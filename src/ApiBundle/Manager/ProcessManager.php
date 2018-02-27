<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\ProProcessus;
use ApiBundle\Entity\PtyProcessusType;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Repository\ProProcessusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProcessManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProProcessusRepository
     */
    private $processRepository;

    /**
     * @var UsrUsers
     */
    private $user;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var DictionaryManager
     */
    private $dictionaryManager;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->processRepository = $this->entityManager->getRepository('ApiBundle:ProProcessus');
        $this->dictionaryManager = $this->container->get('api.manager.dictionnaire');
        $this->user = $this->container->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * Récupère une liste de thématiques et processus sous forme d'arbre
     *
     * @param string $device Le support concerné pour récupérer les bons libellés
     *
     * @return mixed
     */
    public function retrieveCustomProcessTree($device)
    {
        $processList = [];
        // Récupération des traductions des thématiques
        $thematicResult = $this->retrieveThematicList($device);
        if (count($thematicResult)) {
            foreach ($thematicResult as $thematicId => $thematicLabel) {
                // Création de la thématique
                $processList[$thematicId] = $this->createTreeThematic($thematicId, $thematicLabel);
            }
            // Récupération de la liste des processus
            $processResult = $this->processRepository->getProcessList(
                $this->user->getUsrLogin(),
                $this->container->get('api.manager.user')->getApplicationFilterList()
            );
            // Ajout des processus
            foreach ($processResult as $values) {
                // Vérifie que la thématique existe
                if (!isset($processList[$values['proGroupe']])) {
                    continue;
                }
                if (!isset($processList[$values['proGroupe']]->children[$values['proId']])) {
                    // Création du processus
                    $processList[$values['proGroupe']]->children[$values['proId']] = $this->createTreeProcess($values);
                }
                $processList[$values['proGroupe']]->children[$values['proId']]->drawers[] = $values['ptyType'];
            }
            // Suppression des clefs
            foreach ($processList as &$process) {
                $process->children = array_values(
                    $process->children
                );
            }
        }
        return array_values(
            $processList
        );
    }

    /**
     * Récupère la liste des thématiques
     *
     * @param $device
     *
     * @return mixed
     */
    private function retrieveThematicList($device)
    {
        return array_flip(
            preg_filter(
                '/^processusGroupe(\d+)$/',
                '$1',
                array_flip($this->dictionaryManager->getDictionnaire($device))
            )
        );
    }

    /**
     * Crée un objet Thématique
     *
     * @param $thematicId
     * @param $thematicLabel
     *
     * @return object
     */
    private function createTreeThematic($thematicId, $thematicLabel)
    {
        return (object)[
            'id' => 'group' . $thematicId,
            'text' => $thematicLabel,
            'expanded' => false,
            'leaf' => false,
            'children' => []
        ];
    }

    /**
     * Crée un objet Processus
     *
     * @param array $process
     *
     * @return object
     */
    private function createTreeProcess(array $process)
    {
        return (object)[
            'id' => $process['proId'],
            'text' => $process['proLibelle'],
            'expanded' => false,
            'leaf' => true,
            'drawers' => []
        ];
    }

    /**
     * Lit un processus par son Id
     *
     * @param integer $processId L'Id du processus
     *
     * @return ProProcessus
     */
    public function getProcessById($processId)
    {
        return $this->processRepository->find($processId);
    }

    /**
     * Instancie un nouveau processus
     *
     * @return ProProcessus
     */
    public function instantiateProcess()
    {
        $process = new ProProcessus();
        $process->setProUser($this->user);
        return $process;
    }

    /**
     * Vérifie que l'utilisateur n'est pas déjà propriétaire d'un processus portant ce nom
     *
     * @param ProProcessus $process Une instance de ProProcessus
     *
     * @return mixed
     */
    public function hasAnotherProcessLabel(ProProcessus $process)
    {
        return $this->processRepository
            ->findAnotherProcessLabel($process, $this->user->getUsrLogin());
    }

    /**
     * Met à jour un processus
     *
     * @param ProProcessus $process Une instance de ProProcessus
     * @param array $drawersList Une liste de tiroirs
     *
     * @return ProProcessus
     */
    public function updateProcess(ProProcessus $process, array $drawersList)
    {
        $drawersInserted = $this->retrieveProcessDrawersList($process);
        $drawersToInsert = array_diff($drawersList, $drawersInserted);
        $drawersToDelete = array_diff($drawersInserted, $drawersList);
        $this->removeDrawers($drawersToDelete, $process);
        return $this->createProcess($process, $drawersToInsert);
    }

    /**
     * Récupère la liste des tiroirs d'un processus
     *
     * @param ProProcessus $process Une instance de ProProcessus
     *
     * @return mixed
     */
    private function retrieveProcessDrawersList(ProProcessus $process)
    {
        return array_column(
            $this->entityManager->getRepository('ApiBundle:PtyProcessusType')
                ->findDrawers($process->getProId()),
            'typCode'
        );
    }

    /**
     * Supprime une liste de tiroirs d'un processus
     *
     * @param array $drawersList Une liste de tiroirs
     * @param ProProcessus $process Une instance de ProProcessus
     */
    private function removeDrawers(array $drawersList, ProProcessus $process)
    {
        if (count($drawersList)) {
            $this->entityManager->getRepository('ApiBundle:PtyProcessusType')
                ->removeDrawers($drawersList, $process->getProId());
        }
    }

    /**
     * Crée un processus
     *
     * @param ProProcessus $process Une instance de ProProcessus
     * @param array $drawersList Une liste de tiroirs à ajouter
     *
     * @return ProProcessus
     */
    public function createProcess(ProProcessus $process, array $drawersList)
    {
        foreach ($drawersList as $drawer) {
            $this->addDrawer($drawer, $process);
        }
        $this->entityManager->persist($process);
        $this->entityManager->flush();
        return $process;
    }

    /**
     * Ajoute un tiroir
     *
     * @param string $drawer Un tiroir
     * @param ProProcessus $process Une instance de ProProcessus
     */
    private function addDrawer($drawer, ProProcessus $process)
    {
        $processType = new PtyProcessusType();
        $processType->setPtyProcessus($process);
        $processType->setPtyType($this->entityManager->getReference('ApiBundle:TypType', $drawer));
        $this->entityManager->persist($processType);
    }

    /**
     * Contrôle que le processus existe, est éditable et que l'utilisateur en est propriétaire
     *
     * @param $process
     *
     * @return array|string
     */
    public function controlProcess($process)
    {
        if (!$process instanceof ProProcessus) {
            return [ProProcessus::ERR_DOES_NOT_EXIST, ''];
        } elseif (!$this->isOwner($process)) {
            return [ProProcessus::ERR_NOT_OWNER, $process->getProLibelle()];
        }
        return '';
    }

    /**
     * Vérifie le propriétaire du processus
     *
     * @param ProProcessus $process Une instance de ProProcessus
     *
     * @return bool
     */
    public function isOwner(ProProcessus $process)
    {
        return $process->getProUser()->getUsrLogin() == $this->user->getUsrLogin();
    }

    /**
     * Supprime un processus
     *
     * @param ProProcessus $process Une instance de ProProcessus
     */
    public function removeProcess(ProProcessus $process)
    {
        $this->entityManager->remove($process);
        $this->entityManager->flush();
    }
}
