<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\BaseFolderController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\IfpIndexfichePaperless;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class BasketController extends BaseFolderController
{
    /**
     * @ApiDoc(
     *     section="Panier",
     *     description="Renvoit les document(s) dans un panier",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     *
     * @Get("/basket/documents")
     *
     * @return DocapostJsonResponse
     */
    public function getBasketDocument()
    {

        $userToken = $this->get('security.token_storage')->getToken();
        if ($userToken instanceof AnonymousToken) {
            return $this->folderMessageResponse(IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED);
        }

        $documentManager = $this->get('api.manager.document');

        $params = (object) array(
            'node' => (object) array(
                'type' => DocumentController::TYPE_BASKET,
            ),
            'fields' => [],
            'start'  => 0,
            'limit'  => null,
            'sorts'  => [],
        );

        return $this->export(
            $documentManager->retrieveListDocumentWS($params)
        );
    }


    /**
     * @ApiDoc(
     *     section="Panier",
     *     description="Ajoute un ou plusieurs document(s) dans un panier",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Post("/basket/documents")
     *
     * @return DocapostJsonResponse
     */
    public function postBasketDocument()
    {
        $userToken = $this->get('security.token_storage')->getToken();
        if ($userToken instanceof AnonymousToken) {
            return $this->folderMessageResponse(IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED);
        }

        $folderManager = $this->get('api.manager.folder');

        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['documentIds' => ['int']]);
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }

        // Récupération du panier
        $basket = $this->get('doctrine')->getManager()
            ->getRepository('ApiBundle:BasBasket')
            ->getOrCreateBasketForUser($userToken->getUser());

        // Ajout des documents dans le dossier
        if ($errorDocumentIds = $folderManager->addFolderDocuments($basket, $datas->documentIds)) {
            $message = '';
            foreach ($errorDocumentIds as $documentId) {
                // Ajout d'un message d'erreur pour chaque document
                $message = $this->folderMessageResponse(IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED, $documentId);
            }
        }

        // Met à jour le nombre de documents dans un dossier
        $folderManager->setFolderNbDocuments($basket);
        if (isset($message)) {
            return $message;
        }
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Panier",
     *     description="Supprime un ou plusieurs document(s) d'un panier",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Delete("/basket/documents")
     *
     * @return DocapostJsonResponse
     */
    public function deleteBasketDocument()
    {
        $userToken = $this->get('security.token_storage')->getToken();
        if ($userToken instanceof AnonymousToken) {
            return $this->folderMessageResponse(IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED);
        }

        $folderManager = $this->get('api.manager.folder');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['documentIds' => ['int']]);
        if ($this->hasResponseMessage()) {
            return $this->folderMessageResponse();
        }
        // Récupération du panier
        $basket = $this->get('doctrine')->getManager()
            ->getRepository('ApiBundle:BasBasket')
            ->getOrCreateBasketForUser($userToken->getUser());

        // Suppression du ou des document(s) du dossier
        $folderManager->deleteFolderDocuments($basket, $datas->documentIds);
        // Met à jour le nombre de documents dans un dossier
        $folderManager->setFolderNbDocuments($basket);
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Panier",
     *     description="Compte les documents dans le panier",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Get("/basket/countDocuments")
     *
     * @return DocapostJsonResponse
     */
    public function getCountBasketDocument()
    {
        $userToken = $this->get('security.token_storage')->getToken();
        if ($userToken instanceof AnonymousToken) {
            return $this->folderMessageResponse(IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED);
        }

        $folderManager = $this->get('api.manager.folder');

        // Récupération du panier
        $basket = $this->get('doctrine')->getManager()
            ->getRepository('ApiBundle:BasBasket')
            ->getOrCreateBasketForUser($userToken->getUser());

        new DocapostJsonResponse();
        return array(
            'nbrDocs' => $folderManager->countFolderDocuments($basket),
            'lastAccess' => null,
        );
    }
}
