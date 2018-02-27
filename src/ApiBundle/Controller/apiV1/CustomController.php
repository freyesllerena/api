<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\DicDictionnaire;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\DocapostController;

class CustomController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Custom",
     *     description="Renvoie le User Context (au chargement de l'application)",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Get("/customContext")
     *
     * @return DocapostJsonResponse
     */
    public function getCustomContext()
    {
        // Récupération des paramètres dans la Query
        $parameters = $this->getRequestParameters();
        // Validation des paramètres
        if (!$this->validateWSParameters(
            $parameters,
            ['device' => implode('|', DicDictionnaire::$availableDevices)]
        )
        ) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        return $this->export(
            $this->get('api.manager.user_context')->retrieveUserContext($parameters['device'])
        );
    }
}
