<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\ComCompletude;
use ApiBundle\Enum\EnumLabelEmployeeFilterType;
use ApiBundle\Form\ComCompletudeType;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Validator\Constraints\Collection;

class CompletudeController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Complétude",
     *     description="Crée une complétude",
     *     input="ApiBundle\Form\ComCompletudeType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/completude")
     *
     * @return DocapostJsonResponse
     */
    public function postCompletude()
    {
        $parametersToCheck = [
            'comLibelle' => null,
            'comPrivee' => null,
            'comAuto' => null,
            'comEmail' => [null],
            'comPeriode' => null,
            'comAvecDocuments' => null,
            'comPopulation' => null,
            'ctyType' => [null]
        ];
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(true, true, $parametersToCheck);
        if ($this->hasResponseMessage()) {
            return $this->completudeMessageResponse();
        }
        // Création de la complétude
        return $this->handleCompletudeForm($datas);
    }

    /**
     * @ApiDoc(
     *     section="Complétude",
     *     description="Met à jour une complétude",
     *     input="ApiBundle\Form\ComCompletudeType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Put("/completude")
     *
     * @return DocapostJsonResponse
     */
    public function putCompletude()
    {
        $parametersToCheck = [
            'comIdCompletude' => 'int',
            'comLibelle' => null,
            'comPrivee' => null,
            'comAuto' => null,
            'comEmail' => [null],
            'comPeriode' => null,
            'comAvecDocuments' => null,
            'comPopulation' => null,
            'ctyType' => [null]
        ];
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(true, true, $parametersToCheck);
        if ($this->hasResponseMessage()) {
            return $this->completudeMessageResponse();
        }
        // Mise à jour de la complétude
        return $this->handleCompletudeForm($datas, 'PUT');
    }

    /**
     * @ApiDoc(
     *     section="Complétude",
     *     description="Supprime une complétude",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Delete("/completude")
     *
     * @return DocapostJsonResponse
     */
    public function deleteCompletude()
    {
        $completudeManager = $this->get('api.manager.completude');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, ['comIdCompletude' => 'int']);
        if ($this->hasResponseMessage()) {
            return $this->completudeMessageResponse();
        }
        // Lecture de la complétude
        $completude = $completudeManager->getCompletudeById($datas->comIdCompletude);
        // Validation de la complétude
        if ($error = $completudeManager->validateCompletudeAndCheckIsOwner($completude)) {
            return $this->completudeMessageResponse($error[0], $error[1]);
        }
        // Suppression de la complétude
        $completudeManager->removeCompletude($completude);
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Complétude",
     *     description="Affiche les complétudes",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/completude/list")
     *
     * @return DocapostJsonResponse
     */
    public function getCompletudesList()
    {
        return $this->export(
            $this->get('api.manager.completude')->retrieveCompletudeList()
        );
    }

    /**
     * @ApiDoc(
     *     section="Complétude",
     *     description="Affiche le résultat d'une complétude",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          403="Accès non autorisé"
     *     }
     * )
     * @Post("/completude/search")
     *
     * @return DocapostJsonResponse
     */
    public function postCompletudeSearch()
    {
        $completudeManager = $this->get('api.manager.completude');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(
            false,
            true,
            [
                'node' => [
                    'type' => DocumentController::TYPE_CMP,
                    'population' => '^' . EnumLabelEmployeeFilterType::ALL_POP
                        . '|' . EnumLabelEmployeeFilterType::PRESENT_POP
                        . '|' . EnumLabelEmployeeFilterType::OUT_POP . '$',
                    'withoutDoc' => 'bool',
                    'value' => 'int'
                ],
                'start' => 'int',
                'limit' => 'int'
            ]
        );
        if ($this->hasResponseMessage()) {
            return $this->completudeMessageResponse();
        }
        // Lecture de la complétude
        $completude = $completudeManager->getCompletudeById($datas->node->value);
        // Validation de la complétude
        if ($error = $completudeManager->validateCompletudeAndCheckIsOwner($completude)) {
            return $this->completudeMessageResponse($error[0], $error[1]);
        }
        if (!isset($datas->fields)) {
            $datas->fields = (object)[];
        }
        if (!isset($datas->sorts)) {
            $datas->sorts = (object)[];
        }
        // Renvoie la liste des documents de la complétude
        return $this->export(
            $completudeManager->executeCompletude($datas)
        );
    }

    /**
     * Valide le formulaire et ajoute/met à jour une complétude
     *
     * @param array $datas
     * @param string $method
     *
     * @return DocapostJsonResponse
     */
    public function handleCompletudeForm(array $datas = array(), $method = 'POST')
    {
        $completudeManager = $this->get('api.manager.completude');
        // Lecture de la complétude si mise à jour
        if ($method == 'PUT') {
            $completude = $completudeManager->getCompletudeById($datas['comIdCompletude']);
            // Validation de la complétude
            if ($error = $completudeManager->validateCompletudeAndCheckIsOwner($completude)) {
                return $this->completudeMessageResponse($error[0], $error[1]);
            }
        } else {
            // Instanciation d'une nouvelle complétude
            $completude = $completudeManager->instantiateCompletude();
        }
        // Validation du formulaire
        $completudeForm = $this->createForm(ComCompletudeType::class, $completude);
        if (!$completudeForm->submit($datas, false)->isValid()) {
            // Erreur(s) pendant la validation
            $this->translateErrorsFormIntoResponseMessages($completudeForm);
            return $this->completudeMessageResponse();
        }
        // Contrôle que la complétude n'existe pas déjà pour le propriétaire
        if ($completudeManager->hasAnotherCompletudeLabel($completude)) {
            return $this->completudeMessageResponse(
                ComCompletude::ERR_OWNER_COMPLETUDE_LABEL_EXISTS,
                $completude->getComLibelle()
            );
        }
        if ($method == 'PUT') {
            // Mise à jour de la complétude
            $completude = $completudeManager->updateCompletude($completude, $datas['ctyType']);
        } else {
            // Création de la complétude
            $completude = $completudeManager->createCompletude($completude, $datas['ctyType']);
        }
        $completudeResult = $this->convertIntoArray($completude, ['comUser', 'comCreatedAt', 'comUpdatedAt']);
        $completudeResult['comEmail'] = explode(';', $completudeResult['comEmail']);
        return $this->export($completudeResult);
    }

    /**
     * Ajoute un message et peut éventuellement renvoyer un messageJsonResponse
     *
     * @param null|string $code L'erreur rencontrée
     * @param null|string $value La valeur à envoyer dans le message
     *
     * @return DocapostJsonResponse
     */
    private function completudeMessageResponse($code = null, $value = null)
    {
        switch ($code) {
            case ComCompletude::ERR_DOES_NOT_EXIST:
                $this->addResponseMessage(ComCompletude::ERR_DOES_NOT_EXIST);
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
            case ComCompletude::ERR_NOT_OWNER:
                $this->addResponseMessage(
                    ComCompletude::ERR_NOT_OWNER,
                    [DocapostController::MSG_CODE_LABEL => $value]
                );
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
            case ComCompletude::ERR_OWNER_COMPLETUDE_LABEL_EXISTS:
                $this->addResponseMessage(
                    ComCompletude::ERR_OWNER_COMPLETUDE_LABEL_EXISTS,
                    [DocapostController::MSG_CODE_LABEL => $value]
                );
        }
        return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
    }
}
