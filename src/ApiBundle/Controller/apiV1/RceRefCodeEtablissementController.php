<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\RceRefCodeEtablissement;
use ApiBundle\Entity\RcsRefCodeSociete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\DocapostController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RceRefCodeEtablissementController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdCodeEtablissement",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdCodeEtablissement")
     *
     * @return DocapostJsonResponse
     */
    public function getListeRefIdCodeSociete() {
        $results = $this->getDoctrine()->getRepository('ApiBundle:RceRefCodeEtablissement')->findAll();

        $results = array_map(function(RceRefCodeEtablissement $obj) {
            return array(
                'id' => $obj->getRceId(),
                'code' => $obj->getRceCode(),
                'libelle' => $obj->getRceLibelle(),
                'idSociete' => $obj->getRceSociete()->getRcsId(),
            );
        }, $results);
        return $this->export($results);
    }


    /**
     * @param RceRefCodeEtablissement $rce
     * @return DocapostJsonResponse
     */
     public function getDetailRefIdCodeEtablissement(RceRefCodeEtablissement $rce)
     {
         return $this->export(array(
             'id' => $rce->getRceId(),
             'code' => $rce->getRceCode(),
             'libelle' => $rce->getRceLibelle(),
             'idSociete' => $rce->getRceSociete()->getRcsId(),
			 'nic' => $rce->getRceNic(),
         ));
     }

     /**
      * @ApiDoc(
      *     section="Référentiel",
      *     description="Création IdCodeEtablissement",
      *     input="ApiBundle\Form\RceRefCodeEtablissementType",
      *     statusCodes={
      *          200="Requête traitée avec succés",
      *          204="Requête traitée avec succès mais pas d’information à renvoyer",
      *          400="Un ou plusieurs paramètres sont manquants"
      *     }
      * )
      * @Post("/refIdCodeEtablissement")
      *
      * @return DocapostJsonResponse
      */
     public function postRefIdCodeSociete()
     {
         $rce = new RceRefCodeEtablissement();

         return $this->putRefIdCodeSociete($rce);
     }
	 
	 /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdCodeEtablissement",
	 *     input="ApiBundle\Form\RceRefCodeEtablissementType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *          400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdCodeEtablissement/{id}")
     * @ParamConverter("rcs", class="ApiBundle:RceRefCodeEtablissement")
     * @param RceRefCodeEtablissement $rce
     * @return DocapostJsonResponse
     */
    public function putRefIdCodeSociete(RceRefCodeEtablissement $rce)
    {
         $data = $this->getContentParameters(
             false,
             true,
             ['code' => null, 'libelle' => null, 'idSociete' => null, 'nic' => null]
         );
         if ($this->hasResponseMessage()) {
             return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
         }

		 $entityManager = $this->get('doctrine')->getManager();
         $societe = $entityManager->getRepository('ApiBundle:RcsRefCodeSociete')->find($data->idSociete);

         $rce->setRceCode($data->code)
             ->setRceLibelle($data->libelle)
             ->setRceSociete($societe)
			 ->setRceNic($data->nic);

         $entityManager = $this->get('doctrine')->getManager();
         $entityManager->persist($rce);
         $entityManager->flush();

         return $this->getDetailRefIdCodeEtablissement($rce);
    }
}
