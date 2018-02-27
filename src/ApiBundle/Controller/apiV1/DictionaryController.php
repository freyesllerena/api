<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\DicDictionnaire;
use Doctrine\ORM\AbstractQuery;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class DictionaryController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Dictionnaire",
     *     description="Renvoie le dictionnaire en fonction du support et de la langue donnée",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Get("/dictionary")
     *
     * @return DocapostJsonResponse
     */
    public function getDictionary()
    {
        // Récupération des paramètres dans la Query
        $parameters = $this->getRequestParameters();
        // Validation des paramètres
        if (!$this->validateWSParameters(
            $parameters,
            ['device' => implode('|', DicDictionnaire::$availableDevices)]
        )) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        return $this->export(
            $this->get('api.manager.dictionnaire')->getDictionnaire($parameters['device'])
        );
    }
}
