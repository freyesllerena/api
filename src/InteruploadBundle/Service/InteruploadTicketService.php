<?php

namespace InteruploadBundle\Service;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ApiBundle\Entity\IucInteruploadCfg;

/**
 * Class InteruploadTicketService
 * @package InteruploadBundle\Service
 */
class InteruploadTicketService
{
    const FAILED_TO_LOAD_SOAP_WSDL = 'SOAP-ERROR: Echec de chargement du WSDL';
    const UNABLE_TO_RETRIEVE_THE_TICKET = 'Impossible de récupèrer le ticket !';
    const CAN_NOT_BE_INSERTED_INTO_DATABASE = 'Insertion impossible en base de données';

    protected $responseInterupload;

    /**
     * InteruploadClientService constructor.
     *
     * InteruploadClientService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connectInteruploadProductionSoap();
    }

    /**
     * Appel du Webservice SoapClient pour Interupload Production pour récupérer le ticket
     * Sans certificat.
     *
     * @return \SoapClient
     *
     * @throws BadRequestHttpException
     */
    private function connectInteruploadProductionSoap()
    {
        // Définition des options à passer pour l'appel du Webservice 
        $options = [
            'soap_client' => SOAP_1_2,
            'encoding' => $this->container->getParameter('interupload.encoding'),
            'cache_wsdl' => $this->container->getParameter('interupload.cache_wsdl'),
            'style' => SOAP_DOCUMENT,
            'use' => SOAP_LITERAL
        ];
        // Tentative de connection au serveur Interupload Production pour récupérer un ticket
        try {
            $iupProduction = new  \SoapClient(
                $this->container
                    ->getParameter('interupload.url_wsdl'),
                $options
            );
        } catch (\Exception $e) {
            $this->container->get('logger')->debug(self::FAILED_TO_LOAD_SOAP_WSDL.' : '.$e->getMessage());
            throw new BadRequestHttpException(self::FAILED_TO_LOAD_SOAP_WSDL);
        }
        try {
            $this->parametersInteruploadTicket($iupProduction);
        } catch (Exception $e) {
            throw new BadRequestHttpException(self::UNABLE_TO_RETRIEVE_THE_TICKET, $e);
        }
    }

    /**
     * Paramètres du ticket Interupload
     * @param $iupProduction
     */
    public function parametersInteruploadTicket($iupProduction)
    {
        $iucCodeClient = null;
        $iucCodeAppli = null;
        $iucVersion = null;
        $bundleName = $this->container->getParameter('interupload.bundle_name_entities');
        $tableConfigName = $this->container->getParameter('interupload.table_configuration_name');
        // Code Pac courant
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $codePac = $entityManager->getRepository('ApiBundle:ConConfig')->offsetGet('pac');
        /* @var $interuploadCfg IucInteruploadCfg */
        $interuploadCfg = $entityManager->getRepository($bundleName.':'.$tableConfigName)
            ->findOneByIucId(1);
        if (isset($interuploadCfg)) {
            $iucCodeClient = $interuploadCfg->getIucCodeclient();
            $iucCodeAppli = $interuploadCfg->getIucCodeappli();
            $iucVersion = $interuploadCfg->getIucVersionapplet();
        }

        $data = [];
        $params = (object)[
            "pac" => $codePac,
            "codeClient" => $iucCodeClient,
            "codeAppli" => $iucCodeAppli,
            "param" => $this->container->getParameter('interupload.iup_url_ticket'),
            "version" => $iucVersion
        ];
        /* @var $iupProduction object */
        try {
            // Préparation des paramètres collectés pour votre Application
            $data['getTicket'] =  $iupProduction->getTicket($params);
        } catch (\Exception $e) {
            $this->interuploadLogger(self::UNABLE_TO_RETRIEVE_THE_TICKET.' : '.$e->getMessage());
            throw new BadRequestHttpException(self::UNABLE_TO_RETRIEVE_THE_TICKET);
        }

        $this->responseInterupload = $data;
    }

    /**
     * @return mixed
     */
    public function getResponseInterupload()
    {
        return $this->responseInterupload;
    }

    /**
     * @param $trace
     */
    private function interuploadLogger($trace)
    {
        if ($this->container->getParameter('interupload.debug')===true) {
            $this->container->get('logger')->debug($trace);
        }
    }
}