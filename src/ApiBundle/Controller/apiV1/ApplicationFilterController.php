<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\BaseFolderController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\PdaProfilDefAppli;
use ApiBundle\Form\PdaProfilDefAppliType;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\DocapostController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * WS Filtre applicatifs
 *
 * @package ApiBundle\Controller\apiV1
 */
class ApplicationFilterController extends BaseFolderController
{
    /**
     * @ApiDoc(
     *     section="Filtres applicatifs",
     *     description="Renvoit la liste des filtres applicatifs",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Returned if response empty"
     *     },
     * )
     *
     * @Get("/appliFilter")
     *
     * @return DocapostJsonResponse
     */
    public function getListApplicationFilters()
    {

        $entityManager = $this->get('doctrine')->getManager();

        $rows = $entityManager->getRepository('ApiBundle:PdaProfilDefAppli')->selectAllAndCountUsers();

        $output = array();
        foreach ($rows as $row) {
            $pda = $row['appli'];
            $output[] = array(
                'pdaId' => $pda->getPdaId(),
                'pdaLibelle' => $pda->getPdaLibelle(),
                'pdaNbrUsers' => $row['nbr_users']
            );
        };

        return new DocapostJsonResponse($output);
    }

    /**
     * @ApiDoc(
     *     section="Filtres applicatifs",
     *     description="Détail d'un filtre applicatif",
     *     output="ApiBundle\Form\PdaProfilDefAppliType",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Returned if response empty",
     *          404="Not found"
     *     }
     * )
     *
     * @Get("/appliFilter/{pdaId}")
     * @ParamConverter("pda", class="ApiBundle:PdaProfilDefAppli", options={"id" = "pdaId"})
     *
     * @param PdaProfilDefAppli $pda
     *
     * @return DocapostJsonResponse
     */
    public function getApplicationFilter(PdaProfilDefAppli $pda)
    {
        $form = $this->createForm(PdaProfilDefAppliType::class, $pda);

        return $this->renderForm($form);
    }

    /**
     * @ApiDoc(
     *     section="Filtres applicatifs",
     *     description="Créer un filtre applicatif",
     *     intput="ApiBundle\Form\PdaProfilDefAppliType",
     *     output="ApiBundle\Form\PdaProfilDefAppliType",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Returned if response empty",
     *          404="Not found"
     *     }
     * )
     *
     * @Post("/appliFilter")
     *
     * @return DocapostJsonResponse
     */
    public function postApplicationFilter()
    {
        $data = $this->getContentParameters(
            true,
            true,
            ['pdaLibelle' => null, 'pdaTiroirs' => null, 'pdaNbi' => null, 'pdaNbc' => null]
        );
        if ($this->hasResponseMessage()) {

            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        $pda = new PdaProfilDefAppli();
        $form = $this->createForm(PdaProfilDefAppliType::class, $pda);
        $form->submit($data, true);
        if ($form->isValid()) {

            $pda->setPdaTiroirs($data['pdaTiroirs']);
            $entityManager =  $this->get('doctrine')->getManager();
            $entityManager->persist($pda);
            $entityManager->flush();
            return $this->renderForm($form);
        }

        $this->translateErrorsFormIntoResponseMessages($form);
        return $this->folderMessageResponse();
    }

    /**
     * @ApiDoc(
     *     section="Filtres applicatifs",
     *     description="Modifier un filtre applicatif",
     *     intput="ApiBundle\Form\PdaProfilDefAppliType",
     *     output="ApiBundle\Form\PdaProfilDefAppliType",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Returned if response empty",
     *          404="Not found"
     *     }
     * )
     *
     * @Put("/appliFilter/{pdaId}")
     *
     * @param PdaProfilDefAppli $pda
     * @ParamConverter("pda", class="ApiBundle:PdaProfilDefAppli", options={"id" = "pdaId"})
     *
     * @return DocapostJsonResponse
     */
    public function putApplicationFilter(PdaProfilDefAppli $pda)
    {
        $data = $this->getContentParameters(
            true,
            true,
            ['pdaLibelle' => null, 'pdaTiroirs' => null, 'pdaNbi' => null, 'pdaNbc' => null]
        );
        if ($this->hasResponseMessage()) {

            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }


        $form = $this->createForm(PdaProfilDefAppliType::class, $pda);
        $form->submit($data, false);
        if ($form->isValid()) {
            $pda->setPdaTiroirs($data['pdaTiroirs']);
            $entityManager =  $this->get('doctrine')->getManager();
            $entityManager->persist($pda);
            $entityManager->flush();
            return $this->renderForm($form);
        }

        $this->translateErrorsFormIntoResponseMessages($form);
        return $this->folderMessageResponse();
    }

    /**
     * @ApiDoc(
     *     section="Filtres applicatifs",
     *     description="Modifier un filtre applicatif",
     *     intput="ApiBundle\Form\PdaProfilDefAppliType",
     *     output="ApiBundle\Form\PdaProfilDefAppliType",
     *     statusCodes={
     *          200="Returned on request success",
     *          204="Returned if response empty",
     *          404="Not found"
     *     }
     * )
     *
     * @Delete("appliFilter/{pdaId}")
     * @ParamConverter("pda", class="ApiBundle:PdaProfilDefAppli", options={"id" = "pdaId"})
     * @param PdaProfilDefAppli $pda
     * @return DocapostJsonResponse
     */
    public function deleteApplicationFilter(PdaProfilDefAppli $pda)
    {
        $entityManager = $this->get('doctrine')->getManager();
        $entityManager->remove($pda);
        $entityManager->flush();
    }

    /**
     * @ApiDoc(
     *     section="Filtres applicatifs",
     *     description="Renvoie les utilisateurs associés à un filtre applicatif",
     *     statusCodes={
     *          200="Returned on request success",
     *          404="Returned when no filter found"
     *     },
     * )
     *
     * @Get("/appliFilter/{pdaId}/users")
     *
     * @ParamConverter("pda", class="ApiBundle:PdaProfilDefAppli", options={"id" = "pdaId"})
     *
     * @param PdaProfilDefAppli $pda
     * @return DocapostJsonResponse
     */
    public function getUsersApplicationFilter(PdaProfilDefAppli $pda)
    {
        $users = $this->get('doctrine')->getManager()
            ->getRepository('ApiBundle:UsrUsers')
            ->findByPdaProfilDefAppli($pda);
        $users = array_map(function($user) {
            return array(
                'login' => $user->getUsrLogin(),
                'nom' => $user->getUsrNom(),
                'prenom' => $user->getUsrPrenom(),
            );
        }, $users);
        return new DocapostJsonResponse($users);
    }

    /**
     * Retourne les valeurs d'un formulaire dans un JSON
     *
     * @param FormInterface $form
     * @return DocapostJsonResponse
     */
    private function renderForm(FormInterface $form)
    {
        $data = $form->getViewData();
        $output = array();
        $output['pdaId'] = $data->getPdaId();
        $output['pdaLibelle'] = $data->getPdaLibelle();
        $output['pdaTiroirs'] = $data->getPdaTiroirs();
        $output['pdaNbi'] = $data->getPdaNbi();
        $output['pdaNbc'] = $data->getPdaNbc();
        return $this->export($output);
    }
}
