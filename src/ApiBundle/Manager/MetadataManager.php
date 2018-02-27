<?php

namespace ApiBundle\Manager;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use ApiBundle\Repository\IfpIndexfichePaperlessRepository;
use ApiBundle\Entity\IfpIndexfichePaperless;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MetadataManager
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

    /**
     * @var mixed
     */
    private $metadataDocuments;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->documentRepository = $this->entityManager->getRepository('ApiBundle:IfpIndexfichePaperless');
        $this->buildMetadataDocumentsList();
    }

    /**
     * Retourne la description des champs d'une table dans ApiBundle
     * @param $table
     * @return ClassMetadata
     */
    public function getTableMetadata($table)
    {
        return $this->entityManager->getClassMetadata('ApiBundle:' . $table);
    }

    /**
     * Retourne la liste des metadatas d'un document
     *
     * @return mixed
     */
    public function getMetadataDocumentsList()
    {
        return $this->metadataDocuments;
    }

    /**
     * Construit la liste des métadatas d'un document
     */
    private function buildMetadataDocumentsList()
    {
        $metadata = $this->container->getParameter('metadata_documents');
        foreach ($metadata as $field => $properties) {
            $this->metadataDocuments[IfpIndexfichePaperless::IFP_PREFIX . $field] = $properties;
        }
    }

    /**
     * Collection de champs à passer en select
     * Cette collection exclue les champs hidden dans sélection
     *
     * @param string $alias L'alias de la table
     *
     * @return string
     */
    public function collectionOfFieldsForSelect($alias = 'd')
    {
        $resultList = $this->collectionFields('IfpIndexfichePaperless');
        $resultString = $alias . '.' . implode(', ' . $alias . '.', $resultList);
        $ifpCodeDocument = "IDENTITY(".$alias.".ifpCodeDocument) AS ifpCodeDocument";
        $resultString = str_replace($alias.".ifpCodeDocument", $ifpCodeDocument, $resultString);

        return $resultString;
    }

    /**
     * Collection de champs actifs des tables dans ApiBundle
     * Cette collection exclue les champs hidden sur la liste
     *
     * @param $table
     * @return mixed
     */
    public function collectionFields($table)
    {
        if ('IfpIndexfichePaperless' === $table) {
            $keysMetadata = array_keys($this->metadataDocuments);
            $hiddenFields = $this->getHiddenFields();
            $resultList = array_diff($keysMetadata, $hiddenFields);
        } else {
            $resultList = array_keys($this->getTableMetadata($table)->fieldMappings);
        }

        return $resultList;
    }

    /**
     * Liste de champs Hidden (H)
     * @return array
     */
    public function getHiddenFields()
    {
        $hiddenMetadata = [];
        foreach ($this->metadataDocuments as $keyMetadata => $valueMetadata) {
            if (!empty($valueMetadata['hidden'])) {
                $hiddenMetadata[] = $keyMetadata;
            }
        }

        return $hiddenMetadata;
    }

    /**
     * Liste de champs commun dans IfpIndexfichePaperless et dans IinIdxIndiv
     * @return array
     */
    public function getCommonFieldsWithoutTrigram()
    {
        $commonFieldsMetadata = [];
        foreach ($this->metadataDocuments as $keyMetadata => $valueMetadata) {
            $metadataPrefix = substr($keyMetadata, 0, 3);
            if (array_key_exists('source', $valueMetadata) && !empty($valueMetadata['source']) && (
                array_key_exists('ifp', array_flip($valueMetadata['source'])) &&
                array_key_exists('iin', array_flip($valueMetadata['source']))) &&
                ('ifp' === $metadataPrefix || 'iin' === $metadataPrefix)
            ) {
                $commonFieldsMetadata[] = substr($keyMetadata, 3);
            } else {
                $commonFieldsMetadata[] = $keyMetadata;
            }
        }
        return $commonFieldsMetadata;
    }

    /**
     * Récupère la liste des propriétés des champs document
     *
     * @return string
     */
    public function getListFieldsAndPropertiesDocument()
    {
        // La liste des métadonnées est stockée dans memcache une fois pour toutes les instances
        $cacheKey = 'IfpIndexfichePaperless_fields_properties';
        $memcache = $this->container->get('memcache.default');
        if ($list = $memcache->get('API::' . $cacheKey)) {
            $list = json_decode($list, true);
        } else {
            $list = $this->documentRepository->customFieldsAndProperties($this->metadataDocuments);
            $list = $this->addUserPreferencesToMetadataList($list);
            $memcache->set(
                'API::' . $cacheKey,
                json_encode($list),
                0,
                86400
            );
        }
        return $list;
    }

    /**
     * Ajoute les informations des préférences utilisateurs dans la liste des métadatas
     *
     * @param $list
     * @return mixed
     */
    public function addUserPreferencesToMetadataList($list)
    {
        $userPreferences = $this->container->getParameter('user_preferences');
        unset($userPreferences['defaults']['dashboard']);

        foreach ($list['fields'] as $metadata => $values) {
            foreach ($userPreferences['defaults'] as $defaultName => $defaultValues) {
                $values['default'][$defaultName] = in_array($metadata, $defaultValues);
            }
            $list['fields'][$metadata] = $values;
        }

        return $list;
    }
}
