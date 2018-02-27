<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\RceRefCodeEtablissement;
use ApiBundle\Entity\RcsRefCodeSociete;
use ApiBundle\Entity\RidRefId;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\DocapostController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RefIdController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Renvoie la liste des codes PAC d'une instance",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Il manque des paramètres pour traiter la requête"
     *     }
     * )
     * @Get("/referential/pac")
     *
     * @return DocapostJsonResponse
     */
    public function getReferentialPac()
    {
        // Lecture des codes PAC d'une instance
        return $this->export(
            $this->get('api.manager.referential')->retrievePacList()
        );
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdCodeClient",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdCodeClient")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdCodeClient()
    {
        return $this->getListeRefId('CodeClient');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdCodeClient",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdCodeClient")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdCodeClient()
    {
        return $this->postRefId('CodeClient');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdCodeClient",
     *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdCodeClient/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdCodeClient(RidRefId $rid)
    {
        return $this->putRefId('CodeClient', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdCodeJalon",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"     *     }
     * )
     * @Get("/refIdCodeJalon")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdCodeJalon() {
        return $this->getListeRefId('IdCodeJalon');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdCodeJalon",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdCodeJalon")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdCodeJalon()
    {
        return $this->postRefId('IdCodeJalon');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdCodeJalon",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdCodeJalon/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdCodeJalon(RidRefId $rid)
    {
        return $this->putRefId('IdCodeJalon', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdCodeCategSocioProf",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"     *     }
     * )
     * @Get("/refIdCodeCategSocioProf")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdCodeCategSocioProf()
    {
        return $this->getListeRefId('IdCodeCategProfessionnelle');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdCodeCategSocioProf",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdCodeCategSocioProf")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdCodeCategSocioProf()
    {
        return $this->postRefId('IdCodeCategProfessionnelle');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdCodeCategProfessionnelle",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdCodeCategSocioProf/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdCodeCategSocioProf(RidRefId $rid)
    {
        return $this->putRefId('IdCodeCategProfessionnelle', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdTypeContrat",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"     *     }
     * )
     * @Get("/refIdTypeContrat")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdTypeContrat()
    {
        return $this->getListeRefId('TypeContrat');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdTypeContrat",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdTypeContrat")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdTypeContrat()
    {
        return $this->postRefId('TypeContrat');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdTypeContrat",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdTypeContrat/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdTypeContrat(RidRefId $rid)
    {
        return $this->putRefId('TypeContrat', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdAffectation1",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdAffectation1")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdAffectation1()
    {
        return $this->getListeRefId('IdAffectation1');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdAffectation1",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdAffectation1")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdAffectation1()
    {
        return $this->postRefId('IdAffectation1');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdAffectation1",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdAffectation1/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdAffectation1(RidRefId $rid)
    {
        return $this->putRefId('IdAffectation1', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdAffectation2",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdAffectation2")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdAffectation2()
    {
        return $this->getListeRefId('IdAffectation2');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdAffectation2",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdAffectation2")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdAffectation2()
    {
        return $this->postRefId('IdAffectation2');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdAffectation2",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdAffectation2/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdAffectation2(RidRefId $rid)
    {
        return $this->putRefId('IdAffectation2', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdAffectation3",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdAffectation3")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdAffectation3()
    {
        return $this->getListeRefId('IdAffectation3');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdAffectation3",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdAffectation3")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdAffectation3()
    {
        return $this->postRefId('IdAffectation3');
    }


    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdAffectation3",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdAffectation3/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdAffectation3(RidRefId $rid)
    {
        return $this->putRefId('IdAffectation3', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdLibre1",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdLibre1")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdLibre1()
    {
        return $this->getListeRefId('IdLibre1');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdLibre1",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdLibre1/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdLibre1(RidRefId $rid)
    {
        return $this->putRefId('IdLibre1', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdLibre1",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdLibre1")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdLibre1()
    {
        return $this->postRefId('IdLibre1');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdLibre2",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdLibre2")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdLibre2()
    {
        return $this->getListeRefId('IdLibre2');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdLibre2",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdLibre2")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdLibre2()
    {
        return $this->postRefId('IdLibre2');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdLibre2",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdLibre2/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdLibre2(RidRefId $rid)
    {
        return $this->putRefId('IdLibre2', $rid);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Liste IdCodeActivite",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/refIdCodeActivite")
     *
     * @return DocapostJsonResponse
     */
    public function getRefIdCodeActivite()
    {
        return $this->getListeRefId('IdCodeActivite');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création IdCodeActivite",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/refIdCodeActivite")
     *
     * @return DocapostJsonResponse
     */
    public function postRefIdCodeActivite()
    {
        return $this->postRefId('IdCodeActivite');
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification IdCodeActivite",
	 *     input="ApiBundle\Form\RidRefIdType",
     *     statusCodes={
     *          200="Requête traitée avec succés",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/refIdCodeActivite/{id}")
     * @ParamConverter("rid", class="ApiBundle:RidRefId")
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    public function putRefIdCodeActivite(RidRefId $rid)
    {
        return $this->putRefId('IdCodeActivite', $rid);
    }

    /**
     * Renvoit la liste d'un référentiel
     *
     * @param $type
     * @return DocapostJsonResponse
     */
    protected function getListeRefId($type)
    {
        $results = $this->getDoctrine()->getRepository('ApiBundle:RidRefId')->findBy(array(
            'ridType' => $type
        ));

        $results = array_map(function(RidRefId $obj) {
            return array(
                'id' => $obj->getRidId(),
                'code' => $obj->getRidCode(),
                'libelle' => $obj->getRidLibelle(),
                'idCodeClient' => $obj->getRidIdCodeClient(),
            );
        }, $results);
        return $this->export($results);
    }

    /**
     * Renvoit le détail d'un objet référentiel
     *
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    protected function getDetailRefId(RidRefId $rid) {
        return $this->export(array(
            'id' => $rid->getRidId(),
            'code' => $rid->getRidCode(),
            'libelle' => $rid->getRidLibelle(),
            'idCodeClient' => $rid->getRidIdCodeClient(),
        ));
    }

    /**
     * Créé un nouvel objet référentiel
     *
     * @param $type
     * @return DocapostJsonResponse
     */
    protected function postRefId($type) {
        $data = $this->getContentParameters(
            false,
            true,
            ['code' => null, 'libelle' => null, 'idCodeClient' => null]
        );
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        $rid = new RidRefId();
        $rid->setRidType($type)
            ->setRidCode($data->code)
            ->setRidLibelle($data->libelle)
            ->setRidIdCodeClient($data->idCodeClient);

        $entityManager = $this->get('doctrine')->getManager();
        $entityManager->persist($rid);
        $entityManager->flush();

        return $this->getDetailRefId($rid);
    }

    /**
     * Modifie un nouvel objet référentiel
     *
     * @param $type
     * @param RidRefId $rid
     * @return DocapostJsonResponse
     */
    protected function putRefId($type, RidRefId $rid) {
        $data = $this->getContentParameters(
            false,
            true,
            ['code' => null, 'libelle' => null, 'idCodeClient' => null]
        );
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        $rid->setRidType($type)
            ->setRidCode($data->code)
            ->setRidLibelle($data->libelle)
            ->setRidIdCodeClient($data->idCodeClient);

        $entityManager = $this->get('doctrine')->getManager();
        $entityManager->persist($rid);
        $entityManager->flush();

        return $this->getDetailRefId($rid);
    }
}
