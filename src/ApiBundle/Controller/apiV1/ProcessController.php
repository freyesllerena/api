<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\Controller\DocapostController;
use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\DicDictionnaire;
use ApiBundle\Entity\ProProcessus;
use ApiBundle\Form\ProProcessusType;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProcessController extends DocapostController
{
    /**
     * @ApiDoc(
     *     section="Processus",
     *     description="Crée un processus",
     *     input="ApiBundle\Form\ProProcessusType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/process")
     *
     * @return DocapostJsonResponse
     */
    public function postProcess()
    {
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(true, true, [
            'proGroupe' => null,
            'proLibelle' => null,
            'ptyType' => [null]
        ]);
        if ($this->hasResponseMessage()) {
            return $this->processMessageResponse();
        }
        // Création de la complétude
        return $this->handleProcessForm($datas);
    }

    /**
     * @ApiDoc(
     *     section="Processus",
     *     description="Affiche les thématiques et processus métiers, ainsi que ceux de l'utilisateur",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer"
     *     }
     * )
     * @Get("/process/list")
     *
     * @return DocapostJsonResponse
     */
    public function getProcessList()
    {
        $parameters = $this->getRequestParameters();
        // Validation des paramètres
        if (!$this->validateWSParameters(
            $parameters,
            ['device' => implode('|', DicDictionnaire::$availableDevices)]
        )
        ) {
            return $this->processMessageResponse();
        }
        return $this->export(
            $this->get('api.manager.process')
                ->retrieveCustomProcessTree($parameters['device'])
        );
    }

    /**
     * @ApiDoc(
     *     section="Processus",
     *     description="Met à jour un processus",
     *     input="ApiBundle\Form\ProProcessusType",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Put("/process")
     *
     * @return DocapostJsonResponse
     */
    public function putProcess()
    {
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(true, true, [
            'proId' => 'int',
            'proGroupe' => null,
            'proLibelle' => null,
            'ptyType' => [null]
        ]);
        if ($this->hasResponseMessage()) {
            return $this->processMessageResponse();
        }
        // Création de la complétude
        return $this->handleProcessForm($datas, 'PUT');
    }

    /**
     * @ApiDoc(
     *     section="Processus",
     *     description="Supprime un processus",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     * @Delete("/process")
     *
     * @return DocapostJsonResponse
     */
    public function deleteProcess()
    {
        $processManager = $this->get('api.manager.process');
        // Récupération et contrôle des paramètres
        $datas = $this->getContentParameters(false, true, [
            'proId' => 'int',
        ]);
        if ($this->hasResponseMessage()) {
            return $this->processMessageResponse();
        }
        // Lectude du processus
        $process = $processManager->getProcessById($datas->proId);
        // Validation du processus
        if ($error = $processManager->controlProcess($process)) {
            return $this->processMessageResponse($error[0], $error[1]);
        }
        // Suppression du processus
        $processManager->removeProcess($process);
        return $this->export(null);
    }

    /**
     * Valide le formulaire et ajoute/met à jour un processus
     *
     * @param array $datas
     * @param string $method
     *
     * @return DocapostJsonResponse
     */
    private function handleProcessForm(array $datas = array(), $method = 'POST')
    {
        $processManager = $this->get('api.manager.process');
        // Lectude du processus
        if ($method == 'PUT') {
            $process = $processManager->getProcessById($datas['proId']);
            // Validation du processus
            if ($error = $processManager->controlProcess($process)) {
                return $this->processMessageResponse($error[0], $error[1]);
            }
        } else {
            // Instanciation d'un nouveau processus
            $process = $processManager->instantiateProcess();
        }
        // Validation du formulaire
        $processForm = $this->createForm(ProProcessusType::class, $process);
        if (!$processForm->submit($datas, false)->isValid()) {
            // Erreur(s) pendant la validation
            $this->translateErrorsFormIntoResponseMessages($processForm);
            return $this->processMessageResponse();
        }
        // Contrôle que le processus n'existe pas déjà pour le propriétaire
        if ($processManager->hasAnotherProcessLabel($process)) {
            return $this->processMessageResponse(
                ProProcessus::ERR_OWNER_PROCESSUS_LABEL_EXISTS,
                $process->getProLibelle()
            );
        }
        if ($method == 'PUT') {
            // Mise à jour d'un processus
            $process = $processManager->updateProcess($process, $datas['ptyType']);
        } else {
            // Création d'un processus
            $process = $processManager->createProcess($process, $datas['ptyType']);
        }
        return $this->export($this->convertIntoArray($process, ['proUser', 'proCreatedAt', 'proUpdatedAt']));
    }

    /**
     * Ajoute un message et peut éventuellement renvoyer un messageJsonResponse
     *
     * @param null|string $code L'erreur rencontrée
     * @param null|string $value La valeur à envoyer dans le message
     *
     * @return DocapostJsonResponse
     */
    private function processMessageResponse($code = null, $value = null)
    {
        switch ($code) {
            case ProProcessus::ERR_DOES_NOT_EXIST:
                $this->addResponseMessage(ProProcessus::ERR_DOES_NOT_EXIST);
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
            case ProProcessus::ERR_OWNER_PROCESSUS_LABEL_EXISTS:
                $this->addResponseMessage(
                    ProProcessus::ERR_OWNER_PROCESSUS_LABEL_EXISTS,
                    [DocapostController::MSG_CODE_LABEL => $value]
                );
                break;
            case ProProcessus::ERR_NOT_OWNER:
                $this->addResponseMessage(
                    ProProcessus::ERR_NOT_OWNER,
                    [DocapostController::MSG_CODE_LABEL => $value]
                );
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
        }
        return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
    }
}
