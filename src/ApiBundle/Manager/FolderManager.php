<?php

namespace ApiBundle\Manager;

use ApiBundle\Controller\DocapostController;
use ApiBundle\Entity\BfoBaseFolder;
use ApiBundle\Entity\FdoFolderDoc;
use ApiBundle\Entity\FolFolder;
use ApiBundle\Entity\FusFolderUser;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Repository\FolFolderRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class FolderManager
{
    /**
     * @var FolFolderRepository
     */
    private $folderRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var UsrUsers
     */
    private $user;

    public function __construct(ContainerInterface $container)
    {
        $this->folderRepository = $container->get('api.repository.folder');
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->logger = $container->get('logger');
        $this->user = $container->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * Contrôle qu'un utilisateur est propriétaire du dossier
     *
     * @param FolFolder $folder Une instance de FolFolder
     *
     * @return bool
     */
    private function isUserOwner(FolFolder $folder)
    {
        return $this->user->getUsrLogin() == $folder->getFolIdOwner();
    }

    /**
     * Renvoie la relation entre un utilisateur et un dossier
     *
     * @param FolFolder $folder Une instance de FolFolder
     * @param string $userLogin Le login de l'utilisateur
     *
     * @return \ApiBundle\Entity\FusFolderUser
     */
    public function getFolderUserLink(FolFolder $folder, $userLogin)
    {
        return $this->entityManager->getRepository('ApiBundle:FusFolderUser')->findOneBy([
            'fusFolder' => $folder,
            'fusUser' => $this->entityManager->getReference('ApiBundle:UsrUsers', $userLogin)
        ]);
    }

    /**
     * Vérifie que l'objet est une instance de FolFolder, lit l'objet et contrôle le propriétaire,
     * et peut éventuellement contrôler l'accès en lecture au dossier
     *
     * @param null|FolFolder $folder Une instance de FolFolder
     *
     * @return string
     */
    public function validateFolderAndCheckUserIsOwner($folder)
    {
        if (!$folder instanceof FolFolder) {
            return [FolFolder::ERR_DOES_NOT_EXIST, ''];
        } elseif (!$this->isUserOwner($folder)) {
            // Vérifie qu'un utilisateur est propriétaire
            return [FolFolder::ERR_NOT_OWNER, $folder->getFolLibelle()];
        }
        return '';
    }

    /**
     * Récupère la liste des dossiers visualisables par un utilisateur
     *
     * @return array
     */
    public function retrieveFoldersListUser()
    {
        return $this->entityManager->getRepository('ApiBundle:FusFolderUser')
            ->findFoldersListUser($this->user->getUsrLogin());
    }

    /**
     * Lit un dossier par son Id
     *
     * @param int $folderId L'id du dossier
     *
     * @return FolFolder
     */
    public function getFolderById($folderId)
    {
        return $this->folderRepository->find($folderId);
    }

    /**
     * Instancie un nouveau dossier
     *
     * @return FolFolder
     */
    public function instantiateFolder()
    {
        $folder = new FolFolder();
        $folder->setFolIdOwner($this->user->getUsrLogin());
        return $folder;
    }

    /**
     * Vérifie la présence d'un dossier en fonction du nom pour un propriétaire
     *
     * @param FolFolder $folder Une instance de FolFolder
     *
     * @return bool
     */
    public function hasAnotherFolderLabel(FolFolder $folder)
    {
        return $this->folderRepository->findAnotherFolderLabel($folder, $this->user->getUsrLogin());
    }

    /**
     * Crée une relation entre un dossier et un utilisateur
     *
     * @param FolFolder $folder Une instance de FolFolder
     * @param UsrUsers $user Une instance de UsrUsers
     */
    public function createUserLink(FolFolder $folder, UsrUsers $user)
    {
        $folderUser = new FusFolderUser();
        $folderUser->setFusFolder($folder);
        $folderUser->setFusUser($user);
        $folderUser->setFusDateAcces(new \DateTime());
        $this->entityManager->persist($folderUser);
    }

    /**
     * Crée un dossier
     *
     * @param FolFolder $folder Une instance de FolFolder
     *
     * @return FolFolder
     */
    public function createFolder(FolFolder $folder)
    {
        $this->entityManager->persist($folder);
        $this->createUserLink($folder, $this->user);
        $this->entityManager->flush();
        return $folder;
    }

    /**
     * Met à jour le libellé d'un dossier
     *
     * @param FolFolder $folder Une instance de FolFolder
     *
     * @return FolFolder
     */
    public function updateFolder(FolFolder $folder)
    {
        $this->entityManager->persist($folder);
        $this->entityManager->flush();
        return $folder;
    }

    /**
     * Supprime un dossier
     *
     * @param FolFolder $folder Une instance de FolFolder
     */
    public function removeFolder(FolFolder $folder)
    {
        $this->entityManager->remove($folder);
        $this->entityManager->flush();
    }

    /**
     * Ajoute des documents à un dossier
     *
     * @param BfoBaseFolder $folder Une instance de FolFolder
     * @param array $documentIds La liste des ids des documents
     *
     * @return array
     */
    public function addFolderDocuments(BfoBaseFolder $folder, array $documentIds)
    {
        $errorDocumentIds = [];
        // Lecture des documents dans le dossier
        $listDocuments = array_column(
            $this->entityManager->getRepository('ApiBundle:FdoFolderDoc')->getFolderDocuments($folder->getFolId()),
            'ifpId'
        );
        foreach ($documentIds as $documentId) {
            if (!$document = $this->entityManager->getRepository('ApiBundle:IfpIndexfichePaperless')
                ->find($documentId)
            ) {
                // Pas de documents correspondant à cet Id
                $errorDocumentIds[] = $documentId;
                continue;
            } elseif (!in_array($documentId, $listDocuments)) {
                $this->createDocumentLink($folder, $document);
            }
        }
        $this->entityManager->flush();
        return $errorDocumentIds;
    }

    /**
     * Supprime les documents d'un dossier
     *
     * @param BfoBaseFolder $folder Une instance de FolFolder
     * @param array $documentIds Une liste d'ids de document
     */
    public function deleteFolderDocuments(BfoBaseFolder $folder, array $documentIds)
    {
        $this->entityManager->getRepository('ApiBundle:FdoFolderDoc')->removeDocuments($folder, $documentIds);
    }

    /**
     * Renvoit le nombre de documents dans un tableau
     *
     * @param BfoBaseFolder $folder
     *
     * @return mixed
     */
    public function countFolderDocuments(BfoBaseFolder $folder)
    {
        return $this->entityManager->getRepository('ApiBundle:FdoFolderDoc')->getFolderNbDocuments($folder->getFolId());
    }

    /**
     * Met à jour le nombre de documents dans un dossier
     *
     * @param BfoBaseFolder $folder Une instance de FolFolder
     */
    public function setFolderNbDocuments(BfoBaseFolder $folder)
    {
        $folder->setFolNbDoc(
            $this->entityManager->getRepository('ApiBundle:FdoFolderDoc')->getFolderNbDocuments($folder->getFolId())
        );
        $this->entityManager->persist($folder);
        $this->entityManager->flush();
    }

    /**
     * Crée la relation entre un dossier et un document
     *
     * @param BfoBaseFolder $folder Une instance de FolFolder
     *
     * @param IfpIndexfichePaperless $document
     */
    private function createDocumentLink(BfoBaseFolder $folder, IfpIndexfichePaperless $document)
    {
        $folderDoc = new FdoFolderDoc();
        $folderDoc->setFdoFolder($folder);
        $folderDoc->setFdoDoc($document);
        $this->entityManager->persist($folderDoc);
    }

    /**
     * Partage un dossier avec des utilisateurs
     *
     * @param FolFolder $folder Une instance de FolFolder
     * @param array $userIds La liste des login des utilisateurs
     *
     * @return string
     */
    public function shareFolderUsers(FolFolder $folder, array $userIds)
    {
        try {
            foreach ($userIds as $userId) {
                /** @var UsrUsers $user */
                $user = $this->entityManager->getReference('ApiBundle:UsrUsers', $userId);
                $this->createUserLink($folder, $user);
            }
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $this->entityManager->clear();
            $this->logger->error($e->getMessage());
            return DocapostController::ERR_INTERNAL_ERROR;
        }
        return '';
    }

    /**
     * Retire le partage d'un dossier avec un utilisateur
     *
     * @param FolFolder $folder Une instance de FolFolder
     * @param string $userId Le login de l'utilisateur
     *
     * @return array|string
     */
    public function unshareFolderUser(FolFolder $folder, $userId)
    {
        // Get FusFolderUser
        if (!$folderUser = $this->getFolderUserLink($folder, $userId)) {
            return [FusFolderUser::ERR_DOES_NOT_EXIST, $folder->getFolLibelle()];
        }
        // Suppression
        $this->entityManager->remove($folderUser);
        $this->entityManager->flush();
        return '';
    }

    /**
     * Récupère la liste des utilisateurs pour lesquels le dossier est partagé
     *
     * @param int $folderId L'id du dossier
     *
     * @return mixed
     */
    public function retrieveUsersIsSharedFolder($folderId)
    {
        return array_column(
            $this->entityManager->getRepository('ApiBundle:FusFolderUser')
                ->findUsersListIsSharedFolder($folderId, $this->user->getUsrLogin()),
            'usrLogin'
        );
    }

    /**
     * Récupère la liste des utilisateurs pour lesquels le dossier n'est pas partagé
     *
     * @param int $folderId L'id du dossier
     *
     * @return mixed
     */
    public function retrieveUsersIsUnsharedFolder($folderId)
    {
        return array_column(
            $this->entityManager->getRepository('ApiBundle:FusFolderUser')->findUsersListIsUnsharedFolder($folderId),
            'usrLogin'
        );
    }
}
