<?php

namespace InteruploadBundle\Service;

use ApiBundle\Entity\IucInteruploadCfg;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ApiBundle\Controller\DocapostController;

/**
 * Class InteruploadParamsTicketService
 * @package InteruploadBundle\Service
 */
class InteruploadParamsTicketService
{
    const UNKNOWN_CLIENT_CODE = 'code client inconnu';

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
        $this->listParamsTicket();
    }

    /**
     * Liste de paramètres pour mettre à jour le ticket
     */
    public function listParamsTicket()
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $bundleName = $this->container->getParameter('interupload.bundle_name_entities');
        $tableConfigName = $this->container->getParameter('interupload.table_configuration_name');
        /* @var $interuploadCfg IucInteruploadCfg */
        $interuploadCfg = $entityManager->getRepository($bundleName.':'.$tableConfigName)
            ->findOneByIucId(1);
        $docapostController = new DocapostController();
        $listParams = $docapostController->convertIntoArray($interuploadCfg);
        // S'il n'y a pas de configuration pour le ticket
        if (!$interuploadCfg) {
            $this->interuploadLogger(self::UNKNOWN_CLIENT_CODE);
            throw new BadRequestHttpException(self::UNKNOWN_CLIENT_CODE);
        }
        $xmlIucConfig = $listParams['iucConfig'];

        if (is_string($xmlIucConfig)) {
            $xmlParams = simplexml_load_string($listParams['iucConfig']);
            $cert = $xmlParams->document->depot->certificat;
            $traitements = $xmlParams->traitements->traitement;
            $xmlJson = json_encode($xmlParams);
            $xmlArray = json_decode($xmlJson, true);
            $xmlArray['document']['depot']['certificat'] = (string)$cert;
            $xmlArray['traitements']['traitement']['all_types'] = (string)$traitements;
            $attributes = [];
            foreach ($traitements as $valueAttrib) {
                $attributes = (array)$valueAttrib->attributes();
            }
            $xmlArray['traitements']['traitement']['attributes'] = $attributes['@attributes'];
            unset($xmlArray['traitements']['traitement']['@attributes']);
            $listParams['iucConfig_details'] = $xmlArray;

            $this->responseInterupload = $listParams;
        }
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