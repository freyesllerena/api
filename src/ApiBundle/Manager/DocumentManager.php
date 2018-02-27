<?php

namespace ApiBundle\Manager;

use ApiBundle\Controller\apiV1\DocumentController;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\IinIdxIndiv;
use ApiBundle\Entity\IupInterupload;
use ApiBundle\Entity\RapRapport;
use ApiBundle\Entity\TypType;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelStatusDocumentType;
use ApiBundle\Enum\EnumLabelTypeRapportType;
use ApiBundle\Form\RapRapportType;
use ApiBundle\Repository\BasBasketRepository;
use ApiBundle\Repository\ConConfigRepository;
use ApiBundle\Repository\IfpIndexfichePaperlessRepository;
use ApiBundle\Repository\LasLaserlikeRepository;
use ApiBundle\Security\UserToken;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use ApiBundle\Controller\DocapostController;
use ApiBundle\Entity\IniInteruploadIndexation;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use GuzzleHttp\Client;

class DocumentManager
{
    const ERR_FILENAME_REQUIRED = 'errDocumentManagerFilenameRequired';
    const ERR_IDUPLOAD_REQUIRED = 'errDocumentManagerIduploadRequired';
    const ERR_FILECONTENT_ERROR = 'errDocumentManagerFileContent';

    const IUP_RESPONSE_STATUS = 'success';
    const IUP_RESPONSE_TEST1 = '1';
    const IUP_RESPONSE_TEST2 = '2';

    const IUP_RECEPTION = 'reception';
    const IUP_CLOSURE = 'cloture';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var IfpIndexfichePaperlessRepository
     */
    private $documentRepository;

    /**
     * @var BasBasketRepository
     */
    private $basketRepository;

    /**
     * @var ConConfigRepository
     */
    private $configRepository;

    /**
     * @var LasLaserlikeRepository
     */
    private $laserlikeRepository;

    /**
     * @var ExportExcelManager
     */
    private $exportExcelManager;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var ContainerInterface
     */
    private $container;

    const SOURCE_DOC_SPOOL = 'SPOOL_ADP';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->documentRepository = $this->entityManager->getRepository('ApiBundle:IfpIndexfichePaperless');
        $this->basketRepository = $this->entityManager->getRepository('ApiBundle:BasBasket');
        $this->configRepository = $this->entityManager->getRepository('ApiBundle:ConConfig');
        $this->laserlikeRepository = $this->entityManager->getRepository('ApiBundle:LasLaserlike');
        $this->tokenStorage = $container->get('security.token_storage');
        $this->exportExcelManager = $container->get('api.manager.export_excel');
    }

    /**
     * Génère le script de visualisation d'un document
     *
     * @param IfpIndexfichePaperless $ifp
     * @param array $options
     * @return int
     */
    public function createVisScript(IfpIndexfichePaperless $ifp, array $options = array())
    {
        $options = $options + array(
                'numdoc' => 0,
            );

        $ikpConfiguration = $this->container->get('api.manager.interkeepass')->getConfiguration();

        $documents = explode("\|", $ifp->getIfpDocumentsassocies());
        $document = $documents[$options['numdoc']];

        $localisations = explode("|", $ifp->getIfpVdmLocalisation());
        $localisation = $localisations[$options['numdoc']];

        $documentParts = explode('/', $document);
        $localisationParts = explode('/', $localisation);

        $numeroPdf = substr($documentParts[0], 1);
        /* @var $plageRepository LasLaserlikeRepository */
        $plageRepository = $this->entityManager->getRepository('ApiBundle:LasLaserlike');
        $plage = $plageRepository->findOneByNumeroPdf($numeroPdf);
        $base = null;
        $certificat = null;
        switch ($localisationParts[1]) {
            case 'S':
                $base = '';
                $options = '';
                $certificat = '';
                break;
            case 'C':
                $base = $plage->getLasCfecBase();
                $options = $localisationParts[2].'-'.$localisationParts[3].'-'.$localisationParts[4];
                $certificat = $plage->getLasCfecCert();
                break;
            case 'E':
                $base = $plage->getLasCfecBase();
                $options = $localisationParts[3];
                $certificat = $plage->getLasCfecCert();
                break;
        }

        $script = 'VIS_GET_FILE;';
        $script .= $plage->getLasChemin().$numeroPdf.'.pdf;';
        $script .= $localisationParts[0].';'.$localisationParts[1].';;';
        $script .= $base.';'.$options.';'.$certificat.';';

        $script = str_replace('<racine_vis>', $this->configRepository['RACINE_VIS'], $script);

        $ikpConfiguration['cfec_coffre'] = $this->convertCertificatIfRequired($ikpConfiguration['cfec_coffre']);
        $ikpConfiguration['esc_coffre'] = $this->convertCertificatIfRequired($ikpConfiguration['esc_coffre']);

        $ikpMappings = array(
            'INTERKEEPASS WEBVIS PARAM1' => 'vis_root',
            'INTERKEEPASS CFECVIS PARAM1' => 'cfec_cert',
            'INTERKEEPASS CFECVIS PARAM2' => 'cfec_coffre',
            'INTERKEEPASS ESCLASERLIKE PARAM1' => 'esc_cert',
            'INTERKEEPASS ESCLASERLIKE PARAM2' => 'esc_coffre',
            'INTERKEEPASS ESCLASERLIKE PARAM3' => 'esc_salle',
        );
        foreach ($ikpMappings as $name => $value) {
            $script = str_replace($name, $ikpConfiguration[$value], $script);
        }

        return $script;
    }

    /**
     * Convertit un certificat en base 64
     *
     * @param $certificat
     * @return string
     */
    protected function convertCertificatIfRequired($certificat)
    {
        $pos1 = strpos($certificat, '-----BEGIN CERTIFICATE-----');
        $pos2 = strpos($certificat, '-----END CERTIFICATE-----');
        $pos3 = strpos($certificat, '-----BEGIN RSA PRIVATE KEY-----');
        $pos4 = strpos($certificat, '-----END RSA PRIVATE KEY-----');

        if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false) {
            return $certificat;
        } else {
            return base64_encode($certificat);
        }
    }

    /**
     * Récupère une liste de documents
     *
     * @param $params
     * @param $columns
     *
     * @return array|null
     */
    public function retrieveListDocumentWS($params, $columns = null)
    {
        $nodes = $this->processParamsDocument($params);
        if (isset($params->start)) {
            $nodes['start'] = $params->start;
        }
        if (isset($params->limit)) {
            $nodes['limit'] = $params->limit;
        }
        $nodes['sorts'] = $params->sorts;
        if ($columns) {
            array_walk($columns, function (&$column) {
                $column = 'd.' . $column;
            });
            $nodes['fields'] = $columns;
        } else {
            $nodes['fields'] = $this->container->get('api.manager.metadata')->collectionOfFieldsForSelect();
        }
        $nodes['count'] = (isset($params->totalCount) && $params->totalCount === true) ? true : false;

        return $this->buildDocumentWs((object)$nodes);
    }

    public function countListDocumentWS($params)
    {
        $nodes = $this->processParamsDocument($params);

        return $this->countDocumentWs((object)$nodes);
    }

    protected function processParamsDocument($params)
    {
        $code = null;
        $value = null;

        switch ($params->node->type) {
            case DocumentController::TYPE_PDC:
                if ($params->node->leaf) {
                    $value = $params->node->value;
                } else {
                    $value = $this->container->get('api.manager.type')
                        ->retrieveLevelDrawersList($params->node->value);
                }
                $code = 'CodeDocument';
                break;
            case DocumentController::TYPE_BASKET:
                $user = $this->tokenStorage->getToken()->getUser();
                $value = $this->basketRepository->getOrCreateBasketForUser($user);
                $code = 'Folder';
                break;
            case DocumentController::TYPE_FOLDER:
                $value = $params->node->value;
                $code = 'Folder';
                break;
            case DocumentController::TYPE_THE:
                $value = $params->node->value;
                $code = 'Processus';
                break;
            case DocumentController::TYPE_CMP:
                $value = $params->node->value;
                $code = 'Completude';
                break;
        }

        $nodes['node'] = (object)[
            'code' => $code,
            'value' => $value,
            'population' => isset($params->node->population) ? $params->node->population : null,
            'fields' => $params->fields
        ];

        return $nodes;
    }

    /**
     * Récupère une liste de documents
     *
     * @param $params
     * @return array
     */
    protected function buildDocumentWs($params)
    {
        $resultQuery = $this->documentRepository->listDocumentWsByParams(
            $params
        );
        $data = [];
        if ($resultQuery) {
            foreach ($resultQuery as $valueListRows) {
                $data['items'][] = $valueListRows;
            }
        }
        // Insertion du compteur s'il a été demandé
        if ($params->count) {
            $data = array_merge($data, $this->countDocumentWs($params));
        }
        return $data;
    }

    /**
     * Récupère le nombre total de documents correspondant aux paramètres
     * @param $params
     * @return mixed
     */
    protected function countDocumentWs($params)
    {
        $data['totalCount'] = $this->documentRepository->countDocumentWsByParams($params);
        return $data;
    }

    /**
     * Change le status freeze du document
     *
     * @param IfpIndexfichePaperless $document
     * @param $statutFreeze
     * @return IfpIndexfichePaperless
     */
    protected function setStatutFreezeDocument(IfpIndexfichePaperless $document, $statutFreeze)
    {
        $document->setIfpGeler($statutFreeze);
        $this->updateDocument($document);
    }

    /**
     * Gel un document
     * @param $document
     * @return IfpIndexfichePaperless
     */
    public function freezeDocument($document)
    {
        $documentObject = $this->getDocumentById($document['ifpId']);
        if ($documentObject->isIfpGeler()) {
            return IfpIndexfichePaperless::ERR_ALREADY_FROZEN;
        }
        if ($this->isIndividual($document)) {
            $result  = $this->freezeIndividualDocument($document);
        } else {
            $result  = $this->freezeCollectiveDocument($documentObject);
        }
        return $result;
    }

    /**
     * Dégel un document
     * @param IfpIndexfichePaperless $document
     * @return IfpIndexfichePaperless
     */
    public function unfreezeDocument(IfpIndexfichePaperless $document)
    {
        if (!$document->isIfpGeler()) {
            return IfpIndexfichePaperless::ERR_ALREADY_UNFROZEN;
        }
        $this->setStatutFreezeDocument($document, false);
        return $this->getDocumentById($document->getIfpId(), true);
    }

    /**
     * Récupère un objet IfpIndexfichePaperless si $withFields=false, un tableau sinon
     *
     * @param $ifpId
     * @param bool $withFields
     * @return mixed
     */
    public function getDocumentById($ifpId, $withFields = false)
    {
        if ($withFields) {
            return $this->documentRepository
                ->retrieveDataOneDocumentByIfpId(
                    $ifpId,
                    $this->container->get('api.manager.metadata')->collectionOfFieldsForSelect()
                );
        } else {
            return $this->documentRepository
                ->retrieveDataOneDocumentByIfpId($ifpId);
        }
    }

    /**
     * Gel des documents individuels
     * @param $document
     * @return array|null|string
     */
    protected function freezeIndividualDocument($document)
    {
        // Le matricule Rh du salarié est utilisé pour effectuer le gel des documents individuels
        $employeeId = $document['ifpIdNumMatriculeRh'] ?
            $document['ifpIdNumMatriculeRh'] : $document['ifpNumMatricule'];

        if ($employeeId) {
            $params = [
                'ifpIdNumMatriculeRh' => $document['ifpIdNumMatriculeRh'],
                'ifpNumMatricule' => $document['ifpNumMatricule'],
                'ifpCodeClient' => $document['ifpCodeClient'],
                'ifpGeler' => false
            ];

            return $this->processSetFreezeManyDocumentByParams($params);
        }

        return null;
    }

    /**
     * Gél des documents individuels
     * @param IfpIndexfichePaperless $document
     * @return array|null|string
     */
    protected function freezeCollectiveDocument($document)
    {
        $this->setStatutFreezeDocument($document, true);
        return ['items'=> [$this->getDocumentById($document->getIfpId(), true)]];
    }

    /**
     * Traitement du gel du document et des doc. du salarié
     *
     * @param $params
     *
     * @return array|string
     */
    protected function processSetFreezeManyDocumentByParams(array $params)
    {
        // Liste de documents non gelés
        $unfrozenRows = $this->documentRepository->getDocumentUnfreezeByParams($params);
        if (!count($unfrozenRows)) {
            return [];
        }

        // Liste des ids des documents à geler
        $idsUnfrozenRows = array_column($unfrozenRows, 'ifpId');

        // Geler tous les documents éligibles
        $this->documentRepository->freezeDocuments($idsUnfrozenRows);

        // Paramétres pour récupérer les objets à retourner
        $nodes['node'] = (object)[
            'code' => 'Id',
            'value' => $idsUnfrozenRows
        ];
        $nodes['fields'] = (object)[];
        $nodes['sorts'] = (object)[
            'ifpIdUniqueDocument' => (object)[
                'dir' => 'ASC'
            ]
        ];

        // Liste des documents gelés
        return $this->buildDocumentWs((object)$nodes);
    }

    /**
     * Vérifie que l'utilisateur est propriétaire de son document (L'a-t-il uploadé ?)
     *
     * @param IfpIndexfichePaperless $document Une instance de IfpIndexfichePaperless
     *
     * @return bool
     */
    protected function isUserOwner(IfpIndexfichePaperless $document)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $document->getIfpLogin() == $user->getUsrLogin();
    }

    /**
     * Recherche de matriculeRh sur ifp ou sur iin
     * @param $matriculeRh
     * @return IfpIndexfichePaperless|string
     */
    public function findMatriculeRhInIfpOrInIin($matriculeRh)
    {
        /* @var $ifpRepository IfpIndexfichePaperless */
        $ifpRepository = $this->findIndexfichePaperless(
            "IfpIndexfichePaperless",
            ["ifpIdNumMatriculeRh" => $matriculeRh]
        );
        $ifpMatriculeRh = $ifpRepository->getIfpIdNumMatriculeRh();
        if ($ifpMatriculeRh) {
            return $ifpMatriculeRh;
        } elseif (empty($ifpMatriculeRh)) {
            // On cherche sur iin MatriculeRh avec MatriculePaie ifp
            $ifpMatricule = $ifpRepository->getIfpNumMatricule();
            /* @var $employe IinIdxIndiv */
            $employe = $this->findIndexfichePaperless(
                "IinIdxIndiv",
                ["iinNumMatricule" => $ifpMatricule]
            );
            return $employe->getIinIdNumMatriculeRh();
        }
        return null;
    }

    /**
     * Contrôle que l'utilisateur peut modifier les métadonnées du document
     *
     * @param IfpIndexfichePaperless $document
     * @return bool
     */
    public function userCanUpdateDocument(IfpIndexfichePaperless $document)
    {
        // 1 - L'instance doit permettre la modification du document
        // 2 - L'utilisateur doit avoir le droit de modification
        // 3 - L'utilisateur doit être propriétaire du document
        // 4 - Le document ne doit pas être gelé
        if ($this->isUserOwner($document)
            && !$document->isIfpGeler()
        ) {
            return true;
        }
        return false;
    }

    /**
     * Met à jour le document
     *
     * @param IfpIndexfichePaperless $document
     */
    public function updateDocument(IfpIndexfichePaperless $document)
    {
        $this->entityManager->persist($document);
        $this->entityManager->flush();
    }

    /**
     * Contrôle que l'utilisateur peut supprimer un document
     *
     * @param IfpIndexfichePaperless $document
     *
     * @return bool
     */
    public function userCanDeleteDocument(IfpIndexfichePaperless $document)
    {
        // 1 - L'utilisateur doit avoir le droit de suppression
        // 2 - L'utilisateur qui supprime est celui qui a importé le document
        // 3 - Le document n'est pas issu d'un SPOOL
        // 4 - Le document n'est pas gelé
        $user = $this->tokenStorage->getToken()->getUser();
        if ($user->getUsrLogin() == $document->getIfpLogin()
            && $document->getIfpIdSourceDocument() != self::SOURCE_DOC_SPOOL
            && !$document->isIfpGeler()
        ) {
            return true;
        }
        return false;
    }

    /**
     * Supprime un document (soft delete)
     *
     * @param IfpIndexfichePaperless $document
     */
    public function deleteDocument(IfpIndexfichePaperless $document)
    {
        $document->setIfpStatusNum(EnumLabelStatusDocumentType::DELETE_STATUS_DOCUMENT);
        $this->updateDocument($document);
    }

    /**
     * Renvoi le type de document : individuel = I ou collectif = C
     * @param $document
     * @return mixed
     */
    private function getTypeDocument($document)
    {
        return $this->container->get('doctrine')->getManager()
            ->getRepository('ApiBundle:TypType')
            ->findTypIndividuelByTypeCode($document['ifpCodeDocument']);
    }

    /**
     * Renvoi true si le document est individuel
     * @param $document
     * @return bool
     */
    private function isIndividual($document)
    {
        return $this->getTypeDocument($document) == 'I';
    }

    /**
     * Creation du fichier document
     * @param $file
     * @return bool|string
     */
    public function createFileUploadDocument($file)
    {
        // Chemin du fichier
        $token = $this->container->get('security.token_storage');
        $usrLogin = $token->getToken()->getUser()->getUsrLogin();
        $pathFileUpload = $this->pathFile();

        // Parse le nom du fichier
        list($fileLogin, $fileTimestamp, $fileName) = explode('|', $file->fileName);
        $fileName = $fileTimestamp . "_" . $fileName;
        $fileExtensions = implode('|', $this->getExtensionsFile());

        // Vérifie si l'utilisateur envoyé est bien celui connecté
        if ($fileLogin != $usrLogin) {
            return sprintf("L'utilisateur %s ne correspond pas à celui connecté", $usrLogin);
        }

        // Vérifie si l'extension du fichier est autorisée
        if (!preg_match('/^.*.('.$fileExtensions.')$/i', $fileName)) {
            return "Ce type de fichier n'est pas autorisé";
        }

        // Crée les répertoires manquants si nécessaire
        if (!is_dir($pathFileUpload)) {
            mkdir($pathFileUpload, 0750, true);
        }

        // Sauvegarde le contenu du fichier
        if ($file->fileContent) {
            $fhandle = fopen($pathFileUpload. '/'. $fileName, 'w');
            fwrite($fhandle, $file->fileContent);
            fclose($fhandle);
        } else {
            $file->file->move($pathFileUpload, $fileName);
        }

        return true;
    }

    /**
     * Traitement de l'envoi du fichier à Interupload
     * @param $paramsRequest
     * @param $objTicket
     * @param $fileName
     * @return string
     */
    public function sendFileForInterupload($paramsRequest, $objTicket, $fileName)
    {
        $pathFileUpload = $this->pathFile();
        // Vérifie si l'utilisateur envoyé est bien celui connecté
        $token = $this->container->get('security.token_storage');
        $usrLogin = $token->getToken()->getUser()->getUsrLogin();
        list($fileLogin, $fileTimestamp, $fileName) = explode('|', $paramsRequest->pathEtArchiveName);
        if ($fileLogin != $usrLogin) {
            return sprintf("L'utilisateur %s ne correspond pas à celui connecté", $usrLogin);
        }
        $fileNameWithFullPath = $pathFileUpload.'/'.$fileTimestamp.'_'.$fileName;
        // On attribue une ressource fopen.
        $contens = fopen($fileNameWithFullPath, 'r');
        $client = new Client([
            'ssl.certificate_authority' => 'system',
            'verify' => false
        ]);
        try {
            // Service d'envoi du fichier vers interupload
            return $this->callInterupload($client, $objTicket, $contens, $fileNameWithFullPath);
        } catch (Exception $e) {
            throw new BadRequestHttpException(DocapostController::ERR_OBJECT_CONTENT_INCORRECT, $e);
        }
    }

    /**
     * Appel au Web Service soap Interupload
     * @param $client
     * @param $objTicket
     * @param $contens
     * @param $fileNameWithFullPath
     * @return bool
     */
    private function callInterupload($client, $objTicket, $contens, $fileNameWithFullPath)
    {
        $response = null;
        $response = $this->responseInterupload(
            $client, $objTicket, $contens, $fileNameWithFullPath, $this::IUP_RECEPTION);
        $objectContents = json_decode($response->getBody()->getContents());
        if (!is_object($objectContents) || is_null($objectContents)) {
            return false;
        }
        if ($this::IUP_RESPONSE_STATUS === $objectContents->status &&
            $this::IUP_RESPONSE_TEST1 === $objectContents->test) {
            $response2 = $response = $this->responseInterupload(
                $client, $objTicket, $contens, $fileNameWithFullPath, $this::IUP_CLOSURE);
            $objectContents2 = json_decode($response2->getBody()->getContents());
            if (is_null($objectContents2) || $objectContents2 == '') {
                return false;
            }
            if ($this::IUP_RESPONSE_STATUS !== $objectContents2->status ||
                $this::IUP_RESPONSE_TEST2 !== $objectContents2->test) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * Reponses d'interupload
     * @param $client
     * @param $objTicket
     * @param $contens
     * @param $fileNameWithFullPath
     * @param $responseType
     * @return mixed
     */
    private function responseInterupload($client, $objTicket, $contens, $fileNameWithFullPath, $responseType)
    {
        $response = false;
        /* @var $client object */
        if ($this::IUP_RECEPTION === $responseType) {
            $response = $client->request(
                'POST', $objTicket->getTicket->url, [
                'multipart' => [
                    [
                        'name' => 'manuel',
                        'contents' => 'bvrh5'
                    ],
                    [
                        'name'     => "myfile",
                        'contents' => $contens,
                        'filename' => $fileNameWithFullPath
                    ]
                ],
                'stream_context' => [
                    'ssl' => [
                        'allow_self_signed' => true
                    ]
                ]
            ]);
        } elseif ($this::IUP_CLOSURE === $responseType) {
            $response = $client->request('POST', $objTicket->getTicket->url, [
                'multipart' => [
                    [
                        'name' => 'manuel',
                        'contents' => 'bvrh5'
                    ]
                ],
                'stream_context' => [
                    'ssl' => [
                        'allow_self_signed' => true
                    ]
                ]
            ]);
        }
        return $response;
    }

    /**
     * Interupload Ticket et configuration
     * @return object|string
     */
    public function getTicketConfigAction()
    {
        try {
            $iupTicket = $this->container->get('interupload.ticket');
        } catch (Exception $e) {
            throw new BadRequestHttpException(DocapostController::ERR_OBJECT_CONTENT_INCORRECT, $e);
        }
        $data = $iupTicket->getResponseInterupload();
        try {
            $iupParams = $this->container->get('interupload.list_params');
        } catch (Exception $e) {
            throw new BadRequestHttpException(DocapostController::ERR_OBJECT_CONTENT_INCORRECT, $e);
        }
        $listParams = $iupParams->getResponseInterupload();
        $data['params'] = $listParams;
        // Control mise à jour ou creatioh du ticket
        $this->updateOrCreateTicket($data);

        return (object)$data;
    }

    /**
     * Ajout du ticket et information du formulaire Upload
     * @param $paramsRequest
     * @param $objTicket
     */
    public function addDataInteruploadIndexation($paramsRequest, $objTicket)
    {
        $content = (string)json_encode((array)$paramsRequest, true);
        $ticket = $objTicket->getTicket->ticket;
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $iniIupIndexation = new IniInteruploadIndexation();
        $iniIupIndexation->setIniTicket($ticket);
        $iniIupIndexation->setIniContent($content);
        $iniIupIndexation->setIniUpdatedAt(new \Datetime());
        $iniIupIndexation->setIniCreatedAt(new \Datetime());
        $entityManager->persist($iniIupIndexation);
        $entityManager->flush();
    }

    /**
     * Xml metadata creation pour iup_interupload
     * @param $objTicket
     * @return string
     */
    public function buildMedataCreation($objTicket)
    {
        /* @var $tokenStorage UserToken */
        $tokenStorage = $this->tokenStorage->getToken();
        $objUser = $tokenStorage->getUser();
        /* @var $objTicket IupInterupload */
        $objCreatedAt = $objTicket->getIupCreatedAt();
        /* @var $objUser UsrUsers */
        $content = [
            'enreg' => $objTicket->getIupId(),
            'table_indexfiche' => 'iup_interupload',
            'interupload_login' => $objUser->getUsrLogin(),
            'interupload_nom' => $objUser->getUsrNom(),
            'interupload_prenom' => $objUser->getUsrPrenom(),
            'interupload_adresse_mail' => $objUser->getUsrAdresseMail(),
            'interupload_date' => $objCreatedAt->format('d-m-Y H:i:s')
        ];
        $xml = new \SimpleXMLElement("<?xml version='1.0'?><metadata></metadata>");
        $this->arrayToXml($content, $xml);
        return $xml->asXML();
    }

    /**
     * Convertion d'un tableau au format xml
     * @param $array
     * @param $xml
     */
    public function arrayToXml($array, &$xml)
    {
        /* @var $xml object */
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml->addChild("$key");
                    $this->arrayToXml($value, $subnode);
                } else {
                    $this->arrayToXml($value, $xml);
                }
            } else {
                $xml->addChild("$key","$value");
            }
        }
    }

    /**
     * Préparation et ajout d'un nouveau document ifp
     * @param $objParams
     * @return bool|void
     */
    public function addIfpDocument($objParams)
    {
        // 1) Information du formulaire dans la table ini_interupload_indexation grace au numero de ticket
        $rowTicketIni = $this->entityManager->getRepository('ApiBundle:IniInteruploadIndexation')
            ->findOneBy(["iniTicket" => $objParams->ticket]);
        $iniContent = json_decode($rowTicketIni->getIniContent());
        // 2) Information du document actuel grace au IdNumMatriculeRh
        $matriculeRh = $iniContent->IdNumMatriculeRh;
        $rowIinIdxIndiv = $this->findIndexfichePaperless('IinIdxIndiv', ['iinIdNumMatriculeRh' => $matriculeRh]);
        // 3) Information des metadata production et autres à partir de paramètres reçus d'Interupload
        $objMproduction = $this->getMetadataProduction($objParams->metadataProduction);

        return $this->insertDocumentIfp($iniContent, $rowIinIdxIndiv, $objMproduction);
    }

    /**
     * Metadata Production
     * @param $mProduction
     * @return array
     */
    private function getMetadataProduction($mProduction)
    {
        $objMproduction = [];
        if (is_string($mProduction)) {
            $xmlMproduction = (array)simplexml_load_string($mProduction);
            $objectDocument = $xmlMproduction["document"];
            $mpType = isset($objectDocument->type)? $objectDocument->type : null;
            $mpPages = isset($objectDocument->pages)? $objectDocument->pages : null;
            $mpPoid = isset($objectDocument->taille)? $objectDocument->taille : null;
            $mpLocation = isset($objectDocument->location)? $objectDocument->location : null;
            $mpFilename = isset($objectDocument->filename)? $objectDocument->filename : null;
            $mpFilenameSansExt = isset($objectDocument->filename_sans_extension)?
                $objectDocument->filename_sans_extension : null;
            $mpVolumeStockageImg = isset($objectDocument->VolumeStockageImagerie)?
                $objectDocument->VolumeStockageImagerie : null;
            // Définition des documents associés
            $documentsassocies = $this->rulesDocumentsAssocies(
                $mpType, $mpFilenameSansExt, $mpPages, $mpFilename, $mpVolumeStockageImg, $mpPoid);
            // Définition de la localisation
            $vdmLocalisation = $this->rulesVdmLocalisation($mpLocation);
            $explodeLocation = explode("/", $vdmLocalisation);
            $archiveSerialnum = isset($explodeLocation[4])? $explodeLocation[4] : null;
            // si le numero d'archive n'est pas renseigné on le met a jour en splittant vdmlocalisation
            if (is_null($archiveSerialnum)) {
                $split = explode("/", $vdmLocalisation);
                $archiveSerialnum = $split[3];
            }
            $objMproduction = $this->fieldsMetadataProduction(
                $mpType, $mpPages, $mpPoid, $vdmLocalisation, $documentsassocies, $explodeLocation, $archiveSerialnum);
        }
        return $objMproduction;
    }

    /**
     * Champs ifp provenant de metadata production
     * @param $mpType
     * @param $mpPages
     * @param $mpPoid
     * @param $vdmLocalisation
     * @param $documentsassocies
     * @param $explodeLocation
     * @param $archiveSerialnum
     * @return array
     */
    private function fieldsMetadataProduction(
        $mpType, $mpPages, $mpPoid, $vdmLocalisation, $documentsassocies, $explodeLocation, $archiveSerialnum)
    {
        return [
            "ifp_id_format_document" => $mpType,
            "ifp_nombrepages" => $mpPages,
            "ifp_id_poids_document" => (strlen($mpPoid)<12)? str_pad($mpPoid, 12, "0", STR_PAD_LEFT) : $mpPoid,
            "ifp_vdm_localisation" => $vdmLocalisation,
            "ifp_archive_name" => "upload" . date('Ymd') . ".pdf",
            "ifp_documentsassocies" => $documentsassocies,
            "ifp_archive_cfec" => $explodeLocation[2],
            "ifp_archive_serialnumber" => $archiveSerialnum,
            "ifp_archive_cfe" => $explodeLocation[3]
        ];
    }

    /**
     * @param $mpType
     * @param $mpFilenameSansExt
     * @param $mpPages
     * @param $mpFilename
     * @param $mpVolumeStockageImg
     * @param $mpPoid
     * @return string
     */
    private function rulesDocumentsAssocies(
        $mpType, $mpFilenameSansExt, $mpPages, $mpFilename, $mpVolumeStockageImg, $mpPoid)
    {
        $documentsassocies = null;
        if ($mpType == "PDFa") {
            //Type E
            $documentsassocies .= "E" . $mpFilenameSansExt . "/1/" . $mpPages . "|";
        } else {
            //Type B
            $documentsassocies .= "B" . $mpFilename . "/" . $mpVolumeStockageImg . "/" . $mpPoid . "|";
        }
        return substr($documentsassocies, 0, (strlen($documentsassocies) - 1));
    }

    /**
     * @param $mpLocation
     * @return string
     */
    private function rulesVdmLocalisation($mpLocation)
    {
        $vdmLocalisation = null;
        $vdmLocalisation .= "S/E//" . $mpLocation . "|";
        $vdmLocalisation = substr($vdmLocalisation, 0, (strlen($vdmLocalisation) - 1));
        return $vdmLocalisation;
    }

    /**
     * Insertion d'un nouveau document en base
     * @param $iniContent
     * @param $rowIinIdxIndiv
     * @param $objMproduction
     * @return IfpIndexfichePaperless|array
     * @throws \Doctrine\ORM\ORMException
     */
    private function insertDocumentIfp($iniContent, $rowIinIdxIndiv, $objMproduction)
    {
        /* @var $objectIdCategory TypType */
        $objectIdCategory = $this->entityManager->getReference('ApiBundle:TypType', $iniContent->idCategory);
        $ifpNombrepages = (array)$objMproduction['ifp_nombrepages'];
        $nombrePages = (count($ifpNombrepages)>0)? $ifpNombrepages[0] : null;
        $ifpLoginAndDate = explode('|', $iniContent->idUpload);
        $ifpLogin = $ifpLoginAndDate[0];
        $periode = explode('/', $iniContent->IdPeriodePaie);
        $periodePaie = $periode[1].$periode[0];
        $periodeExercice = $periode[1];
        $date = new \DateTime();
        /* @var $rowIinIdxIndiv IinIdxIndiv */
        $ifpPaperless = new IfpIndexfichePaperless();
        $ifpPaperless->setIfpDocumentsassocies($objMproduction['ifp_documentsassocies']);
        $ifpPaperless->setIfpVdmLocalisation($objMproduction['ifp_vdm_localisation']);
        $ifpPaperless->setIfpRefpapier('');
        $ifpPaperless->setIfpNombrepages($nombrePages);
        $ifpPaperless->setIfpIdCodeChrono($rowIinIdxIndiv->getIinIdCodeChrono());
        $ifpPaperless->setIfpIdNumeroBoiteArchive($iniContent->IdNumeroBoiteArchive);
        $ifpPaperless->setIfpInterbox('N');
        $ifpPaperless->setIfpLotIndex('');
        $ifpPaperless->setIfpLotProduction(0);
        $ifpPaperless->setIfpIdNomSociete($rowIinIdxIndiv->getIinIdNomSociete());
        $ifpPaperless->setIfpIdCompany('');
        $ifpPaperless->setIfpIdNomClient($rowIinIdxIndiv->getIinIdNomClient());
        $ifpPaperless->setIfpCodeClient($rowIinIdxIndiv->getIinCodeClient());
        $ifpPaperless->setIfpIdCodeSociete($rowIinIdxIndiv->getIinIdCodeSociete());
        $ifpPaperless->setIfpIdCodeEtablissement($rowIinIdxIndiv->getIinIdCodeEtablissement());
        $ifpPaperless->setIfpIdLibEtablissement($rowIinIdxIndiv->getIinIdLibEtablissement());
        if ($rowIinIdxIndiv->getIinIdCodeJalon() != '') {
            $ifpPaperless->setIfpIdCodeJalon($rowIinIdxIndiv->getIinIdCodeJalon());
            $ridRefId = $this->entityManager
                ->getRepository('ApiBundle:RidRefId')->findOneBy(['ridCode' => $rowIinIdxIndiv->getIinIdCodeJalon()]);
            $ifpPaperless->setIfpIdLibelleJalon($ridRefId->getRidLibelle());
        }
        $ifpPaperless->setIfpIdNumSiren($rowIinIdxIndiv->getIinIdNumSiren());
        $ifpPaperless->setIfpIdNumSiret($rowIinIdxIndiv->getIinIdNumSiret());
        $ifpPaperless->setIfpIdIndiceClassement($objectIdCategory);
        $ifpPaperless->setIfpIdLibelleDocument($iniContent->IdLibelleDocument);
        $ifpPaperless->setIfpIdUniqueDocument('');
        $ifpPaperless->setIfpIdTypeDocument('');
        $ifpPaperless->setIfpCodeDocument($objectIdCategory);
        $ifpPaperless->setIfpIdFormatDocument($objMproduction['ifp_id_format_document']);
        $ifpPaperless->setIfpIdAuteurDocument(IfpIndexfichePaperless::DEFAULT_AUTEUR_DOCUMENT);
        $ifpPaperless->setIfpIdSourceDocument(IfpIndexfichePaperless::SOURCE_DOCUMENT_EXCLUDED);
        $ifpPaperless->setIfpIdNumVersionDocument('1');
        $ifpPaperless->setIfpIdPoidsDocument($objMproduction['ifp_id_poids_document']);
        $ifpPaperless->setIfpIdNbrPagesDocument('');
        $ifpPaperless->setIfpIdProfilArchivage('');
        $ifpPaperless->setIfpIdEtatArchive('');
        $ifpPaperless->setIfpIdPeriodePaie($periodePaie);
        $ifpPaperless->setIfpIdPeriodeExerciceSociale($periodeExercice);
        $ifpPaperless->setIfpIdDateDernierAccesDocument(null);
        $ifpPaperless->setIfpIdDateArchivageDocument(new \DateTime());
        $ifpPaperless->setIfpIdDureeArchivageDocument($objectIdCategory->getTypVieDuree());
        $ifpPaperless->setIfpIdDateFinArchivageDocument(new \DateTime());
        $ifpPaperless->setIfpIdNomSalarie($rowIinIdxIndiv->getIinIdNomSalarie());
        $ifpPaperless->setIfpIdPrenomSalarie($rowIinIdxIndiv->getIinIdPrenomSalarie());
        $ifpPaperless->setIfpIdNomJeuneFilleSalarie($rowIinIdxIndiv->getIinIdNomJeuneFilleSalarie());
        $ifpPaperless->setIfpIdDateNaissanceSalarie($rowIinIdxIndiv->getIinIdDateNaissanceSalarie());
        $ifpPaperless->setIfpIdDateEntree($rowIinIdxIndiv->getIinIdDateEntree());
        $ifpPaperless->setIfpIdDateSortie($rowIinIdxIndiv->getIinIdDateSortie());
        $ifpPaperless->setIfpIdCodeCategProfessionnelle($rowIinIdxIndiv->getIinIdCodeCategProfessionnelle());
        $ifpPaperless->setIfpIdCodeCategSocioProf($rowIinIdxIndiv->getIinIdCodeCategSocioProf());
        $ifpPaperless->setIfpIdTypeContrat($rowIinIdxIndiv->getIinIdTypeContrat());
        $ifpPaperless->setIfpIdAffectation1($rowIinIdxIndiv->getIinIdAffectation1());
        $ifpPaperless->setIfpIdAffectation2($rowIinIdxIndiv->getIinIdAffectation2());
        $ifpPaperless->setIfpIdAffectation3($rowIinIdxIndiv->getIinIdAffectation3());
        $ifpPaperless->setIfpIdLibre1($rowIinIdxIndiv->getIinIdLibre1());
        $ifpPaperless->setIfpIdLibre2($rowIinIdxIndiv->getIinIdLibre2());
        $ifpPaperless->setIfpIdAffectation1Date('');
        $ifpPaperless->setIfpIdAffectation2Date('');
        $ifpPaperless->setIfpIdAffectation3Date('');
        $ifpPaperless->setIfpIdLibre1Date('');
        $ifpPaperless->setIfpIdLibre2Date('');
        $ifpPaperless->setIfpNumMatricule($rowIinIdxIndiv->getIinNumMatricule());
        $ifpPaperless->setIfpIdNumMatriculeRh($rowIinIdxIndiv->getIinIdNumMatriculeRh());
        $ifpPaperless->setIfpIdNumMatriculeGroupe($rowIinIdxIndiv->getIinIdNumMatriculeGroupe());
        $ifpPaperless->setIfpIdAnnotation('');
        $ifpPaperless->setIfpIdConteneur('');
        $ifpPaperless->setIfpIdBoite('');
        $ifpPaperless->setIfpIdLot('');
        $ifpPaperless->setIfpIdNumOrdre($rowIinIdxIndiv->getIinIdNumOrdre());
        $ifpPaperless->setIfpArchiveCfec($objMproduction['ifp_archive_cfec']);
        $ifpPaperless->setIfpArchiveSerialnumber($objMproduction['ifp_archive_serialnumber']);
        $ifpPaperless->setIfpArchiveDatetime($date->format('Y/m/d H:i:s'));
        $ifpPaperless->setIfpArchiveName($objMproduction['ifp_archive_name']);
        $ifpPaperless->setIfpArchiveCfe($objMproduction['ifp_archive_cfe']);
        $ifpPaperless->setIfpNumeropdf('');
        $ifpPaperless->setIfpOpnProvenance(IupInterupload::ARCHIVING_SYSTEM);
        $ifpPaperless->setIfpStatusNum('OK');
        $ifpPaperless->setIfpIsDoublon('N');
        $ifpPaperless->setIfpLogin($ifpLogin);
        $ifpPaperless->setIfpModedt('');
        $ifpPaperless->setIfpIdNumNir($rowIinIdxIndiv->getIinIdNumNir());
        $ifpPaperless->setIfpNumdtr('');
        $ifpPaperless->setIfpIdCodeActivite($rowIinIdxIndiv->getIinIdCodeActivite());
        $ifpPaperless->setIfpCycleFinDeVie(false);
        $ifpPaperless->setIfpGeler(false);
        $ifpPaperless->setIfpSetFinArchivage(false);
        $ifpPaperless->setIfpIsPersonnel(false);
        // Analyse des champs obligatoire non recpectés
        $list = $this->checkFieldsNullableFalse($ifpPaperless);
        if (!empty($list)) {
            $fieldsNotNullables = [
                "code" => DocapostController::ERR_FIELDS_NOT_NULLABLE,
                "fields" => $list
            ];
            return $fieldsNotNullables;
        }
        $this->entityManager->persist($ifpPaperless);
        $this->entityManager->flush();
        return $ifpPaperless;
    }

    /**
     * Chemin du fichier
     * @return string
     */
    private function pathFile()
    {
        $token = $this->container->get('security.token_storage');
        $numinstance = $token->getToken()->getAttributes()['pac'];
        $usrLogin = $token->getToken()->getUser()->getUsrLogin();
        $basePathUpload = $this->container->getParameter('base_path_upload');
        $additionalPathUpload = $numinstance.'/'.$usrLogin;

        return $basePathUpload.'/'.$additionalPathUpload;
    }

    /**
     * Liste de extensions permis pour l'upload
     * @return array
     */
    private function getExtensionsFile()
    {
        $listParams = $this->container->get('interupload.list_params')->getResponseInterupload();
        $xmlParams = simplexml_load_string($listParams['iucConfig']);
        $traitementsParams = $xmlParams->traitements->traitement;
        $listExtension = explode(";*.", (string)$traitementsParams);
        $listExtension[0] = substr($listExtension[0], 2, strlen($listExtension[0]) + 1);
        $listExtension[count($listExtension) - 1] = substr($listExtension[count($listExtension) - 1], 0, -1);

        return $listExtension;
    }

    /**
     * Recherche de id_num_matricule_rh sur les tables ifp et iin
     * @param $table
     * @param $field
     * @return array
     */
    public function findIndexfichePaperless($table, $field)
    {
        return $this->entityManager->getRepository('ApiBundle:'.$table)
            ->findOneBy($field);
    }

    /**
     * Mise à jour ou creatioh du ticket
     * @param $data
     * @return \ApiBundle\DocapostJsonResponse
     */
    private function updateOrCreateTicket($data)
    {
        $iupBuildTicket = $this->container->get('interupload.build_ticket');
        $ticket = $iupBuildTicket->searchTicket($data['getTicket']->ticket);
        if (is_null($ticket)) {
            return $iupBuildTicket->createTicket($data);
        }
        return null;
    }

    /**
     * Control de nullable champss
     * @param $ojectForPersist
     * @return array
     */
    private function checkFieldsNullableFalse($ojectForPersist)
    {
        // Champs obligatoires
        $notNullFields = $this->nullableFalseFieldsByTable('IfpIndexfichePaperless');
        $docapost = new DocapostController();
        $fields = $docapost->convertIntoArray($ojectForPersist);
        $filesNull = [];
        foreach ($fields as $key => $field) {
            if (is_null($field) && $key != 'ifpId') {
                if (array_key_exists($key, $notNullFields)) {
                    $filesNull[$key] = $field;
                }
            }
        }

        return $filesNull;
    }

    /**
     * Liste de champs obligatoires
     * @param $table
     * @return array
     */
    private function nullableFalseFieldsByTable($table)
    {
        $notNullFields = [];
        $metadata = $this->entityManager->getClassMetadata('ApiBundle:' . $table);
        foreach ($metadata->fieldMappings as $key => $field) {
            if ($field['nullable'] === false) {
                $notNullFields[$key] = $field['columnName'];
            }
        }
        return $notNullFields;
    }

    /**
     * Crée un fichier Excel et le stocke sur le disque
     *
     * @param array $values
     *
     * @return bool
     */
    public function createDocumentsInExcelFile(array $values)
    {
        $userToken = $this->tokenStorage->getToken();
        $user = $this->tokenStorage->getToken()->getUser();
        /** @var UserToken $userToken */
        $numInstance = $userToken->getNumInstance();
        $this->constructDocumentsExcelFile($values);
        $pathName = $this->container->getParameter('base_path_export')
            . '/' . $numInstance . '/' . $user->getUsrLogin();
        $filename = 'export_doc_' . $user->getUsrLogin() . '.xlsx';
        // Création des répertoires si manquants
        if (!is_dir($pathName)) {
            $creation = mkdir($pathName, 0750, true);
            if (!$creation) {
                return false;
            }
        }
        $pathName .= '/' . $filename;
        $this->exportExcelManager->save($pathName);
        // Lecture du rapport
        if (!$report = $this->container->get('api.manager.report')->retieveOneReportByUserAndType(
            EnumLabelTypeRapportType::RECHERCHE_TYPE
        )
        ) {
            $report = new RapRapport();
            $report->setRapLibelleFic($filename);
            $report->setRapTypeRapport(EnumLabelTypeRapportType::RECHERCHE_TYPE);
            $report->setRapUser($user);
        }
        // Création d'un formulaire
        $form = $this->container->get('form.factory')
            ->create(RapRapportType::class, $report);
        $form->submit(['rapFichier' => $pathName], false);
        // Validation
        if ($form->isValid()) {
            $this->entityManager->persist($report);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    /**
     * Effectue l'export d'un rapport en synchrone
     *
     * @param array $values
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportDocumentsInExcelFile(array $values)
    {
        /** @var UsrUsers $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $this->constructDocumentsExcelFile($values);
        // Renvoie la réponse au navigateur
        return $this->exportExcelManager->export('export_doc_' . $user->getUsrLogin() . '.xlsx');
    }

    /**
     * Construit l'export des documents dans un fichier Excel
     *
     * @param array $values
     */
    private function constructDocumentsExcelFile(array $values)
    {
        $format = [];
        if (isset($values[1])) {
            $metadatas = $this->container->get('api.manager.metadata')->getListFieldsAndPropertiesDocument()['fields'];
            foreach (array_keys($values[1]) as $col => $field) {
                if (isset($metadatas[$field]['type'])) {
                    switch ($metadatas[$field]['type']) {
                        case 'text':
                        case 'string':
                            $format['setOnColumns'][$col]['setTypeAs'] = \PHPExcel_Cell_DataType::TYPE_STRING;
                    }
                }
            }
            // Ajout d'un style pour les en-têtes
            $format['setOnRows'][]['applyFromArray'] = $this->exportExcelManager->getStyleHeader();
        }
        // Création du fichier
        $dictionaryManager = $this->container->get('api.manager.dictionnaire');
        $this->exportExcelManager->create([
            'title' => $dictionaryManager->getParameter(RapRapport::PROPERTIE_RAP_SEARCH_EXPORT_TITLE),
            'subject' => $dictionaryManager->getParameter(RapRapport::PROPERTIE_RAP_SEARCH_EXPORT_TITLE),
            'description' => $dictionaryManager->getParameter(RapRapport::PROPERTIE_RAP_SEARCH_EXPORT_DESCRIPTION),
            'sheet' => [
                'title' => $dictionaryManager->getParameter(RapRapport::PROPERTIE_RAP_SEARCH_EXPORT_SHEET_TITLE)
            ]
        ]);
        $this->exportExcelManager->fromCustomArray($values, $format);
    }

    /**
     * Récupère la liste des flux CEL
     *
     * @param array $datas
     *
     * @return array
     */
    public function retrieveCelList(array $datas)
    {
        return $this->documentRepository->findCelList($datas);
    }

    /**
     * Renvoie une liste de valeurs pour une colonne de IfpIndexfichePaperless
     *
     * @param array $params
     *
     * @return array
     */
    public function retrieveFieldSearch(array $params)
    {
        return array_column(
            $this->documentRepository->findFieldSearch($params),
            $params['field']
        );
    }
}
