<?php

namespace InteruploadBundle\Controller\apiV1;

use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\IupInterupload;
use ApiBundle\Manager\InteruploadManager;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Get;
use ApiBundle\Controller\DocapostController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class TicketController
 * @package InteruploadBundle\Controller\apiV1
 */
class TicketController extends DocapostController
{
    const WARN_NOT_FOUND_TICKET_CONFIG_VAR01 = 'notFoundTicketConfig';
    const WARN_NOT_FOUND_TICKET = 'notFoundTicket';
    const ERR_NOT_TICKET = 'notTicket';
    const ERR_TICKET_NOT_FOUND = 'ticketNotFound';
    const INFO_IUP_SUCCESS_ENTRY_TICKET_STATUS = 'successEntryTicketStatus';
    const UNKNOWN_CLIENT_CODE = 'codeClientInconnu';
    const INFO_ALREADY_DONE = 'alreadyDone';

    /**
     * @ApiDoc(
     *     section="Interupload",
     *     description="Ticket et configuration",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     * @Get("/ticket/{ticketId}/config", requirements={"ticketId" = "\w+"})
     */
    public function getTicketConfigAction()
    {
        $request = $this->container->get('request');
        // Paramétres du fichier uploadé
        if (!$idTicket = ($request->get('ticketId'))? $request->get('ticketId') : null) {
            $this->addResponseMessage($this::ERR_NOT_TICKET);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 400);
        }
        // Recherche du ticket
        /* @var $objTicket IupInterupload */
        $objTicket = $this->findTicket($idTicket);
        if (is_null($objTicket)) {
            $this->addResponseMessage($this::ERR_TICKET_NOT_FOUND, [$idTicket], 'iupTicket');
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 400);
        }
        $data = [
            'ticket' => $idTicket,
            'iupConfig' => $objTicket->getIupConfig(),
            'iupMetadataCreation' => $objTicket->getIupMetadataCreation()
        ];
        return new JsonResponse(array('data' => $data));
    }

    /**
     * Recherche du ticket pour interupload
     * @param $ticketId
     * @return null
     */
    private function findTicket($ticketId)
    {
        $iupBuildTicket = $this->get('interupload.build_ticket');
        $objTicket = $iupBuildTicket->searchTicket($ticketId);
        if (is_null($objTicket)) {
            return null;
        }
        return $objTicket;
    }

    /**
     * @ApiDoc(
     *     section="Interupload",
     *     description="Mise-à-jour du status du ticket",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     * @Put("/ticket/status")
     */
    public function setTicketStatusAction()
    {
        // Code Pac courant
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $codePac = $entityManager->getRepository('ApiBundle:ConConfig')->offsetGet('pac');
        $request = $this->container->get('request');
        // Récupération du contenu JSON
        if (!$content = $request->getContent()) {
            $this->addResponseMessage($this::ERR_OBJECT_CONTENT_INCORRECT);
            return $this->messageJsonResponse($this::MSG_LEVEL_ERROR);
        }
        // Récuperation des paramètres
        $objParams = json_decode($content);
        // Vérifiaction du code Pac courant sur les paramètres reçus
        if (JSON_ERROR_NONE !== json_last_error() || ($objParams->pac !== $codePac)) {
            $this->addResponseMessage($this::ERR_OBJECT_CONTENT_INCORRECT);
            return $this->messageJsonResponse($this::MSG_LEVEL_ERROR);
        }
        // Si pas de ticket on retourne une erreur
        if (!isset($objParams->ticket) || is_null($objParams->ticket)) {
            $this->addResponseMessage(self::ERR_NOT_TICKET);
            return $this->messageJsonResponse($this::MSG_LEVEL_ERROR);
        }
        $status = (isset($objParams->statut) && !is_null($objParams->statut)) ? $objParams->statut : null;
        if (is_null($status)) {
            /* Todo : ajout d'une notification */
            $this->export([]); // Réponse NOK
        }
        $codeTicket = substr($status, 0, 2);
        $metadaProduction = (isset($objParams->metadataProduction) && !is_null($objParams->metadataProduction)) ?
            $objParams->metadataProduction : null;
        $interuploadManager = $this->container->get('api.manager.interupload');
        /* @var $rowTicket IupInterupload */
        $rowTicket = $interuploadManager->searchTicket($objParams);
        // Si le ticket n'existe pas dans iup_interupload (table) on retourne une erreur
        if (is_null($rowTicket)) {
            $this->addResponseMessage(self::ERR_TICKET_NOT_FOUND, [$objParams->ticket], 'iupTicket');
            return $this->messageJsonResponse($this::MSG_LEVEL_ERROR);
        }
        // On sort si le statut du ticket = OK_ARCHIVAGE
        if ($rowTicket->getIupStatut() === IupInterupload::STATUT_OK_ARCHIVAGE) {
            $this->addResponseMessage(self::INFO_ALREADY_DONE, [$objParams->ticket], 'iupTicket');
            return $this->messageJsonResponse($this::MSG_LEVEL_INFO, 200);
        }
        /* @var $rowTicket IupInterupload */
        $rowTicket->setIupStatut(IupInterupload::STATUT_OK_PRODUCTION);
        if ("ER" === $codeTicket) {
            /* Todo : ajout d'une notification */
            $rowTicket->setIupStatut($status);
            $rowTicket->setIupMetadataArchivage(IupInterupload::METADATA_PRODUCTION_NON);
            // Modification du champ statut et metadataArchivage du ticket dans IupInterupload
            $interuploadManager = $this->container->get('api.manager.interupload');
            $resultRowTicket = $this->updateStatutTicket($rowTicket, $interuploadManager, $metadaProduction);
            $this->addResponseMessage(
                self::INFO_IUP_SUCCESS_ENTRY_TICKET_STATUS,
                [$resultRowTicket->getIupStatut()],
                'iupStatut'
            );
            return $this->messageJsonResponse($this::MSG_LEVEL_INFO, 200);
        } else {
            $documentManager = $this->container->get('api.manager.document');
            $document = $documentManager->addIfpDocument($objParams);
            $rowTicket->setIupStatut(IupInterupload::STATUT_OK_ARCHIVAGE);
            $rowTicket->setIupMetadataArchivage(IupInterupload::METADATA_PRODUCTION_OK);
            $this->updateStatutTicket($rowTicket, $interuploadManager, $metadaProduction);
            /* @var $document IfpIndexfichePaperless */
            $this->addResponseMessage(
                self::INFO_IUP_SUCCESS_ENTRY_TICKET_STATUS,
                [$rowTicket->getIupStatut(), $document->getIfpId()],
                'iupStatut'
            );
            return $this->messageJsonResponse($this::MSG_LEVEL_INFO, 200);
        }
    }

    /**
     * Mise à jour du statut du ticket
     *
     * @param $rowTicket
     * @param $interuploadManager
     * @param $metadaProduction
     * @return IupInterupload
     */
    private function updateStatutTicket($rowTicket, $interuploadManager, $metadaProduction)
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        /* @var $rowTicket IupInterupload */
        /* @var $interuploadManager InteruploadManager */
        $interuploadManager->updateIupInterupload($rowTicket);
        if (!is_null($metadaProduction)) {
            $rowTicket->setIupMetadataProduction($metadaProduction);
            $rowTicket->setIupDateProduction(new \DateTime('now'));
        }
        $entityManager->persist($rowTicket);
        $entityManager->flush();
        return $rowTicket;
    }
}
