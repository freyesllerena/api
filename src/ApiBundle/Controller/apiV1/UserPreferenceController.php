<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\BaseFolderController;
use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\DicDictionnaire;
use ApiBundle\Entity\ProProfil;
use ApiBundle\Entity\PusProfilUser;
use ApiBundle\Entity\UprUserPreferences;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Form\UprUserPreferencesType;
use ApiBundle\Form\UsrUsersType;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Web Services Préférences Utilisateurs
 *
 * @package ApiBundle\Controller\apiV1
 */
class UserPreferenceController extends BaseFolderController
{
    /**
     * @ApiDoc(
     *     section="Préférences utilisateurs",
     *     description="Modifie une préférence utilisateur",
     *     input="ApiBundle\Form\UprUserPreferencesType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée",
     *          500="Erreur interne"
     *     }
     * )
     * @Put("/userPreference")
     *
     * @return DocapostJsonResponse
     */
    public function putUserPreference()
    {
        $parameters = $this->getContentParameters(true, true);

        if (!$this->validateWSParameters(
            $parameters,
            [
                'uprDevice' => implode('|', DicDictionnaire::$availableDevices),
                'uprType' => null,
                'uprData' => null,
            ]
        )
        ) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $preference = $entityManager->getRepository('ApiBundle:UprUserPreferences')->findOneBy(array(
            'uprUser' => $user,
            'uprDevice' => $parameters['uprDevice'],
            'uprType' => $parameters['uprType'],
        ));
        if (null == $preference) {
            $preference = new UprUserPreferences();
            $preference->setUprUser($user);
        }

        $form = $this->createForm(UprUserPreferencesType::class, $preference);
        $form->submit($parameters, true);
        if ($form->isValid()) {
            $entityManager =  $this->get('doctrine')->getManager();
            $entityManager->persist($preference);
            $entityManager->flush();
            return $this->export(array(
                'uprUser' => $preference->getUprUser()->getUsrLogin(),
                'uprDevice' => $preference->getUprDevice(),
                'uprType' => $preference->getUprType(),
                'uprData' => $preference->getUprData(),
            ));
        }

        $this->translateErrorsFormIntoResponseMessages($form);
        return $this->folderMessageResponse();
    }
}
