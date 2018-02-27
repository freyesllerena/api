<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Table(name="dic_dictionnaire_translations", indexes={
 *      @ORM\Index(name="dic_dictionnaire_translations_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class DicDictionnaireTranslation extends AbstractTranslation
{
    /**
     * All required columns are mapped through inherited superclass
     */
}