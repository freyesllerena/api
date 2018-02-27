<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\AdoAnnotationsDossier;
use ApiBundle\Entity\AnoAnnotations;
use ApiBundle\Entity\FolFolder;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Form\AdoAnnotationsDossierType;
use ApiBundle\Form\AnoAnnotationsType;
use ApiBundle\Manager\AnnotationManager;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AnnotationController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Annotation",
     *     description="Renvoie la liste des annotations pour un document",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/annotation/document/{documentId}/list", requirements={"documentId":"\d+"})
     *
     * @param integer $documentId L'id du document
     *
     * @return DocapostJsonResponse
     */
    public function getDocumentAnnotationsList($documentId)
    {
        return $this->export($this->get('api.manager.annotation')->retrieveAnnotationsList($documentId));
    }

    /**
     * @ApiDoc(
     *     section="Annotation",
     *     description="Supprime une annotation d'un document",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Delete("/annotation/document")
     *
     * @return DocapostJsonResponse
     */
    public function deleteDocumentAnnotation()
    {
        $annotationManager = $this->get('api.manager.annotation');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['anoId' => 'int']);
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Lecture d'une annotation
        if (!$annotation = $annotationManager->getAnnotationById($datas->anoId)) {
            $this->addResponseMessage(AnoAnnotations::ERR_DOES_NOT_EXIST);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        }
        // Contrôle du propriétaire
        if (!$annotationManager->isOwner($annotation)) {
            $this->addResponseMessage(AnoAnnotations::ERR_NOT_OWNER);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
        }
        // Vérifie que c'est la dernière annotation
        $lastAnnotation = $annotationManager->isLastestAnnotation($annotation);
        if (null === $lastAnnotation) {
            $this->addResponseMessage(AnoAnnotations::ERR_LIST_EMPTY);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        } elseif (!$lastAnnotation) {
            $this->addResponseMessage(AnoAnnotations::ERR_NOT_ALLOWED_TO_DELETE);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
        }
        // Mise à jour
        $annotationManager->updateAnnotationEtat($annotation);
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Annotation",
     *     description="Ajoute une annotation sur un document",
     *     input="ApiBundle\Form\AnoAnnotationsType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/annotation/document")
     *
     * @return DocapostJsonResponse
     */
    public function postDocumentAnnotation()
    {
        $annotationManager = $this->get('api.manager.annotation');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(
            true,
            true,
            [
                'anoTexte' => null,
                'anoStatut' => null,
                'anoFiche' => 'int'
            ]
        );
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Vérifie que le document existe
        if (!$this->get('api.manager.document')->getDocumentById($datas['anoFiche'])) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED,
                [DocapostController::MSG_CODE_VALUE => $datas['anoFiche']]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        }
        // Instanciation d'une nouvelle annotation
        $annotation = $annotationManager->instantiateAnnotation();
        // Validation d'une annotation
        $annotationForm = $this->createForm(AnoAnnotationsType::class, $annotation);
        if (!$annotationForm->submit($datas, false)->isValid()) {
            // Erreur(s) pendant la validation
            $this->translateErrorsFormIntoResponseMessages($annotationForm);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Création d'une annotation
        $annotationManager->createAnnotation($annotation);
        return $this->export($this->convertIntoArray($annotation, ['anoLogin', 'anoFiche']));
    }

    /**
     * @ApiDoc(
     *     section="Annotation",
     *     description="Renvoie la liste des annotations pour un dossier",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/annotation/folder/{folderId}/list", requirements={"folderId":"\d+"})
     *
     * @param integer $folderId L'id du dossier
     *
     * @return DocapostJsonResponse
     */
    public function getFolderAnnotationsList($folderId)
    {
        return $this->export(
            $this->get('api.manager.annotation')
                 ->retrieveAnnotationsList($folderId, AnnotationManager::ANNOTATION_TYPE_FOLDER)
        );
    }

    /**
     * @ApiDoc(
     *     section="Annotation",
     *     description="Supprime une annotation d'un dossier",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Delete("/annotation/folder")
     *
     * @return DocapostJsonResponse
     */
    public function deleteFolderAnnotation()
    {
        $annotationManager = $this->get('api.manager.annotation');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['adoId' => 'int']);
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Lecture d'une annotation
        if (!$annotationFolder = $annotationManager->getAnnotationById(
            $datas->adoId,
            AnnotationManager::ANNOTATION_TYPE_FOLDER
        )) {
            $this->addResponseMessage(AnoAnnotations::ERR_DOES_NOT_EXIST);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        }
        // Contrôle du propriétaire
        if (!$annotationManager->isOwner($annotationFolder)) {
            $this->addResponseMessage(AnoAnnotations::ERR_NOT_OWNER);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
        }
        // Vérifie que c'est la dernière annotation
        $lastAnnotation = $annotationManager->isLastestAnnotation($annotationFolder);
        if (null === $lastAnnotation) {
            $this->addResponseMessage(AdoAnnotationsDossier::ERR_LIST_EMPTY);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        } elseif (!$lastAnnotation) {
            $this->addResponseMessage(AnoAnnotations::ERR_NOT_ALLOWED_TO_DELETE);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
        }
        // Mise à jour
        $annotationManager->updateAnnotationEtat($annotationFolder);
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Annotation",
     *     description="Ajoute une annotation sur un dossier",
     *     input="ApiBundle\Form\AdoAnnotationsDossierType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/annotation/folder")
     *
     * @return DocapostJsonResponse
     */
    public function postFolderAnnotation()
    {
        $annotationManager = $this->get('api.manager.annotation');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(
            true,
            true,
            [
                'adoTexte' => null,
                'adoStatut' => null,
                'adoFolder' => 'int'
            ]
        );
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Vérifie que le dossier existe
        if (!$this->get('api.manager.folder')->getFolderById($datas['adoFolder'])) {
            $this->addResponseMessage(FolFolder::ERR_DOES_NOT_EXIST);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        }
        // Instanciation d'une nouvelle annotation
        $annotationFolder = $annotationManager->instantiateAnnotation(AnnotationManager::ANNOTATION_TYPE_FOLDER);
        // Validation d'une annotation
        $annotationFolderForm = $this->createForm(AdoAnnotationsDossierType::class, $annotationFolder);
        if (!$annotationFolderForm->submit($datas, false)->isValid()) {
            // Erreur(s) pendant la validation
            $this->translateErrorsFormIntoResponseMessages($annotationFolderForm);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Création d'une annotation
        $annotationManager->createAnnotation($annotationFolder);
        return $this->export($this->convertIntoArray($annotationFolder, ['adoLogin', 'adoFolder']));
    }
}
