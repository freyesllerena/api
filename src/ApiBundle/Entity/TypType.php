<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumNumPopulationType;

/**
 * TypType
 *
 * @ORM\Table(
 *     name="typ_type", indexes={
 *      @ORM\Index(name="typ_type", columns={"typ_type"}),
 *      @ORM\Index(name="typ_id_niveau", columns={"typ_id_niveau"}),
 *      @ORM\Index(name="typ_num_ordre", columns={
 *          "typ_num_ordre_1", "typ_num_ordre_2", "typ_num_ordre_3", "typ_num_ordre_4"
 *      })
 *     }
 * )
 * 
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\TypTypeRepository")
 */
class TypType
{
    const DEFAULT_TYP_INDIVIDUEL = true;
    const DEFAULT_TYP_VIE_DUREE = 30;

    const TYP_MIXED = 'M';
    const TYP_INDIVIDUAL = 'I';
    const TYP_COLLECTIVE = 'C';

    const ERR_NOT_TRANSLATED = 'errTypTypeNotTranslated';
    const ERR_NOT_TRANSLATED_MESSAGE = 'errTypTypeNotTranslatedMessage';
    const ERR_IS_NOT_INDIVIDUAL = 'errTypTypeIsNotIndividual';
    const ERR_IS_NOT_COLLECTIVE = 'errTypTypeIsNotCollective';
    const ERR_LIST_EMPTY = 'errTypTypeListEmpty';
    const ERR_DOES_NOT_EXIST = 'errTypTypeDoesNotExist';

    const DOCUMENT_TYPE_INDIVIDUAL = '1';
    const DOCUMENT_TYPE_COLLECTIVE = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="typ_code", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $typCode;

    /**
     * @var string
     *
     * @ORM\Column(name="typ_code_adp", type="string", length=10, nullable=false)
     */
    private $typCodeAdp;

    /**
     * @var string
     *
     * @ORM\Column(name="typ_id_niveau", type="string", length=5, nullable=false)
     */
    private $typIdNiveau;

    /**
     * @var integer
     *
     * @ORM\Column(name="typ_type", type="smallint", nullable=false)
     */
    private $typType = EnumNumPopulationType::ONE_NUM_POPULATION;

    /**
     * @var boolean
     *
     * @ORM\Column(name="typ_individuel", type="boolean", nullable=false)
     */
    private $typIndividuel = self::DEFAULT_TYP_INDIVIDUEL;

    /**
     * @var integer
     *
     * @ORM\Column(name="typ_vie_duree", type="smallint", nullable=false)
     */
    private $typVieDuree = self::DEFAULT_TYP_VIE_DUREE;

    /**
     * @var integer
     *
     * @ORM\Column(name="typ_num_ordre_1", type="smallint", nullable=false)
     */
    private $typNumOrdre1;

    /**
     * @var integer
     *
     * @ORM\Column(name="typ_num_ordre_2", type="smallint", nullable=false)
     */
    private $typNumOrdre2;

    /**
     * @var integer
     *
     * @ORM\Column(name="typ_num_ordre_3", type="smallint", nullable=false)
     */
    private $typNumOrdre3;

    /**
     * @var integer
     *
     * @ORM\Column(name="typ_num_ordre_4", type="smallint", nullable=false)
     */
    private $typNumOrdre4;

    /**
     * @var string
     *
     * @ORM\Column(name="typ_date_effet", type="string", length=40, nullable=false)
     */
    private $typDateEffet;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="typ_created_at", type="datetime", nullable=true)
     */
    private $typCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="typ_updated_at", type="datetime", nullable=true)
     */
    private $typUpdatedAt;



    /**
     * Set typCode
     *
     * @param string $typCode
     *
     * @return TypType
     */
    public function setTypCode($typCode)
    {
        $this->typCode = $typCode;

        return $this;
    }

    /**
     * Get typCode
     *
     * @return string
     */
    public function getTypCode()
    {
        return $this->typCode;
    }

    /**
     * Set typCodeAdp
     *
     * @param string $typCodeAdp
     *
     * @return TypType
     */
    public function setTypCodeAdp($typCodeAdp)
    {
        $this->typCodeAdp = $typCodeAdp;

        return $this;
    }

    /**
     * Get typCodeAdp
     *
     * @return string
     */
    public function getTypCodeAdp()
    {
        return $this->typCodeAdp;
    }

    /**
     * Set typIdNiveau
     *
     * @param string $typIdNiveau
     *
     * @return TypType
     */
    public function setTypIdNiveau($typIdNiveau)
    {
        $this->typIdNiveau = $typIdNiveau;

        return $this;
    }

    /**
     * Get typIdNiveau
     *
     * @return string
     */
    public function getTypIdNiveau()
    {
        return $this->typIdNiveau;
    }

    /**
     * Set typType
     *
     * @param integer $typType
     *
     * @return TypType
     */
    public function setTypType($typType)
    {
        $this->typType = $typType;

        return $this;
    }

    /**
     * Get typType
     *
     * @return integer
     */
    public function getTypType()
    {
        return $this->typType;
    }

    /**
     * Set typIndividuel
     *
     * @param boolean $typIndividuel
     *
     * @return TypType
     */
    public function setTypIndividuel($typIndividuel)
    {
        $this->typIndividuel = $typIndividuel;

        return $this;
    }

    /**
     * Get typIndividuel
     *
     * @return boolean
     */
    public function isTypIndividuel()
    {
        return $this->typIndividuel;
    }

    /**
     * Set typVieDuree
     *
     * @param integer $typVieDuree
     *
     * @return TypType
     */
    public function setTypVieDuree($typVieDuree)
    {
        $this->typVieDuree = $typVieDuree;

        return $this;
    }

    /**
     * Get typVieDuree
     *
     * @return integer
     */
    public function getTypVieDuree()
    {
        return $this->typVieDuree;
    }

    /**
     * Set typNumOrdre1
     *
     * @param integer $typNumOrdre1
     *
     * @return TypType
     */
    public function setTypNumOrdre1($typNumOrdre1)
    {
        $this->typNumOrdre1 = $typNumOrdre1;

        return $this;
    }

    /**
     * Get typNumOrdre1
     *
     * @return integer
     */
    public function getTypNumOrdre1()
    {
        return $this->typNumOrdre1;
    }

    /**
     * Set typNumOrdre2
     *
     * @param integer $typNumOrdre2
     *
     * @return TypType
     */
    public function setTypNumOrdre2($typNumOrdre2)
    {
        $this->typNumOrdre2 = $typNumOrdre2;

        return $this;
    }

    /**
     * Get typNumOrdre2
     *
     * @return integer
     */
    public function getTypNumOrdre2()
    {
        return $this->typNumOrdre2;
    }

    /**
     * Set typNumOrdre3
     *
     * @param integer $typNumOrdre3
     *
     * @return TypType
     */
    public function setTypNumOrdre3($typNumOrdre3)
    {
        $this->typNumOrdre3 = $typNumOrdre3;

        return $this;
    }

    /**
     * Get typNumOrdre3
     *
     * @return integer
     */
    public function getTypNumOrdre3()
    {
        return $this->typNumOrdre3;
    }

    /**
     * Set typNumOrdre4
     *
     * @param integer $typNumOrdre4
     *
     * @return TypType
     */
    public function setTypNumOrdre4($typNumOrdre4)
    {
        $this->typNumOrdre4 = $typNumOrdre4;

        return $this;
    }

    /**
     * Get typNumOrdre4
     *
     * @return integer
     */
    public function getTypNumOrdre4()
    {
        return $this->typNumOrdre4;
    }

    /**
     * Set typDateEffet
     *
     * @param string $typDateEffet
     *
     * @return TypType
     */
    public function setTypDateEffet($typDateEffet)
    {
        $this->typDateEffet = $typDateEffet;

        return $this;
    }

    /**
     * Get typDateEffet
     *
     * @return string
     */
    public function getTypDateEffet()
    {
        return $this->typDateEffet;
    }

    /**
     * Set typCreatedAt
     *
     * @param \DateTime $typCreatedAt
     *
     * @return TypType
     */
    public function setTypCreatedAt($typCreatedAt)
    {
        $this->typCreatedAt = $typCreatedAt;

        return $this;
    }

    /**
     * Get typCreatedAt
     *
     * @return \DateTime
     */
    public function getTypCreatedAt()
    {
        return $this->typCreatedAt;
    }

    /**
     * Set typUpdatedAt
     *
     * @param \DateTime $typUpdatedAt
     *
     * @return TypType
     */
    public function setTypUpdatedAt($typUpdatedAt)
    {
        $this->typUpdatedAt = $typUpdatedAt;

        return $this;
    }

    /**
     * Get typUpdatedAt
     *
     * @return \DateTime
     */
    public function getTypUpdatedAt()
    {
        return $this->typUpdatedAt;
    }

    /**
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->typCode;
    }
}
