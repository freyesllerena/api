<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\IinIdxIndiv;
use ApiBundle\Security\UserToken;
use Doctrine\ORM\AbstractQuery;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class IdxIndivController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Renvoie la liste des matricules RH avec leur nom, prénom et nom de jeune fille",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/indiv/matricule/list")
     *
     * @return DocapostJsonResponse
     */
    public function getMatriculeList()
    {
        $memcache = $this->container->get('memcache.default');
        /** @var UserToken $userToken */
        $userToken = $this->container->get('security.token_storage')->getToken();
        $numInstance = $userToken->getNumInstance();
        if (!$matricules = json_decode(
            $memcache->get('API::' . $numInstance . '::matriculesList')
        )
        ) {
            $fields = [
                'iin.iinIdNumMatriculeRh',
                'iin.iinIdNomSalarie',
                'iin.iinIdPrenomSalarie'
            ];
            $matricules = $this->getDoctrine()->getManager()->getRepository('ApiBundle:IinIdxIndiv')
                ->findAllOrdered($fields, AbstractQuery::HYDRATE_ARRAY);
            $memcache->set('API::' . $numInstance . '::matriculesList', json_encode($matricules), 0, 86400);
        }

        return $matricules;
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Renvoie la liste des salariés",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/idxIndiv")
     *
     * @return DocapostJsonResponse
     */
    public function getListIdxIndiv()
    {
        $results = $this->get('doctrine')->getRepository('ApiBundle:IinIdxIndiv')->findAll();
        $results = array_map(function(IinIdxIndiv $idx) {
            return array(
                'iinId' => $idx->getIinId(),
                'iinIdNomSalarie' => $idx->getIinIdNomSalarie(),
                'iinIdPrenomSalarie' => $idx->getIinIdPrenomSalarie(),
            );
        }, $results);
        return $this->export($results);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Détail d'un salarié",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *          404="Resource non trouvée"
     *     }
     * )
     * @Get("/idxIndiv/{iinId}")
     * @ParamConverter("iin", class="ApiBundle:IinIdxIndiv", options={"id" = "iinId"})
     * @param IinIdxIndiv $iin
     * @return DocapostJsonResponse
     */
    public function getDetailIdxIndiv(IinIdxIndiv $iin)
    {
        $output = array();
        $attributes = (array) $iin;
        foreach ($attributes as $key => $value) {
            $key = substr($key, 30);
            $output[$key] = $value;
        }

        $dates = array('iinIdDateEntree', 'iinIdDateSortie', 'iinIdDateNaissanceSalarie');
        foreach ($dates as $date) {
            $output[$date] = $output[$date] instanceof \DateTime ? $output[$date]->format('Y-m-d') : null;
        }

        $datetimes = array('iinCreatedAt', 'iinUpdatedAt');
        foreach ($datetimes as $datetime) {
            $output[$datetime] = $output[$datetime] instanceof \DateTime ? $output[$datetime]->format('Y-m-d h:i:s') : null;
        }
        return $this->export($output);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Création d'un salarié",
	 *     input="ApiBundle\Form\IinIdxIndivType",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Post("/idxIndiv")
     * @return DocapostJsonResponse
     */
    public function postIdxIndiv()
    {
        $iin = new IinIdxIndiv();
        return $this->putIdxIndiv($iin);
    }

    /**
     * @ApiDoc(
     *     section="Référentiel",
     *     description="Modification d'un salarié",
	 *     input="ApiBundle\Form\IinIdxIndivType",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
	 *			400="Un ou plusieurs paramètres sont manquants",
     *          404="Resource non trouvée"
     *     }
     * )
     * @Put("/idxIndiv/{iinId}")
     * @ParamConverter("iin", class="ApiBundle:IinIdxIndiv", options={"id" = "iinId"})
     * @param IinIdxIndiv $iin
     * @return DocapostJsonResponse
     */
    public function putIdxIndiv(IinIdxIndiv $iin) {
        $datas = $this->getContentParameters(false, true, [
            'iinCodeClient' => null,
            'iinIdCodeSociete' => null,
            'iinIdCodeJalon' => null,
            'iinIdCodeEtablissement' => null,
            'iinIdLibEtablissement' => null,
            'iinIdNomSociete' => null,
            'iinIdNomClient' => null,
            'iinIdPeriodePaie' => null,
            'iinIdNomSalarie' => null,
            'iinIdPrenomSalarie' => null,
            'iinIdNumNir' => null,
            'iinNumMatricule' => null,
            'iinFichierIndex' => null,
            'iinIdCodeCategProfessionnelle' => null,
            'iinIdCodeCategSocioProf' => null,
            'iinIdTypeContrat' => null,
            'iinIdAffectation1' => null,
            'iinIdAffectation2' => null,
            'iinIdAffectation3' => null,
            'iinIdNumSiren' => null,
            'iinIdNumSiret' => null,
            'iinIdDateNaissanceSalarie' => null,
            'iinIdNumMatriculeRh' => null,
            'iinIdCodeActivite' => null,
        ]);
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        foreach ($datas as $key => $value) {
            if (in_array($value, array('iinIdDateEntree', 'iinIdDateSortie', 'iinIdDateNaissanceSalarie'))) {
                if ($value) {
                    $value = new \Datetime($value);
                }
            }

            $setter = 'set'.ucfirst($key);
            if (method_exists($iin, $setter)) {
                $iin->{$setter}($value);
            }
        }

        $entityManager = $this->get('doctrine')->getManager();
        $entityManager->persist($iin);
        $entityManager->flush();

        return $this->getDetailIdxIndiv($iin);
    }
}
