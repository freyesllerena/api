<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * DicDictionnaire
 *
 * @ORM\Table(name="dic_dictionnaire", uniqueConstraints={@ORM\UniqueConstraint(name="dic_support_code", columns={"dic_support", "dic_code"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\DicDictionnaireRepository")
 * @Gedmo\TranslationEntity(class="DicDictionnaireTranslation")
 */
class DicDictionnaire implements Translatable
{
    const DEFAULT_DIC_LOCALE = 'fr_FR';
    const DEFAULT_DIC_DEVICE = 'DES';

    public static $availableDevices = array('DES', 'MOB');

    /**
     * @var integer
     *
     * @ORM\Column(name="dic_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $dicId;

    /**
     * @var string
     *
     * @ORM\Column(name="dic_support", type="string", length=5, nullable=true)
     */
    private $dicSupport = self::DEFAULT_DIC_DEVICE;

    /**
     * @var string
     *
     * @ORM\Column(name="dic_code", type="string", length=50, nullable=true)
     */
    private $dicCode;

    /**
     * @var string
     *
     * @ORM\Column(name="dic_valeur", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $dicValeur;

    /**
     * @var string
     *
     * @ORM\Column(name="dic_old_variable", type="string", length=255, nullable=true)
     */
    private $dicOldVariable;

    /**
     * @var string
     *
     * @ORM\Column(name="dic_old_valeur", type="string", length=255, nullable=true)
     */
    private $dicOldValeur;

    /**
     * @var string
     *
     * @ORM\Column(name="dic_old_provenance", type="string", length=11, nullable=true)
     */
    private $dicOldProvenance;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="dic_created_at", type="datetime", nullable=true)
     */
    private $dicCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="dic_updated_at", type="datetime", nullable=true)
     */
    private $dicUpdatedAt;

    /**
     * Post locale
     * Used locale to override Translation listener's locale
     *
     * @Gedmo\Locale
     */
    protected $locale = self::DEFAULT_DIC_LOCALE;



    /**
     * Sets translatable locale
     *
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get dicId
     *
     * @return integer
     */
    public function getDicId()
    {
        return $this->dicId;
    }

    /**
     * Set dicSupport
     *
     * @param string $dicSupport
     *
     * @return DicDictionnaire
     */
    public function setDicSupport($dicSupport)
    {
        $this->dicSupport = $dicSupport;

        return $this;
    }

    /**
     * Get dicSupport
     *
     * @return string
     */
    public function getDicSupport()
    {
        return $this->dicSupport;
    }

    /**
     * Set dicCode
     *
     * @param string $dicCode
     *
     * @return DicDictionnaire
     */
    public function setDicCode($dicCode)
    {
        $this->dicCode = $dicCode;

        return $this;
    }

    /**
     * Get dicCode
     *
     * @return string
     */
    public function getDicCode()
    {
        return $this->dicCode;
    }

    /**
     * Set dicValeur
     *
     * @param string $dicValeur
     *
     * @return DicDictionnaire
     */
    public function setDicValeur($dicValeur)
    {
        $this->dicValeur = $dicValeur;

        return $this;
    }

    /**
     * Get dicValeur
     *
     * @return string
     */
    public function getDicValeur()
    {
        return $this->dicValeur;
    }

    /**
     * Set dicOldVariable
     *
     * @param string $dicOldVariable
     *
     * @return DicDictionnaire
     */
    public function setDicOldVariable($dicOldVariable)
    {
        $this->dicOldVariable = $dicOldVariable;

        return $this;
    }

    /**
     * Get dicOldVariable
     *
     * @return string
     */
    public function getDicOldVariable()
    {
        return $this->dicOldVariable;
    }

    /**
     * Set dicOldValeur
     *
     * @param string $dicOldValeur
     *
     * @return DicDictionnaire
     */
    public function setDicOldValeur($dicOldValeur)
    {
        $this->dicOldValeur = $dicOldValeur;

        return $this;
    }

    /**
     * Get dicOldValeur
     *
     * @return string
     */
    public function getDicOldValeur()
    {
        return $this->dicOldValeur;
    }

    /**
     * Set dicOldProvenance
     *
     * @param string $dicOldProvenance
     *
     * @return DicDictionnaire
     */
    public function setDicOldProvenance($dicOldProvenance)
    {
        $this->dicOldProvenance = $dicOldProvenance;

        return $this;
    }

    /**
     * Get dicOldProvenance
     *
     * @return string
     */
    public function getDicOldProvenance()
    {
        return $this->dicOldProvenance;
    }

    /**
     * Set dicCreatedAt
     *
     * @param \DateTime $dicCreatedAt
     *
     * @return DicDictionnaire
     */
    public function setDicCreatedAt($dicCreatedAt)
    {
        $this->dicCreatedAt = $dicCreatedAt;

        return $this;
    }

    /**
     * Get dicCreatedAt
     *
     * @return \DateTime
     */
    public function getDicCreatedAt()
    {
        return $this->dicCreatedAt;
    }

    /**
     * Set dicUpdatedAt
     *
     * @param \DateTime $dicUpdatedAt
     *
     * @return DicDictionnaire
     */
    public function setDicUpdatedAt($dicUpdatedAt)
    {
        $this->dicUpdatedAt = $dicUpdatedAt;

        return $this;
    }

    /**
     * Get dicUpdatedAt
     *
     * @return \DateTime
     */
    public function getDicUpdatedAt()
    {
        return $this->dicUpdatedAt;
    }
}