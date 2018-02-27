<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository
{
    const KEY_META_FIELD_HIDDEN = 'fieldHidden';
    const KEY_META_FIELD_EDITABLE = 'fieldEditable';
    const KEY_META_FIELD_SELECTED = 'fieldSelected';
    const KEY_META_FIELD_CATEGORY = 'fieldCategory';
    const KEY_META_FIELD_NAME = 'fieldName';
    const KEY_META_TYPE = 'type';
    const KEY_META_LENGTH = 'length';
    const KEY_META_NULLABLE = 'nullable';
    const KEY_META_FIELD_AUTOCOMPLETE = 'autocomplete';
    const BUNDLE_NAME = 'ApiBundle';
    const TABLE = 'table';
    const FIELDS = 'fields';

    /**
     * Renvoit le mapping entre les colonnes de la table et les propriétés de l'entité
     *
     * @return array
     */
    public function getColumnMapping()
    {
        $results = array();
        $fieldMappings = $this->getClassMetadata()->fieldMappings;
        foreach ($fieldMappings as $data) {
            $key = strtoupper(substr($data['columnName'], 4));
            $results[$key] = $data['fieldName'];
        }
        return $results;
    }

    /**
     * Liste de champs et propiétés simplifiés
     *
     * @param $entityName
     * @param $hiddenFields
     *
     * @return string
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function simpleListFieldsAndProperties($entityName, $hiddenFields)
    {

        $metadata = $this->_em->getClassMetadata('ApiBundle:UsrUsers');
        // $metadata->associationMappings

        $metadata = $this->getEntityManager()->getClassMetadata(self::BUNDLE_NAME . ':' . $entityName);
        $fieldsAndSize = [self::TABLE => $entityName];

        foreach ($metadata->fieldMappings as $keysMeta => $valuesMeta) {
            $fields = [];
            foreach ($valuesMeta as $keyMeta => $valueMeta) {
                if (in_array($keysMeta, $hiddenFields)) {
                    $fields[self::KEY_META_FIELD_HIDDEN] = true;
                } else {
                    $fields[self::KEY_META_FIELD_HIDDEN] = false;
                }
                if (in_array($keyMeta, [self::KEY_META_TYPE, self::KEY_META_LENGTH, self::KEY_META_NULLABLE])) {
                    $fields[$keyMeta] = $valueMeta;
                }
            }
            $fieldsAndSize[self::FIELDS][] = [$valuesMeta[self::KEY_META_FIELD_NAME] => $fields];
        }

        return $fieldsAndSize;
    }
}
