<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Form\PdhProfilDefHabiType;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\DocapostController;
use ApiBundle\Entity\PdhProfilDefHabi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormInterface;

class PopulationFilterController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Filtres par population",
     *     description="Renvoie la liste des filtres par population",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Returned if response empty"
     *     },
     * )
     *
     * @Get("/populationFilter")
     *
     * @return DocapostJsonResponse
     */
    public function getListAction()
    {
        $entityManager = $this->get('doctrine')->getManager();

        $rows = $entityManager->getRepository('ApiBundle:PdhProfilDefHabi')->selectAllAndCountUsers();

        $output = array();
        foreach ($rows as $row) {
            $pdh = $row['habi'];
            /* @var $pdh PdhProfilDefHabi */
            $output[] = array(
                'pdhId' => $pdh->getPdhId(),
                'pdhLibelle' => $pdh->getPdhLibelle(),
                'pdhNbrUsers' => $row['nbr_users'],
                'pdhIndividuel' => (bool) $pdh->getPdhHabilitationI(),
                'pdhCollectif' => (bool) $pdh->getPdhHabilitationC(),
            );
        };

        return new DocapostJsonResponse($output);
    }

    /**
     * @ApiDoc(
     *     section="Filtres par population",
     *     description="Renvoie le détail d'un filtre par population",
     *     statusCodes={
     *          200="Returned on request success",
     *          404="Returned when no filter found"
     *     },
     * )
     * @Get("/populationFilter/{id}")
     *
     * @ParamConverter("habi", class="ApiBundle:PdhProfilDefHabi", options={"id" = "id"})
     *
     * @param PdhProfilDefHabi $habi
     * @return DocapostJsonResponse
     */
    public function getAction(PdhProfilDefHabi $habi)
    {
        $form = $this->createForm(PdhProfilDefHabiType::class, $habi);
        $output = array('pdhId' => $habi->getPdhId());
        $output += $this->extractFormValues($form);
        $output += array('users' => $this->getHabilitationUsersList($habi));
        return $this->export($output);
    }

    /**
     * @ApiDoc(
     *     section="Filtres par population",
     *     description="Crée un filtre par population",
     *     statusCodes={
     *          200="Returned on request success",
     *          404="Returned when no filter found"
     *     },
     * )
     * @Post("/populationFilter")
     *
     * @return DocapostJsonResponse
     */
    public function postAction()
    {
        $habi = new PdhProfilDefHabi();
        $form = $this->createForm(PdhProfilDefHabiType::class, $habi);
        return $this->handleForm($form);
    }

    /**
     * @ApiDoc(
     *     section="Filtres par population",
     *     description="Modifie un filtre par population",
     *     statusCodes={
     *          200="Returned on request success",
     *          404="Returned when no filter found"
     *     },
     * )
     * @Put("/populationFilter/{id}")
     *
     * @ParamConverter("habi", class="ApiBundle:PdhProfilDefHabi", options={"id" = "id"})
     *
     * @param PdhProfilDefHabi $habi
     * @return DocapostJsonResponse
     */
    public function putAction(PdhProfilDefHabi $habi)
    {
        $form = $this->createForm(PdhProfilDefHabiType::class, $habi);
        return $this->handleForm($form);
    }

    /**
     * @ApiDoc(
     *     section="Filtres par population",
     *     description="Crée un filtre par population",
     *     statusCodes={
     *          200="Returned on request success",
     *          404="Returned when no filter found"
     *     },
     * )
     * @Delete("/populationFilter/{id}")
     *
     * @ParamConverter("habi", class="ApiBundle:PdhProfilDefHabi", options={"id" = "id"})
     *
     * @param PdhProfilDefHabi $habi
     * @return DocapostJsonResponse
     */
    public function deleteAction(PdhProfilDefHabi $habi)
    {
        $entityManager = $this->get('doctrine')->getManager();
        $entityManager->remove($habi);
        $entityManager->flush();

        return array();
    }

    /**
     * @ApiDoc(
     *     section="Filtres par population",
     *     description="Renvoie les utilisateurs associés à un filtre par population",
     *     statusCodes={
     *          200="Returned on request success",
     *          404="Returned when no filter found"
     *     },
     * )
     *
     * @Get("/populationFilter/{id}/users")
     *
     * @ParamConverter("habi", class="ApiBundle:PdhProfilDefHabi", options={"id" = "id"})
     *
     * @param PdhProfilDefHabi $habi
     * @return DocapostJsonResponse
     */
    public function getUsersAction(PdhProfilDefHabi $habi)
    {
        $users = $this->getHabilitationUsersList($habi);
        return new DocapostJsonResponse($users);
    }

    /**
     * Traite le formulaire
     *
     * @param FormInterface $form
     * @return DocapostJsonResponse
     */
    protected function handleForm(FormInterface $form)
    {
        $data = $this->getContentParameters(
            true,
            true,
            ['usrLibelle' => '\w+']
        );

        $form->submit($data, true);
        if ($form->isValid()) {
            $entity = $form->getData();
            $entityManager = $this->get('doctrine')->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();
            $output = array('pdhId' => $entity->getPdhId());
            $output += $this->extractFormValues($form);
            return $this->export($output);
        }

        // Erreur(s) pendant la validation
        $this->translateErrorsFormIntoResponseMessages($form);
        return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
    }

    /**
     * Renvoit la liste d'utilisateurs rattachés à un filtre par population
     *
     * @param PdhProfilDefHabi $habi
     * @return array
     */
    protected function getHabilitationUsersList(PdhProfilDefHabi $habi) {
        $users = $this->get('doctrine')->getManager()
            ->getRepository('ApiBundle:UsrUsers')
            ->findByPdhProfilDefHabi($habi);
        $users = array_map(function($user) {
            return array(
                'login' => $user->getUsrLogin(),
                'nom' => $user->getUsrNom(),
                'prenom' => $user->getUsrPrenom(),
            );
        }, $users);
        return $users;
    }
}
