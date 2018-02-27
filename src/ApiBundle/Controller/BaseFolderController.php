<?php

namespace ApiBundle\Controller;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\FolFolder;
use ApiBundle\Entity\FusFolderUser;
use ApiBundle\Entity\IfpIndexfichePaperless;

class BaseFolderController extends DocapostController
{
    /**
     * Ajoute un message et peut éventuellement renvoyer un messageJsonResponse
     *
     * @param null|string $code L'erreur rencontrée
     * @param null|string $value La valeur à envoyer dans le message
     *
     * @return DocapostJsonResponse
     */
    protected function folderMessageResponse($code = null, $value = null)
    {
        switch ($code) {
            case FolFolder::ERR_DOES_NOT_EXIST:
                $this->addResponseMessage(FolFolder::ERR_DOES_NOT_EXIST);
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);

            case FolFolder::ERR_NOT_OWNER:
                $this->addResponseMessage(FolFolder::ERR_NOT_OWNER, [DocapostController::MSG_CODE_LABEL => $value]);

                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
            
            case IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED:
                $this->addResponseMessage(
                    IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED,
                    [DocapostController::MSG_CODE_VALUE => $value]
                );
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_WARN, 404);

            case FolFolder::ERR_OWNER_FOLDER_LABEL_EXISTS:
                $this->addResponseMessage(
                    FolFolder::ERR_OWNER_FOLDER_LABEL_EXISTS,
                    [DocapostController::MSG_CODE_LABEL => $value]
                );
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);

            case FusFolderUser::ERR_DOES_NOT_EXIST:
                $this->addResponseMessage(
                    FusFolderUser::ERR_DOES_NOT_EXIST,
                    [DocapostController::MSG_CODE_LABEL => $value]
                );
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);

            case DocapostController::ERR_INTERNAL_ERROR:
                $this->addResponseMessage(DocapostController::ERR_INTERNAL_ERROR);
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 500);
        }
        return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
    }
}
