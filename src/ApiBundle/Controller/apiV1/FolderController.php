<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\BaseFolderController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\FolFolder;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Form\FolFolderType;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class FolderController extends BaseFolderController
{
    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Crée un dossier",
     *     input="ApiBundle\Form\FolFolderType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/folder")
     *
     * @return DocapostJsonResponse
     */
    public function postFolder()
    {
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(
            true,
            true,
            [
                'folLibelle' => null
            ]
        );
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }
        // Création du dossier
        return $this->handleFolderForm($datas);
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Affiche les dossiers d'un utilisateur",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/folder/list")
     *
     * @return DocapostJsonResponse
     */
    public function getFoldersList()
    {
        return $this->export(
            $this->get('api.manager.folder')->retrieveFoldersListUser()
        );
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Met à jour un dossier",
     *     input="ApiBundle\Form\FolFolderType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Put("/folder")
     *
     * @return DocapostJsonResponse
     */
    public function putFolder()
    {
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(true, true, ['folId' => 'int', 'folLibelle' => null]);
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }
        // Mise à jour du dossier
        return $this->handleFolderForm($datas, 'PUT');
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Supprime un dossier",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Delete("/folder")
     *
     * @return DocapostJsonResponse
     */
    public function deleteFolder()
    {
        $folderManager = $this->get('api.manager.folder');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['folId' => 'int']);
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }
        // Lecture du dossier
        $folder = $folderManager->getFolderById($datas->folId);
        // Validation du dossier et contrôle du propriétaire
        if ($errorCheckFolder = $folderManager->validateFolderAndCheckUserIsOwner($folder)) {
            return $this->folderMessageResponse($errorCheckFolder[0], $errorCheckFolder[1]);
        }
        // Suppression du dossier
        $folderManager->removeFolder($folder);
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Ajoute un ou plusieurs document(s) dans un dossier",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Post("/folder/documents")
     *
     * @return DocapostJsonResponse
     */
    public function postFolderDocument()
    {
        $folderManager = $this->get('api.manager.folder');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['folId' => 'int', 'documentIds' => ['int']]);
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }
        // Lecture du dossier
        $folder = $folderManager->getFolderById($datas->folId);
        // Validation du dossier et contrôle du propriétaire
        if ($error = $folderManager->validateFolderAndCheckUserIsOwner($folder)) {
            return $this->folderMessageResponse($error[0], $error[1]);
        }
        // Ajout des documents dans le dossier
        if ($errorDocumentIds = $folderManager->addFolderDocuments($folder, $datas->documentIds)) {
            $message = '';
            foreach ($errorDocumentIds as $documentId) {
                // Ajout d'un message d'erreur pour chaque document
                $message = $this->folderMessageResponse(IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED, $documentId);
            }
        }
        // Met à jour le nombre de documents dans un dossier
        $folderManager->setFolderNbDocuments($folder);
        if (isset($message)) {
            return $message;
        }
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Supprime un ou plusieurs document(s) d'un dossier",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Delete("/folder/documents")
     *
     * @return DocapostJsonResponse
     */
    public function deleteFolderDocument()
    {
        $folderManager = $this->get('api.manager.folder');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['folId' => 'int', 'documentIds' => ['int']]);
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }
        // Lecture du dossier
        $folder = $folderManager->getFolderById($datas->folId);
        // Validation du dossier et contrôle du propriétaire
        if ($error = $folderManager->validateFolderAndCheckUserIsOwner($folder)) {
            return $this->folderMessageResponse($error[0], $error[1]);
        }
        // Suppression du ou des document(s) du dossier
        $folderManager->deleteFolderDocuments($folder, $datas->documentIds);
        // Met à jour le nombre de documents dans un dossier
        $folderManager->setFolderNbDocuments($folder);
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Partage un dossier avec un ou plusieurs utilisateur(s)",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée",
     *          500="Erreur interne"
     *     }
     * )
     * @Post("/folder/user")
     *
     * @return DocapostJsonResponse|string
     */
    public function postFolderUser()
    {
        $folderManager = $this->get('api.manager.folder');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['folId' => 'int', 'userIds' => ['\w+']]);
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }
        // Lecture du dossier
        $folder = $folderManager->getFolderById($datas->folId);
        // Validation du dossier et contrôle du propriétaire
        if ($error = $folderManager->validateFolderAndCheckUserIsOwner($folder)) {
            return $this->folderMessageResponse($error[0], $error[1]);
        }
        // Partage du dossier avec des utilisateurs
        if ($error = $folderManager->shareFolderUsers($folder, $datas->userIds)) {
            return $this->folderMessageResponse($error);
        }
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Retire le partage d'un dossier avec un utilisateur",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Delete("/folder/user")
     *
     * @return DocapostJsonResponse
     */
    public function deleteFolderUser()
    {
        $folderManager = $this->get('api.manager.folder');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['userId' => '\w+', 'folId' => 'int']);
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }
        // Lecture du dossier
        $folder = $folderManager->getFolderById($datas->folId);
        // Validation du dossier et contrôle du propriétaire
        if ($error = $folderManager->validateFolderAndCheckUserIsOwner($folder)) {
            return $this->folderMessageResponse($error[0], $error[1]);
        }
        // Retrait du partage
        $folderManager->unshareFolderUser($folder, $datas->userId);
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Affiche la liste des utilisateurs pour lesquels le dossier est partagé",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/folder/{folId}/list/users/shared", requirements={"folId":"\d+"})
     *
     * @param integer $folId L'id du dossier
     *
     * @return DocapostJsonResponse
     */
    public function getUsersListIsSharedFolder($folId)
    {
        $folderManager = $this->get('api.manager.folder');
        // Lecture du dossier
        $folder = $folderManager->getFolderById($folId);
        // Validation du dossier et contrôle du propriétaire
        if ($error = $folderManager->validateFolderAndCheckUserIsOwner($folder)) {
            return $this->folderMessageResponse($error[0], $error[1]);
        }
        return $this->export($folderManager->retrieveUsersIsSharedFolder($folId));
    }

    /**
     * @ApiDoc(
     *     section="Dossier",
     *     description="Affiche la liste des utilisateurs pour lesquels le dossier n'est pas partagé",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/folder/{folId}/list/users/unshared", requirements={"folId":"\d+"})
     *
     * @param integer $folId L'id du dossier
     *
     * @return DocapostJsonResponse
     */
    public function getUsersListIsUnsharedFolder($folId)
    {
        $folderManager = $this->get('api.manager.folder');
        // Lecture du dossier
        $folder = $folderManager->getFolderById($folId);
        // Validation du dossier et contrôle du propriétaire
        if ($error = $folderManager->validateFolderAndCheckUserIsOwner($folder)) {
            return $this->folderMessageResponse($error[0], $error[1]);
        }
        return $this->export($folderManager->retrieveUsersIsUnsharedFolder($folId));
    }

    /**
     * Valide le formulaire et ajoute/met à jour un dossier
     *
     * @param array $datas Un tableau représentant les attributs de FolFolder
     * @param string $method La méthode PUT ou POST
     *
     * @return DocapostJsonResponse
     */
    private function handleFolderForm(array $datas = array(), $method = 'POST')
    {
        $folderManager = $this->get('api.manager.folder');
        // Lecture du dossier si mise à jour
        if ($method == 'PUT') {
            $folder = $folderManager->getFolderById($datas['folId']);
            // Validation du dossier et contrôle du propriétaire
            if ($error = $folderManager->validateFolderAndCheckUserIsOwner($folder)) {
                return $this->folderMessageResponse($error[0], $error[1]);
            }
        } else {
            $folder = $folderManager->instantiateFolder();
        }
        // Validation du formulaire
        $folderForm = $this->createForm(FolFolderType::class, $folder);
        if (!$folderForm->submit($datas, false)->isValid()) {
            // Erreur(s) pendant la validation
            $this->translateErrorsFormIntoResponseMessages($folderForm);
            return $this->folderMessageResponse();
        }
        // Contrôle que le dossier n'existe pas déjà pour le propriétaire
        if ($folderManager->hasAnotherFolderLabel($folder)) {
            return $this->folderMessageResponse(FolFolder::ERR_OWNER_FOLDER_LABEL_EXISTS, $folder->getFolLibelle());
        }
        if ($method == 'PUT') {
            // Mise à jour du dossier
            $folder = $folderManager->updateFolder($folder);
        } else {
            // Ajout du dossier et du lien entre le dossier et le user
            $folder = $folderManager->createFolder($folder);
        }
        return $this->export($this->convertIntoArray($folder, ['folCreatedAt', 'folUpdatedAt']));
    }
}
