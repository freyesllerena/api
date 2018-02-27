<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\IhaImportHabilitation;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AdminController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Admin",
     *     description="Importe en masse des habilitations",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/habilitation")
     *
     * @return DocapostJsonResponse
     */
    public function postUserHabilitation()
    {
        $file = $this->container->get('request')->files->get('file');
        if (!$file) {
            $this->addResponseMessage(
                DocapostController::ERR_PARAMETER_IS_MISSING,
                [DocapostController::MSG_CODE_PARAMETER => 'file'],
                'file'
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        if (!$file->isValid()) {
            $this->addResponseMessage(
                DocapostController::ERR_INTERNAL_ERROR
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        $habilitationImport = $this->get('api.manager.habilitation');
        if (!$habilitationImport->hasCorrectMimeType($file)) {
            $this->addResponseMessage(
                DocapostController::ERR_FILE_CONTENT_INCORRECT,
                [DocapostController::MSG_CODE_LABEL => $file->getClientOriginalName()]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        if (!$habilitationImport->hasCorrectExtension($file)) {
            $this->addResponseMessage(
                DocapostController::ERR_FILE_EXTENSION_INCORRECT,
                [DocapostController::MSG_CODE_LABEL => $file->getClientOriginalName()],
                'file'
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        if (!$habilitationImport->handleUsersHabilitationsFile($file)) {
            $this->addResponseMessage(
                IhaImportHabilitation::ERR_IHA_HABILITATION_IMPORT_FAILED
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        $this->addResponseMessage(
            IhaImportHabilitation::INFO_IHA_HABILITATION_IMPORT_WAS_SUCCESSFUL
        );
        return $this->messageJsonResponse(DocapostController::MSG_LEVEL_INFO, 200);
    }

    /**
     * @ApiDoc(
     *     section="Admin",
     *     description="Récupère la liste des imports des habilitations en masse effectués et leur état",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *     }
     * )
     * @Get("/habilitation")
     *
     * @return DocapostJsonResponse
     */
    public function getUserHabilitation()
    {
        return $this->export(
            $this->get('api.manager.habilitation')->retrieveHabilitationImportList()
        );
    }

    /**
     * @ApiDoc(
     *     section="Admin",
     *     description="Exporte les utilisateurs qui n'ont pas ou ont des habilitations incomplètes",
     *     statusCodes={
     *          200="Requête traitée avec succès"
     *     }
     * )
     * @Get("/habilitation/model")
     *
     * @return DocapostJsonResponse
     */
    public function getUserHabilitationModel()
    {
        return $this->get('api.manager.habilitation')->retrieveHabilitationModel();
    }
}
