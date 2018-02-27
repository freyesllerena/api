<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\BaseFolderController;
use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Form\UsrUsersType;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use ApiBundle\Entity\RcsRefCodeSociete;

/**
 * Web Services Utilisateurs
 *
 * @Security("has_role('chef de file') or has_role('chef de file expert')")
 *
 * @package ApiBundle\Controller\apiV1
 */
class UserController extends BaseFolderController
{
    /**
     * @ApiDoc(
     *     section="Utilisateur",
     *     description="Renvoie la liste des utilisateurs",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée",
     *          500="Erreur interne"
     *     }
     * )
     * @Get("/user")
     *
     * @return DocapostJsonResponse
     */
    public function getUserList()
    {
        $output = array();

        $rows = $this->get('doctrine')
            ->getManager()
            ->getRepository('ApiBundle:UsrUsers')
            ->selectAllUsersAndCountFilters();

        $securityManager = $this->get('api.manager.security');
        foreach ($rows as $row) {
            $user = $row['user'];
            /* @var $user UsrUsers */
            $authorizations = $user->getUsrAuthorizations();
            foreach ($authorizations as $habilitation => &$permission) {
                $permission = $securityManager->convertHabilitationToPermissions($habilitation, $permission);
            }
            $output[] = array(
                'usrId' => base64_encode($user->getUsrLogin()),
                'usrLogin' => $user->getUsrLogin(),
                'usrNom' => $user->getUsrNom(),
                'usrPrenom' => $user->getUsrPrenom(),
                'usrEmail' => $user->getUsrAdresseMail(),
                'usrTypeHabilitation' => $user->getUsrTypeHabilitation(),
                'usrNbrFiltresApplicatifs' => (int) $row['filtres_applicatifs'],
                'usrNbrFiltresPopulations' => (int) $row['filtres_populations'],
                'usrActif' => $user->isUsrActif(),
                'usrAuthorizations' => $authorizations
            );
        }

        return $this->export($output);
    }

    /**
     * @ApiDoc(
     *     section="Utilisateur",
     *     description="Renvoie le détail d'un utilisateur",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée",
     *          500="Erreur interne"
     *     }
     * )
     * @Get("/user/{user}")
     *
     * @ParamConverter(
     *     "user", class="ApiBundle:UsrUsers", options={"repository_method" = "findOneByBase64EncodedLogin"})
     *
     * @param UsrUsers $user
     *
     * @return DocapostJsonResponse
     */
    public function getUserAction(UsrUsers $user)
    {
        $form = $this->createForm(UsrUsersType::class, $user);

        return $this->renderForm($form);
    }

    /**
     * @ApiDoc(
     *     section="Utilisateur",
     *     description="Modifie un utilisateur",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée",
     *          500="Erreur interne"
     *     }
     * )
     * @Put("/user/{user}")
     *
     * @ParamConverter(
     *     "user", class="ApiBundle:UsrUsers", options={"repository_method" = "findOneByBase64EncodedLogin"})
     *
     * @param UsrUsers $user
     *
     * @return DocapostJsonResponse
     */
    public function putUser(UsrUsers $user)
    {
        $data = $this->getContentParameters(
            true,
            true,
            ['usrLogin' => '\w+']
        );

        $form = $this->createForm(UsrUsersType::class, $user);
        $form->submit($data, false);
        if ($form->isValid()) {
            $entityManager =  $this->get('doctrine')->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->get('api.manager.user')->saveProfiles($user, $data['usrProfils']);
            return $this->renderForm($form);
        }

        $this->translateErrorsFormIntoResponseMessages($form);
        return $this->folderMessageResponse();
    }

    /**
     * @ApiDoc(
     *     section="Utilisateur",
     *     description="Désactive un utilisateur",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée",
     *          500="Erreur interne"
     *     }
     * )
     * @Delete("/user/{user}")
     *
     * @ParamConverter(
     *     "user", class="ApiBundle:UsrUsers", options={"repository_method" = "findOneByBase64EncodedLogin"})
     *
     * @param UsrUsers $user
     *
     * @return DocapostJsonResponse
     */
    public function deleteUser(UsrUsers $user)
    {
        $user->setUsrActif(false);

        $entityManager =  $this->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->getUserAction($user);
    }

    /**
     * @ApiDoc(
     *     section="Utilisateur",
     *     description="Liste autocompletion de recherche",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing"
     *     }
     * )
     * @Post("/user/autocomplete/search")
     */
    public function postAutocompleteUserSearch()
    {
        $managerAutocomplete = $this->get('api.manager.autocomplete');
        $datas = $this->getContentParameters(
            false,
            true,
            []
        );
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Liste de tous les champs et contextes recherchés
        $searchable = $managerAutocomplete->getSearchableFieldsUser();
        // Contrôle du PAC Client
        if (!$managerAutocomplete->checkPac($datas)) {
            $this->addResponseMessage(RcsRefCodeSociete::ERR_PAC_DOES_NOT_EXIST);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        $context = $managerAutocomplete->buildConcatContextFields('usr', $searchable);

        // Renvoi la recherche par autocompletion
        return $this->export(
            $managerAutocomplete->autocompleteUserSearch($datas, $searchable, $context)
        );
    }

    /**
     * Retourne les valeurs d'un formulaire dans un JSON
     *
     * @param FormInterface $form
     * @return DocapostJsonResponse
     */
    private function renderForm(FormInterface $form)
    {
        $output = array();

        $view = $form->createView();
        foreach ($view as $name => $field) {
            /* @var $field FormView */
            $output[$name] = $field->vars['value'];
        }

        $user = $form->getViewData();
        $output['usrActif'] = (bool) $user->isUsrActif();
        $output['usrId'] = base64_encode($user->getUsrLogin());
        $output['usrProfils'] = $this->get('api.manager.user')->loadProfiles($user);
        return $this->export($output);
    }
}
