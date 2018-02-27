<?php

namespace ApiBundle\Manager;

use ApiBundle\DocapostJsonResponse;
use ApiBundle\Repository\RidRefIdRepository;
use ApiBundle\Repository\UsrUsersRepository;
use Doctrine\ORM\EntityManager;
use ApiBundle\Repository\IfpIndexfichePaperlessRepository;
use ApiBundle\Helper\Tools;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\IinIdxIndiv;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ApiBundle\Controller\DocapostController;

class AutocompleteManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var IfpIndexfichePaperlessRepository
     */
    private $documentRepository;

    /**
     * @var ContainerInterface
     */
    private $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->documentRepository = $this->entityManager->getRepository('ApiBundle:IfpIndexfichePaperless');
    }

    /**
     * Retourne la liste des champs sur lesquels l'autocomplétion est possible
     * @param $source
     * @return \stdClass
     */
    public function getSearchableFields($source)
    {
        $metadataManager = $this->container->get('api.manager.metadata');
        // liste de tous les champs et contextes recherchés
        $searchable = new \stdClass();
        if (array_key_exists('iin', $source) && array_key_exists('ifp', $source)) {
            $searchable->commonFields = $metadataManager->getCommonFieldsWithoutTrigram();
        } elseif (array_key_exists('iin', $source) && !array_key_exists('ifp', $source)) {
            $collectionFieldsIin = $metadataManager->collectionFields('IinIdxIndiv');
            $searchable->commonFields = $this->fieldsWithoutTablePrefix($collectionFieldsIin, 'iin');
        } elseif (!array_key_exists('iin', $source) && array_key_exists('ifp', $source)) {
            $collectionFieldsIfp = $metadataManager->collectionFields('IfpIndexfichePaperless');
            $searchable->commonFields = $this->fieldsWithoutTablePrefix($collectionFieldsIfp, 'ifp');
        }
        $contextAutocomplete = $this->container->getParameter('context_autocomplete');

        $searchable->contextDefault = $contextAutocomplete['default'];
        $searchable->contextMatriculeRh = $contextAutocomplete['matriculeRh'];
        $searchable->contextSociete = $contextAutocomplete['societe'];
        $searchable->contextEtablissement = $contextAutocomplete['etablissement'];
        $searchable->contextDates = $contextAutocomplete['date'];

        return $searchable;
    }

    /**
     * Champs de recherche de l'utilisateur
     * @return array|object
     */
    public function getSearchableFieldsUser()
    {
        $searcheableFields = [
            'usrNom',
            'usrPrenom',
            'usrAdresseMail',
            'usrLogin'
        ];
        return $searcheableFields;
    }

    /**
     * Resultat pour l'autocompletion
     *
     * @param object $params
     * @param object $listFieldsSearch
     * @param array $source
     * @return array|bool|null
     */
    public function getResultAutocomplete($params, $source, $listFieldsSearch = null)
    {
        if (is_null($listFieldsSearch)) {
            return false;
        }
        $codeClient = array_flip($params->referencialPac);
        $patternValue = null;
        $resultIin = [];
        $resultIfp = [];
        $defaultRenderFields = $listFieldsSearch->contextDefault;
        $keysSource = array_flip($source);
        // Données actuelles
        $listIinFieldsSearch = $this->fieldsSearchInTable(
            IinIdxIndiv::IIN_PREFIX,
            'IinIdxIndiv',
            $listFieldsSearch->commonFields
        );
        // Données archivées
        $listIfpFieldsSearch = $this->fieldsSearchInTable(
            IfpIndexfichePaperless::IFP_PREFIX,
            'IfpIndexfichePaperless',
            $listFieldsSearch->commonFields
        );
        if (!is_object($params) || !array_key_exists($params->main->code, $listIfpFieldsSearch)) {
            return null;
        }
        $patternValue = $params->main->value;
        // Paramètres fields des documents archivés (IfpIndexfichePaperless)
        $paramsfieldsIfp = (array)$params->fields;
        // Traitement des paramètres fields des documents actuels (IinIdxIndiv)
        $paramsfieldsIin = [];
        $keyfields = array_keys($paramsfieldsIfp);
        foreach ($keyfields as $keyField) {
            $strField = explode(IfpIndexfichePaperless::IFP_PREFIX, $keyField);
            $fieldIin = IinIdxIndiv::IIN_PREFIX.$strField[1];
            if (array_key_exists($fieldIin, $listIinFieldsSearch)) {
                $paramsfieldsIin[$fieldIin] = $paramsfieldsIfp{$keyField};
            }
        }
        $paramStart = $params->start;
        $paramLimit = $params->limit;
        $strMainCode = explode(IfpIndexfichePaperless::IFP_PREFIX, $params->main->code);
        $communMainCode = $strMainCode[1];
        // Recherche sur IinIdxIndiv (documents actuels)
        if (array_key_exists(IinIdxIndiv::IIN_PREFIX, $keysSource)) {
            $searchOnSourceParams = (object)[
                "prefix" => IinIdxIndiv::IIN_PREFIX,
                "listFieldsSearch" => $listFieldsSearch,
                "defaultRenderFields" => $defaultRenderFields,
                "codeClient" => $codeClient,
                "patternValue" => $patternValue,
                "paramsfieldsSource" => $paramsfieldsIin,
                "communMainCode" => $communMainCode,
                "entitySource" => "ApiBundle\Entity\IinIdxIndiv",
                "paramStart" => $paramStart,
                "paramLimit" => $paramLimit
            ];
            $resultIin = $this->searchOnSource($searchOnSourceParams);
        }
        // Recherche sur IfpIndexfichePaperless (documents archivés)
        if (array_key_exists(IfpIndexfichePaperless::IFP_PREFIX, $keysSource)) {
            $searchOnSourceParams = (object)[
                "prefix" => IfpIndexfichePaperless::IFP_PREFIX,
                "listFieldsSearch" => $listFieldsSearch,
                "defaultRenderFields" => $defaultRenderFields,
                "codeClient" => $codeClient,
                "patternValue" => $patternValue,
                "paramsfieldsSource" => $paramsfieldsIfp,
                "communMainCode" => $communMainCode,
                "entitySource" => "ApiBundle\Entity\IfpIndexfichePaperless",
                "paramStart" => $paramStart,
                "paramLimit" => $paramLimit
            ];
            $preResultIfp = $this->searchOnSource($searchOnSourceParams);
            $resultIfp = $this->findEmptyMatriculeRh($preResultIfp);
        }

        // Union et unicité de données
        if (array_key_exists(IinIdxIndiv::IIN_PREFIX, $keysSource) &&
            array_key_exists(IfpIndexfichePaperless::IFP_PREFIX, $keysSource)) {
            $mergedIfpAndIin = array_merge((array)$resultIfp, (array)$resultIin);
            $uniqueArrayIfpAndIin = Tools::uniqueMultidimArray((array)$mergedIfpAndIin, 'context');
        } elseif (!array_key_exists(IinIdxIndiv::IIN_PREFIX, $keysSource) &&
                   array_key_exists(IfpIndexfichePaperless::IFP_PREFIX, $keysSource)) {
            $uniqueArrayIfpAndIin = Tools::uniqueMultidimArray((array)$resultIfp, 'context');
        } elseif (array_key_exists(IinIdxIndiv::IIN_PREFIX, $keysSource) &&
                  !array_key_exists(IfpIndexfichePaperless::IFP_PREFIX, $keysSource)) {
            $uniqueArrayIfpAndIin = Tools::uniqueMultidimArray((array)$resultIin, 'context');
        } else {
            $uniqueArrayIfpAndIin = [];
        }

        return $uniqueArrayIfpAndIin;
    }

    /**
     * Fields search in table
     * @param $prefix
     * @param $table
     * @param array $listFieldsSearch
     * @return mixed
     */
    public function fieldsSearchInTable($prefix, $table, $listFieldsSearch = [])
    {
        // Liste de tous les champs d'une table
        $meta = $this->entityManager->getClassMetadata('ApiBundle:'.$table);
        $fieldsInTable = array_keys($meta->fieldMappings);
        // Liste des champs de recherche par autocompletion
        foreach ($listFieldsSearch as &$value) {
            $value = $prefix.ucfirst($value);
        }
        $result = array_intersect($fieldsInTable, $listFieldsSearch);

        return array_flip($result);
    }

    /**
     * Champs sans le prefixe du nom de sa table
     * @param $collectionFields
     * @param $prefix
     * @return mixed
     */
    private function fieldsWithoutTablePrefix($collectionFields, $prefix)
    {
        // Liste des champs de recherche par autocompletion
        foreach ($collectionFields as &$valueFields) {
            $strKey = explode($prefix, $valueFields);
            $valueFields = ucfirst($strKey[1]);
        }

        return $collectionFields;
    }

    /**
     * Recherche sur une source
     * @param $searchOnSource
     * @return array|null
     */
    public function searchOnSource($searchOnSource)
    {
        $prefix = $searchOnSource->prefix;
        $listFieldsSearch = $searchOnSource->listFieldsSearch;
        $defaultFieldsSearch = [$searchOnSource->communMainCode];
        $patternCodeSource = $prefix.$searchOnSource->communMainCode;
        $fieldGroupToSearch = [$searchOnSource->communMainCode];
        $fieldsForContext = ', '.$prefix.'.'.$patternCodeSource.' AS context';
        // Recherche sur context MatriculeRH
        if ('IdNumMatriculeRh' === $searchOnSource->communMainCode) {
            $defaultFieldsSearch = $listFieldsSearch->contextMatriculeRh;
            $fieldGroupToSearch = $listFieldsSearch->contextMatriculeRh;
            $fieldsForContext = $this->buildConcatContextFields(
                $prefix,
                [$prefix.$searchOnSource->communMainCode,
                $prefix.'IdNomSalarie',
                $prefix.'IdPrenomSalarie',
                $prefix.'IdNomJeuneFilleSalarie']
            );
        }
        // Recherche sur context société
        if (array_key_exists($searchOnSource->communMainCode, array_flip($listFieldsSearch->contextSociete))) {
            $searchOnSource->defaultRenderFields = [];
            $defaultFieldsSearch = ['IdNumSiret', 'IdNomSociete', 'IdCodeSociete'];
            $fieldGroupToSearch = $listFieldsSearch->contextSociete;
            $fieldsForContext = $this->buildConcatContextFields(
                $prefix,
                [$prefix.'IdNumSiret', $prefix.'IdNomSociete', $prefix.'IdCodeSociete']
            );
        }
        // Recherche sur context Etablissement
        if (array_key_exists($searchOnSource->communMainCode, array_flip($listFieldsSearch->contextEtablissement))) {
            $defaultFieldsSearch = $listFieldsSearch->contextEtablissement;
            $fieldsForContext = $this->buildConcatContextFields(
                $prefix,
                [$prefix.'IdCodeEtablissement', $prefix.'IdLibEtablissement', $prefix.'IdCodeSociete']
            );
            $fieldGroupToSearch = $listFieldsSearch->contextEtablissement;
        }

        $defaultFields = [];
        if (0<count($defaultFieldsSearch) && 0<count($searchOnSource->defaultRenderFields)) {
            $defaultFields = array_merge($defaultFieldsSearch, $searchOnSource->defaultRenderFields);
        } elseif (0===count($defaultFieldsSearch) && 0<count($searchOnSource->defaultRenderFields)) {
            $defaultFields = $searchOnSource->defaultRenderFields;
        } elseif (0<count($defaultFieldsSearch) && 0===count($searchOnSource->defaultRenderFields)) {
            $defaultFields = $defaultFieldsSearch;
        }

        // Liste des Champs pour la clause Select et conditions limitant la recherche d'information
        $fieldsForSelect = $this->fieldsInQuerySelect(
            $prefix,
            $defaultFields,
            $searchOnSource,
            $patternCodeSource,
            $fieldsForContext
        );

        return $this->documentRepository->queryForAutocomplete(
            $fieldsForSelect->fieldInSelect,
            $listFieldsSearch,
            $searchOnSource->entitySource,
            $fieldGroupToSearch,
            $fieldsForSelect->paramsSearch
        );
    }

    /**
     * Vérifie si le code PAC est valide pour l'instance courante
     * @param $objParams
     * @return bool
     */
    public function checkPac($objParams)
    {
        /* @var $referentialPac RidRefIdRepository */
        $referentialPac = $this->entityManager
            ->getRepository('ApiBundle:RidRefId');
        $isExist = false;
        $listPacInParams = !empty(array_flip($objParams->referencialPac))? array_flip($objParams->referencialPac) : [];
        if (1===count($listPacInParams)) {
            $isExist = $referentialPac->isPacExists(array_flip($listPacInParams)[0]);
        } elseif (count($listPacInParams)>1) {
            foreach (array_flip($listPacInParams) as $pacParam) {
                if ($referentialPac->isPacExists($pacParam)) {
                    $isExist = $referentialPac->isPacExists($pacParam);
                }
            }
        }
        if ($isExist) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Liste les champs ou calculs à afficher ou restituer
     * Et, conditions limitant la recherche d'information
     * @param $prefix
     * @param $defaultFields
     * @param $searchOnSource
     * @param $patternCodeSource
     * @param $fieldsForContext
     * @return object
     */
    private function fieldsInQuerySelect(
        $prefix,
        $defaultFields,
        $searchOnSource,
        $patternCodeSource,
        $fieldsForContext
    ) {
        $fieldsForSelect = $prefix . '.' .
            $prefix . implode(', ' . $prefix . '.' . $prefix, $defaultFields);
        $fieldsForSelect .= $fieldsForContext;
        $paramsSearch = (object)[
            'client' => [
                'column' => $prefix.'CodeClient',
                'value' => $searchOnSource->codeClient
            ],
            'main' => [
                'column' => $patternCodeSource,
                'value' => $searchOnSource->patternValue
            ],
            'prefix' => $prefix,
            'fields' => $searchOnSource->paramsfieldsSource,
            'start' => $searchOnSource->paramStart,
            'limit' => $searchOnSource->paramLimit
        ];

        return (object)['fieldInSelect' => $fieldsForSelect, 'paramsSearch' => $paramsSearch];
    }

    /**
     * Champs clés de la recherche
     * @param $prefix
     * @param array $columns
     * @return string
     */
    public function buildConcatContextFields($prefix, $columns)
    {
        $concat = ", concat(";
        $separateur = "' '";
        $ifNullopen = "coalesce(";
        $ifNullclose = ", '')";
        foreach ($columns as $keyColumn => $valColumn) {
            if (0 === $keyColumn) {
                $concat .= $ifNullopen.$prefix . "." . $valColumn.$ifNullclose;
            } else {
                $concat .= ", " . $separateur. ", " . $ifNullopen.$prefix . "." . $valColumn.$ifNullclose;
            }
        }
        $concat .= ") AS context";
        return $concat;
    }

    /**
     * Execute la recherche pour l'autocomplétion et control les champs en sortie
     * @param object $objParams
     * @param object $searchable
     * @return array
     */
    public function autocompleteSearch($objParams, $searchable)
    {
        $resultQuery = $this->checkConfigFile($objParams, $searchable, $this->getSourceParams($objParams));
        $listContext = [];
        foreach ($resultQuery as $valuesQuery) {
            $renamesKeyIin = [];
            foreach ($valuesQuery as $keyData => $valueData) {
                if ($keyData === $objParams->main->code) {
                    $renamesKeyIin['code'] = $valueData;
                } elseif ($keyData === 'context') {
                    $renamesKeyIin['value'] = $valueData;
                }
                $strKey = explode('iin', $keyData);
                if (count($strKey)>1) {
                    $keyIfp = 'ifp'. $strKey[1];
                    if ($keyIfp === $objParams->main->code) {
                        $renamesKeyIin['code'] = $valueData;
                    } elseif ($keyIfp === 'context') {
                        $renamesKeyIin['value'] = $valueData;
                    }
                }
            }
            $listContext[] = $renamesKeyIin;
        }

        return $listContext;
    }

    /**
     * Execute la recherche pour l'autocomplétion et control les champs user en sortie
     * @param $objParams
     * @param $searchable
     * @param $context
     * @return array|null
     */
    public function autocompleteUserSearch($objParams, $searchable, $context)
    {
        /* @var $querySearch UsrUsersRepository */
        $querySearch = $this->entityManager
            ->getRepository('ApiBundle:usrUsers');
        $listContext = $querySearch->queryForAutocompleteUsers($objParams, $searchable, $context);
        
        return $listContext;
    }

    /**
     * Vérifie si le fichier de configuration des métadonnées est accessible
     * @param object $objParams
     * @param $searchable
     * @param array $source
     * @return DocapostJsonResponse
     */
    public function checkConfigFile($objParams, $searchable, $source)
    {
        $docaController = new DocapostController();
        if (!$resultQuery = $this
            ->getResultAutocomplete($objParams, $source, $searchable)
        ) {
            if (!empty(DocapostController::ERR_INTERNAL_ERROR)) {
                $docaController->addResponseMessage(DocapostController::ERR_INTERNAL_ERROR);
            } // fichier config manquant
            return $docaController->messageJsonResponse(DocapostController::MSG_LEVEL_ERROR);
        }

        return $resultQuery;
    }

    /**
     * Récupération de paramètres sources
     * @param object $objParams
     * @return array
     */
    public function getSourceParams($objParams)
    {
        $sourceParams = [];
        if ($objParams->actualData && $objParams->archivedData) {
            $sourceParams = ["iin", "ifp"];
        } elseif ($objParams->actualData) {
            $sourceParams = ["iin"];
        } elseif ($objParams->archivedData) {
            $sourceParams = ["ifp"];
        }
        return $sourceParams;
    }

    /**
     * Recherche du MatriculeRh vide
     * @param $result
     * @return array
     */
    public function findEmptyMatriculeRh($result)
    {
        $results = [];
        $managerDocument = $this->container->get('api.manager.document');
        foreach ($result as &$values) {
            if (empty($values['ifpIdNumMatriculeRh']) && isset($values['ifpIdNumMatriculeRh'])) {
                $values['ifpIdNumMatriculeRh'] = $managerDocument
                    ->findMatriculeRhInIfpOrInIin($values['ifpNumMatricule']);
                $results[] = $values;
            } else {
                $results[] = $values;
            }
        }
        return $results;
    }
}
