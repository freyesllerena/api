<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\IupInterupload;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InteruploadManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var string
     */
    private $tableTicketName;

    /**
     * InteruploadManager constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->container = $container;
        $this->bundle = $this->container->getParameter('interupload.bundle_name_entities');
        $this->tableTicketName = $this->container->getParameter('interupload.table_ticket_name');
    }

    /**
     * Recherche du ticket dans IupInterupload
     * @param $objParams
     * @return mixed
     */
    public function searchTicket($objParams)
    {
        return $this->entityManager->getRepository($this->bundle.':'.$this->tableTicketName)
            ->findIupInteruploadByParams($objParams);

    }

    /**
     * @param $objTicket
     * @return mixed
     */

    /**
     * Mise à jour du statut et MetadataArchivage du Ticket
     * @param $objTicket
     */
    public function updateIupInterupload($objTicket)
    {
        /* @var $objTicket IupInterupload */
        $objTicket->setIupDateArchivage(new \DateTime());
        $objTicket->setIupUpdatedAt(new \DateTime());
        $this->entityManager->persist($objTicket);
        $this->entityManager->flush();

    }
}