<?php

namespace ApiBundle\Controller\apiV1;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\RapRapport;
use ApiBundle\Entity\RcsRefCodeSociete;
use ApiBundle\Entity\TypType;
use ApiBundle\Form\IfpIndexfichePaperlessType;
use ApiBundle\Manager\DocumentManager;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Controller\DocapostController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use VisBundle\Service\VisClientService;

/**
 * Class DocumentController
 *
 * @package ApiBundle\Controller\apiV1
 */
class DocumentController extends DocapostController
{
    const TYPE_PDC = 'PDC';
    const TYPE_BASKET = 'BASKET';
    const TYPE_THE = 'PROCESSUS';
    const TYPE_CMP = 'COMPLETUDE';
    const TYPE_FOLDER = 'FOLDER';

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Récupère une liste de documents",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     * @Post("/document/search")
     *
     * @return DocapostJsonResponse
     */
    public function postDocumentSearch()
    {
        $documentManager = $this->get('api.manager.document');
        $params = $this->getContentParameters(
            false,
            true,
            [
                'node' => [
                    'type' => '^[A-Z]+$',
                    'value' => null,
                    'leaf' => 'bool'
                ],
                'start' => 'int',
                'limit' => 'int'
            ]
        );
        // Vérifie si un message a été positionné lors du traitement des paramètres
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        if (!isset($params->fields)) {
            $params->fields = (object)[];
        }
        if (!isset($params->sorts)) {
            $params->sorts = (object)[];
        }

        return $this->export(
            $documentManager->retrieveListDocumentWS($params)
        );
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Récupère le nombre de documents d'un noeud ou tiroir",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     * @Post("/document/search/count")
     *
     * @return DocapostJsonResponse
     */
    public function postDocumentSearchCount()
    {
        $documentManager = $this->get('api.manager.document');
        $params = $this->getContentParameters(
            false,
            true,
            [
                'node' => [
                    'type' => '^[A-Z]+$',
                    'value' => null,
                    'leaf' => 'bool'
                ]
            ]
        );
        // Vérifie si un message a été positionné lors du traitement des paramètres
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        if (!isset($params->fields)) {
            $params->fields = (object)[];
        }

        return $this->export(
            $documentManager->countListDocumentWS($params)
        );
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Liste de champs et propiétés de la table IfpIndexfichePaperless",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     * @Get("/document/metadata")
     *
     * @return DocapostJsonResponse
     */
    public function getListPropertiesFields()
    {
        return $this->export(
            $this->get('api.manager.metadata')->getListFieldsAndPropertiesDocument()
        );
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Dégèle d'un document",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     * @param $documentId
     * @return DocapostJsonResponse
     *
     * @Get("/document/{documentId}/unfreeze", requirements={"documentId" = "\d+"})
     */
    public function getUnfreezeDocument($documentId)
    {
        // Manager des documents
        $documentManager = $this->get('api.manager.document');
        // Lecture du document
        /* @var $document IfpIndexfichePaperless */
        if (!$document = $documentManager->getDocumentById($documentId)) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED,
                [DocapostController::MSG_CODE_VALUE => $documentId]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        $result = $documentManager->unfreezeDocument($document);
        if (!is_array($result)) {
            $this->addResponseMessage($result);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_WARN);
        }

        return $this->export($result);
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Gèle des documents",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     *
     * @param $documentId
     * @return DocapostJsonResponse
     *
     * @Get("/document/{documentId}/freeze", requirements={"documentId" = "\d+"})
     */
    public function getFreezeDocument($documentId)
    {
        // Manager des documents
        $documentManager = $this->get('api.manager.document');
        // Lecture du document
        if (!$document = $documentManager->getDocumentById($documentId, true)) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED,
                [DocapostController::MSG_CODE_VALUE => $documentId]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        $result = $documentManager->freezeDocument($document);
        // Erreur : Le document est déjà gelé
        if (!is_array($result)) {
            $this->addResponseMessage($result);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_WARN);
        }

        return $this->export($result);
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Modification des métadonnées du document",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     *
     * @param $documentId
     * @return DocapostJsonResponse
     *
     * @Put("/document/{documentId}", requirements={"documentId" = "\d+"})
     */
    public function putDocument($documentId)
    {
        $documentManager = $this->get('api.manager.document');
        $parametersToCheck = [
            'ifpId' => 'int',
            'ifpIdPeriodePaie' => null,
            'ifpIdPeriodeExerciceSociale' => null,
            'ifpIdNumeroBoiteArchive' => null,
            'ifpCodeDocument' => null
        ];
        $datas = $this->getContentParameters(true, true, $parametersToCheck);
        // Vérifie que l'Id de l'URL est identique à l'Id passé dans les paramètres
        if ($documentId != $datas['ifpId']) {
            $this->addResponseMessage(
                DocapostController::ERR_OBJECT_CONTENT_INCORRECT,
                [DocapostController::MSG_CODE_VALUE => $datas['ifpId']]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 400);
        }
        // Lecture du document
        /* @var $document IfpIndexfichePaperless */
        if (!$document = $documentManager->getDocumentById($datas['ifpId'])) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED,
                [DocapostController::MSG_CODE_VALUE => $datas['ifpId']]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        }
        // Mise à jour des métadonnées ?
        if (!$documentManager->userCanUpdateDocument($document)) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED,
                [DocapostController::MSG_CODE_VALUE => $datas['ifpId']]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
        }
        // Validation du formulaire
        $documentForm = $this->createForm(IfpIndexfichePaperlessType::class, $document);
        if (!$documentForm->submit($datas, false)->isValid()) {
            // Erreur(s) pendant la validation
            $this->translateErrorsFormIntoResponseMessages($documentForm);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Libellé du tiroir
        if (!$label = $this->get('api.manager.dictionnaire')->getParameter('pdcTiroir-' . $datas['ifpCodeDocument'])) {
            $this->addResponseMessage(
                TypType::ERR_NOT_TRANSLATED,
                [DocapostController::MSG_CODE_VALUE => $datas['ifpCodeDocument']]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        } else {
            $document->setIfpIdLibelleDocument($label);
            $documentManager->updateDocument($document);
        }
        return $this->export($documentManager->getDocumentById($datas['ifpId'], true));
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Suppression d'un document",
     *     statusCodes={
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants",
     *          403="Accès non autorisé",
     *          404="Ressource non trouvée"
     *     }
     * )
     *
     * @return DocapostJsonResponse
     *
     * @Delete("/document")
     */
    public function deleteDocument()
    {
        $documentManager = $this->get('api.manager.document');
        $parametersToCheck = [
            'ifpId' => 'int',
        ];
        $datas = $this->getContentParameters(true, true, $parametersToCheck);
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Lecture du document
        if (!$document = $documentManager->getDocumentById($datas['ifpId'])) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED,
                [DocapostController::MSG_CODE_VALUE => $datas['ifpId']]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 404);
        }
        // Peut-on supprimer le document ?
        if (!$documentManager->userCanDeleteDocument($document)) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_USER_NOT_ALLOWED,
                [DocapostController::MSG_CODE_VALUE => $datas['ifpId']]
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 403);
        }
        $documentManager->deleteDocument($document);
        return $this->export(null);
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Upload d'un document",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing"
     *     }
     * )
     * @Post("/document/upload")
     * @codeCoverageIgnore
     */
    public function postUploadDocument()
    {
        $request = $this->container->get('request');

        // Paramétres du fichier uploadé
        if (!$fileName = ($request->get('fileName'))? $request->get('fileName') : null) {
            $this->addResponseMessage(DocumentManager::ERR_FILENAME_REQUIRED);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_INFO, 400);
        }
        if (!$idUpload = ($request->get('idUpload'))? $request->get('idUpload') : null) {
            $this->addResponseMessage(DocumentManager::ERR_IDUPLOAD_REQUIRED);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_INFO, 400);
        }

        // Récupère le contenu du fichier
        if ($fileContent = $request->get('fileContent')) {  // Fichier issu du scanner
            $fileContent = base64_decode($fileContent);
        } elseif ($request->files->count()) {  // Fichier issu du drag&drop
            $file = $request->files->get('file');
        } else {
            $this->addResponseMessage(DocumentManager::ERR_FILECONTENT_ERROR);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_INFO, 400);
        }

        $file = (object) [
            'fileName' => $fileName,
            'fileContent' => isset($fileContent)? $fileContent : null,
            'idUpload' => $idUpload,
            'file' => isset($file)? $file : null
        ];

        // Creation du fichier
        $documentManager = $this->container->get('api.manager.document');
        $result = $documentManager->createFileUploadDocument($file);

        // Une erreur s'est produite lors de l'upload
        if (!($result === true)) {
            $this->addResponseMessage($result);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 400);
        }

        // Upload Ok
        $this->addResponseMessage('UPLOAD_COMPLETE', (array)$file);
        return $this->messageJsonResponse(DocapostController::MSG_LEVEL_INFO, 200);
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Télécharge un document",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     *
     * @ParamConverter("ifp", class="ApiBundle:IfpIndexfichePaperless", options={"id" = "documentId"})
     *
     * @Get("/document/download/{documentId}", requirements={"documentId" = "\d+"})
     *
     * @param IfpIndexfichePaperless $ifp
     *
     * @return DocapostJsonResponse
     */
    public function getDownloadDocument(IfpIndexfichePaperless $ifp)
    {
        $ikp = $this->get('api.manager.interkeepass')->getConfiguration();

        $script = $this->get('api.manager.document')->createVisScript($ifp);
        $this->get('logger')->info('Executing VIS script '.$script);

        $config = $this->getDoctrine()->getRepository('ApiBundle:ConConfig');
        $url = $this->get('api.manager.interkeepass')->convert($config['ORSID_VIS_URL_HTTP']);
        $vis = new VisClientService($url);

        $visResponse = $vis->executeScript($script, $ikp['vis_local_path']);

        $response = new Response($visResponse->getBody()->getContents());
        $response->headers->set('Content-type', 'application/pdf');
        return $response;
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Prévisualisation d'un document",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     *
     * @ParamConverter("ifp", class="ApiBundle:IfpIndexfichePaperless", options={"id" = "documentId"})
     *
     * @Get("/document/preview/{documentId}", requirements={"documentId" = "\d+"})
     *
     * @param IfpIndexfichePaperless $ifp
     *
     * @return DocapostJsonResponse
     */
    public function getPreviewDocument(IfpIndexfichePaperless $ifp)
    {
        $ikp = $this->get('api.manager.interkeepass')->getConfiguration();

        $script = $this->get('api.manager.document')->createVisScript($ifp);
        $this->get('logger')->info('Executing VIS script '.$script);

        $config = $this->getDoctrine()->getRepository('ApiBundle:ConConfig');
        $url = $this->get('api.manager.interkeepass')->convert($config['ORSID_VIS_URL_HTTP']);
        $vis = new VisClientService($url);

        $visResponse = $vis->executeScript($script, $ikp['vis_local_path'], array(
            'ConvertProfil' => 'jpegtumb',
            'ConvertPage' => 1,
            'ConvertPageHeight' => 500,
            'ConvertPageWidth' => 500,
            'mode' => 'doc_image',
        ));

        $response = new Response($visResponse->getBody()->getContents());
        $response->headers->set('Content-type', 'image/jpeg');
        return $response;
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Ajout d'un document",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing",
     *          404="Returned when no files found"
     *     }
     * )
     * @Post("/document")
     *
     * @return DocapostJsonResponse
     */
    public function postDocument()
    {
        $request = $this->container->get('request');
        $matriculeRH = ($request->request->get('matriculeRH'))? $request->request->get('matriculeRH') : null;
        if (is_null($matriculeRH)) {
            $this->addResponseMessage(
                IfpIndexfichePaperless::ERR_NOT_MATRICULE_RH,
                []
            );
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        $boiteArchive = ($request->request->get('boiteArchive'))? $request->request->get('boiteArchive') : null;
        $category = ($request->request->get('category'))? $request->request->get('category') : null;
        $idCategory = ($request->request->get('idCategory'))? $request->request->get('idCategory') : null;
        $idUpload = ($request->request->get('idUpload'))? $request->request->get('idUpload') : null;
        $fileName = ($request->request->get('fileName'))? $request->request->get('fileName') : null;

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $pattern = '/.'.$ext.'/';
        $strFileName = preg_replace($pattern, '', $fileName);
        $stringFileName = explode('|', $strFileName);
        // Liste toutes les variables
        $nameFile = $stringFileName[2];
        $libDoc = ($request->request->get('libDoc'))? $request->request->get('libDoc') : null;
        $nomEtablissement = ($request->request->get('nomEtablissement'))?
            $request->request->get('nomEtablissement') : null;
        $nomSociete = ($request->request->get('nomSociete'))? $request->request->get('nomSociete') : null;
        $periode = ($request->request->get('periode'))? $request->request->get('periode') : null;
        $typeImport = ($request->request->get('typeImport'))? $request->request->get('typeImport') : null;
        // Demande du Ticket
        $documentManager = $this->container->get('api.manager.document');
        $objTicket = $documentManager->getTicketConfigAction();
        // Collection des parametres
        $paramsRequest = (object) [
            'IdNumeroBoiteArchive' => $boiteArchive,
            'category' => $category,
            'idCategory' => $idCategory,
            'pathEtArchiveName' => $fileName,
            'idUpload' => $idUpload,
            'IdLibelleDocument' => $libDoc,
            'IdNumMatriculeRh' => $matriculeRH,
            'IdLibEtablissement' => $nomEtablissement,
            'IdNomSociete' => $nomSociete,
            'IdPeriodePaie' => $periode,
            'typeImport' => $typeImport,
            'nameFile' => $nameFile
        ];
        // Saisie du ticket sur  ini_interupload_indexation
        $documentManager->addDataInteruploadIndexation($paramsRequest, $objTicket);
        // Mise à jour du ticket sur la table iup_interupload
        $iupTicket = $this->container->get('interupload.build_ticket');
        $iupTicket->updateTicketInIupInterupload($objTicket);
        // Envoie et réponse d'Interupload du fichier receptionné
        $responseIup = $documentManager->sendFileForInterupload($paramsRequest, $objTicket, $fileName);
        if (false === $responseIup) {
            $this->addResponseMessage('ADD_DOCUMENT_PROCESSING_ERROR');
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR, 200);
        }
        $this->addResponseMessage('ADD_DOCUMENT_PROCESSING_ON_THE_WAY', (array)json_decode($responseIup));
        return $this->messageJsonResponse(DocapostController::MSG_LEVEL_INFO, 200);
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Liste autocompletion de recherche",
     *     statusCodes={
     *          200="Returned on request success",
     *          400="Returned when parameters are missing"
     *     }
     * )
     * @Post("/document/autocomplete/search")
     */
    public function postAutocompleteSearch()
    {
        $managerAutocomplete = $this->get('api.manager.autocomplete');
        $datas = $this->getContentParameters(
            false,
            true,
            ['actualData' => 'bool', 'archivedData' => 'bool']
        );
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        // Récupération de paramètres sources
        $sourceParams = $managerAutocomplete->getSourceParams($datas);
        $source = array_flip($sourceParams);
        // Liste de tous les champs et contextes recherchés
        $searchable = $managerAutocomplete->getSearchableFields($source);
        // Contrôle du PAC Client
        if (!$managerAutocomplete->checkPac($datas)) {
            $this->addResponseMessage(RcsRefCodeSociete::ERR_PAC_DOES_NOT_EXIST);
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        // Renvoi la recherche par autocompletion
        return $this->export(
            $managerAutocomplete->autocompleteSearch($datas, $searchable)
        );
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Exporte une liste de documents",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/document/export")
     *
     * @return DocapostJsonResponse
     */
    public function postDocumentExport()
    {
        $documentManager = $this->get('api.manager.document');
        $params = $this->getContentParameters(
            false,
            true,
            [
                'node' => [
                    'type' => '^[A-Z]+$',
                    'value' => null,
                    'leaf' => 'bool'
                ],
                'totalCount' => 'bool',
                'columns' => [null]
            ]
        );
        // Vérifie si un message a été positionné lors du traitement des paramètres
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        if (!isset($params->fields)) {
            $params->fields = (object)[];
        }
        if (!isset($params->sorts)) {
            $params->sorts = (object)[];
        }
        $headers = [];
        // Récupère la traduction de chaque champ pour les en-têtes du fichier
        foreach ($params->columns as $column) {
            $headers[] = $this->get('api.manager.dictionnaire')->getParameter($column);
        }
        $list = $documentManager->retrieveListDocumentWS($params, $params->columns);
        if (!empty($list['totalCount'])) {
            $values = array_merge([$headers], $list['items']);
            // Récupération de la limite des documents exportables en mode synchrone
            $limit = $this->get('api.manager.config')->getParameter('export_synchrone_documents_limit');
            // Passage en mode asynchrone si le nombre de documents est supérieur à la limite
            if ($limit && $list['totalCount'] > $limit) {
                if (!$documentManager->createDocumentsInExcelFile($values)) {
                    $this->addResponseMessage(RapRapport::ERR_RAP_CREATION_FAILED);
                    return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
                }
                $this->addResponseMessage(RapRapport::INFO_RAP_CREATION_WAS_SUCCESSFUL);
                return $this->messageJsonResponse(DocapostController::MSG_LEVEL_INFO, 200);
            }
        } else {
            $values[] = $headers;
        }
        // Renvoie du fichier au navigateur
        return $documentManager->exportDocumentsInExcelFile($values);
    }

    /**
     * @ApiDoc(
     *     section="Document",
     *     description="Récupère les valeurs d'un champ de la table IfpIndexfichePaperless",
     *     statusCodes={
     *          200="Requête traitée avec succès",
     *          204="Requête traitée avec succès mais pas d’information à renvoyer",
     *          400="Un ou plusieurs paramètres sont manquants"
     *     }
     * )
     * @Post("/document/field/search")
     *
     * @return DocapostJsonResponse
     */
    public function postDocumentFieldSearch()
    {
        $params = $this->getContentParameters(
            true,
            true,
            [
                'field' => null,
                'distinct' => 'bool'
            ]
        );
        // Vérifie si un message a été positionné lors du traitement des paramètres
        if ($this->hasResponseMessage()) {
            return $this->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }
        return $this->export(
            $this->get('api.manager.document')
                ->retrieveFieldSearch($params)
        );
    }
}
