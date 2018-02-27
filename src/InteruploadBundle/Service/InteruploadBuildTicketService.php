<?php

namespace InteruploadBundle\Service;

use ApiBundle\Entity\IupInterupload;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class InteruploadBuildTicketService
 * @package InteruploadBundle\Service
 */
class InteruploadBuildTicketService
{
    /**
     * InteruploadClientService constructor.
     *
     * InteruploadClientService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Rechercher un Ticket
     * @param $ticket
     * @return mixed
     */
    public function searchTicket($ticket)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $bundle = $this->container->getParameter('interupload.bundle_name_entities');
        $tableTicketName = $this->container->getParameter('interupload.table_ticket_name');
        return $entityManager->getRepository($bundle.':'.$tableTicketName)
            ->findOneByIupTicket($ticket);
    }

    /**
     * Ajout d'un nouveau ticket
     * @param $data
     */
    public function createTicket($data)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $ticket = new IupInterupload();
        $ticket->setIupTicket($data['getTicket']->ticket);
        $ticket->setIupChallenge('');
        $ticket->setIupStatut($data['getTicket']->statut);
        $ticket->setIupDateProduction(null);
        $ticket->setIupDateArchivage(null);
        $ticket->setIupConfig($data['params']['iucConfig']);
        $ticket->setIupMetadataCreation('');
        $ticket->setIupMetadataProduction('');
        $ticket->setIupMetadataArchivage('');
        $ticket->setIupCreatedAt(new \Datetime());
        $ticket->setIupUpdatedAt(new \Datetime());
        $entityManager->persist($ticket);
        $entityManager->flush();
    }

    /**
     * Liste de resultats après modification du ticket
     * @param $rowTicket
     * @return mixed
     */
    public function prepareResultTicket($rowTicket)
    {
        /* @var $rowTicket IupInterupload */
        $result['iupId'] = $rowTicket->getIupId();
        $result['iupTicket'] = $rowTicket->getIupTicket();
        $result['iupChallenge'] = $rowTicket->getIupChallenge();
        $result['iupStatut'] = $rowTicket->getIupStatut();
        $result['iupDateProduction'] = ($rowTicket->getIupDateProduction())?
            $rowTicket->getIupDateProduction()->getTimestamp() : null;
        $result['iupDateArchivage'] = ($rowTicket->getIupDateArchivage())?
            $rowTicket->getIupDateArchivage()->getTimestamp() : null;
        $result['iupConfig'] = $rowTicket->getIupConfig();
        $result['iupMetadataCreation'] = $rowTicket->getIupMetadataCreation();
        $result['iupMetadataProduction'] = $rowTicket->getIupMetadataProduction();
        $result['iupMetadataArchivage'] = $rowTicket->getIupMetadataArchivage();
        if ($this->container->get('kernel')->getEnvironment() != 'test') {
            $result['iupCreatedAt'] = $rowTicket->getIupCreatedAt();
            $result['iupUpdatedAt'] = $rowTicket->getIupUpdatedAt();
        }
        return $result;
    }

    /**
     * Mise à jour de metadata création du ticket
     * @param $objTicket
     * @return null
     */
    public function updateTicketInIupInterupload($objTicket)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $iupBuildTicket = $this->container->get('interupload.build_ticket');
        $objTicket = $iupBuildTicket->searchTicket($objTicket->getTicket->ticket);
        /* @var $objTicket IupInterupload */
        $documentManager = $this->container->get('api.manager.document');
        $metadataCreation = $documentManager->buildMedataCreation($objTicket);
        $objTicket->setIupMetadataCreation($metadataCreation);
        $entityManager->persist($objTicket);
        $entityManager->flush();
        return null;
    }
}
