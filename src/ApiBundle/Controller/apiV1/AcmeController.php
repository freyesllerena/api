<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\DocapostJsonResponse;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\DocapostController;

class AcmeController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Acme",
     *     description="Récupère un nom de fichier et retourne son contenu",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     * @Get("/getAcme/{filename}")
     *
     * @param string $filename Le nom du fichier
     * @return DocapostJsonResponse
     * @codeCoverageIgnore
     */
    public function getAction($filename = '')
    {
        if (empty($filename)) {

            // Le nom du fichier n'est pas renseigné
            return new DocapostJsonResponse(array('message' => 'Un nom de fichier est requis'), 400);
        }

        $path = $this->get('kernel')->getRootDir() . '/../../json_template/' . $filename . '.json';

        // Le fichier existe ?
        if (!file_exists($path)) {

            // Le fichier n'existe pas
            return new DocapostJsonResponse(array('message' => 'Le fichier n\'existe pas'), 400);
        } else {

            // Lecture du contenu du fichier
            return $this->export(file_get_contents($path));
        }
    }
}
