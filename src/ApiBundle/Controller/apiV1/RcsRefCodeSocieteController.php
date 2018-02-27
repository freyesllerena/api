<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\RcsRefCodeSociete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\DocapostController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RcsRefCodeSocieteController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Renvoie la liste des codes société pour un PAC donné",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          404="Le code PAC n'existe pas pour cette instance"
     *     }
     * )
     * @Get("/referential/pac/{pacId}/company", requirements={"pacId":"\w+"})
     *
     * @param String $pacId     Code pac
     * @return DocapostJsonResponse
     */
    public function getReferentialCompany($pacId)
    {
        // Vérifie si le code pac existe
        if (!$this->getDoctrine()->getRepository('ApiBundle:RidRefId')->isPacExists($pacId)) {
            $this->addResponseMessage(RcsRefCodeSociete::ERR_PAC_DOES_NOT_EXIST);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        }

        return $this->export(
            $this->getDoctrine()->getRepository('ApiBundle:RcsRefCodeSociete')->findByRcsIdCodeClient($pacId)
        );
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdCodeSociete",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdCodeSociete")
     *
     * @return DocapostJsonResponse
     */
    public function getListeRefIdCodeSociete() {
        $results = $this->getDoctrine()->getRepository('ApiBundle:RcsRefCodeSociete')->findAll();

        $results = array_map(function(RcsRefCodeSociete $obj) {
            return array(
                'id' => $obj->getRcsId(),
                'code' => $obj->getRcsCode(),
                'libelle' => $obj->getRcsLibelle(),
                'siren' => $obj->getRcsSiren(),
                'idCodeClient' => $obj->getRcsIdCodeClient(),
            );
        }, $results);
        return $this->export($results);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Detail IdCodeSociete",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Get("/refIdCodeSociete/{id}")
     * @ParamConverter("rcs", class="ApiBundle:RcsRefCodeSociete")
     * @return DocapostJsonResponse
     */
    public function getDetailRefIdCodeSociete(RcsRefCodeSociete $rcs) {
        return $this->export(array(
            'id' => $rcs->getRcsId(),
            'code' => $rcs->getRcsCode(),
            'libelle' => $rcs->getRcsLibelle(),
            'siren' => $rcs->getRcsSiren(),
            'idCodeClient' => $rcs->getRcsIdCodeClient(),
        ));
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdCodeSociete",
     *     input="ApiBundle\Form\RcsRefCodeSocieteType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/refIdCodeSociete")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdCodeSociete()
    {
        $rcs = new RcsRefCodeSociete();

        return $this->putRefIdCodeSociete($rcs);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdCodeSociete",
     *     input="ApiBundle\Form\RcsRefCodeSocieteType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdCodeSociete/{id}")
     * @ParamConverter("rcs", class="ApiBundle:RcsRefCodeSociete")
     * @param RcsRefCodeSociete $rcs
     * @return DocapostJsonResponse
     */
    public function putRefIdCodeSociete(RcsRefCodeSociete $rcs)
    {
        $data = $this->getContentParameters(
            false,
            true,
            ['code' => null, 'libelle' => null, 'idCodeClient' => null, 'siren' => null]
        );
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        $rcs->setRcsCode($data->code)
            ->setRcsLibelle($data->libelle)
            ->setRcsIdCodeClient($data->idCodeClient)
            ->setRcsSiren($data->siren);

        $entityManager = $this->get('doctrine')->getManager();
        $entityManager->persist($rcs);
        $entityManager->flush();

        return $this->getDetailRefIdCodeSociete($rcs);
    }
}
