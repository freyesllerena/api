<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProProfil
 *
 * @ORM\Table(name="pro_profil")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ProProfilRepository")
 */
class ProProfil
{
    const DEFAULT_PRO_GENERIC = false;
    const DEFAULT_PRO_ADP = false;
    const DEFAULT_PRO_ARC = false;
    const DEFAULT_PRO_ORDER = true;
    const DEFAULT_PRO_IMPORT = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="pro_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $proId;

    /**
     * @var string
     *
     * @ORM\Column(name="pro_libelle", type="string", length=100, nullable=false)
     */
    private $proLibelle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pro_order", type="boolean", nullable=false)
     */
    private $proOrder = self::DEFAULT_PRO_ORDER;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pro_import", type="boolean", nullable=false)
     */
    private $proImport = self::DEFAULT_PRO_IMPORT;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pro_generic", type="boolean", nullable=true)
     */
    private $proGeneric = self::DEFAULT_PRO_GENERIC;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pro_adp", type="boolean", nullable=true)
     */
    private $proAdp = self::DEFAULT_PRO_ADP;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pro_arc", type="boolean", nullable=true)
     */
    private $proArc = self::DEFAULT_PRO_ARC;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="pro_created_at", type="datetime", nullable=true)
     */
    private $proCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="pro_updated_at", type="datetime", nullable=true)
     */
    private $proUpdatedAt;


    /**
     * Get proId
     *
     * @return integer
     */
    public function getProId()
    {
        return $this->proId;
    }

    /**
     * Set proLibelle
     *
     * @param string $proLibelle
     *
     * @return ProProfil
     */
    public function setProLibelle($proLibelle)
    {
        $this->proLibelle = $proLibelle;

        return $this;
    }

    /**
     * Get proLibelle
     *
     * @return string
     */
    public function getProLibelle()
    {
        return $this->proLibelle;
    }

    /**
     * Set proOrder
     *
     * @param boolean $proOrder
     *
     * @return ProProfil
     */
    public function setProOrder($proOrder)
    {
        $this->proOrder = $proOrder;

        return $this;
    }

    /**
     * Get proOrder
     *
     * @return boolean
     */
    public function isProOrder()
    {
        return $this->proOrder;
    }

    /**
     * Set proImport
     *
     * @param boolean $proImport
     *
     * @return ProProfil
     */
    public function setProImport($proImport)
    {
        $this->proImport = $proImport;

        return $this;
    }

    /**
     * Get proImport
     *
     * @return boolean
     */
    public function isProImport()
    {
        return $this->proImport;
    }

    /**
     * Set proGeneric
     *
     * @param boolean $proGeneric
     *
     * @return ProProfil
     */
    public function setProGeneric($proGeneric)
    {
        $this->proGeneric = $proGeneric;

        return $this;
    }

    /**
     * Get proGeneric
     *
     * @return boolean
     */
    public function isProGeneric()
    {
        return $this->proGeneric;
    }

    /**
     * Set proAdp
     *
     * @param boolean $proAdp
     *
     * @return ProProfil
     */
    public function setProAdp($proAdp)
    {
        $this->proAdp = $proAdp;

        return $this;
    }

    /**
     * Get proAdp
     *
     * @return boolean
     */
    public function isProAdp()
    {
        return $this->proAdp;
    }

    /**
     * Set proArc
     *
     * @param boolean $proArc
     *
     * @return ProProfil
     */
    public function setProArc($proArc)
    {
        $this->proArc = $proArc;

        return $this;
    }

    /**
     * Get proArc
     *
     * @return boolean
     */
    public function isProArc()
    {
        return $this->proArc;
    }

    /**
     * Set proCreatedAt
     *
     * @param \DateTime $proCreatedAt
     *
     * @return ProProfil
     */
    public function setProCreatedAt($proCreatedAt)
    {
        $this->proCreatedAt = $proCreatedAt;

        return $this;
    }

    /**
     * Get proCreatedAt
     *
     * @return \DateTime
     */
    public function getProCreatedAt()
    {
        return $this->proCreatedAt;
    }

    /**
     * Set proUpdatedAt
     *
     * @param \DateTime $proUpdatedAt
     *
     * @return ProProfil
     */
    public function setProUpdatedAt($proUpdatedAt)
    {
        $this->proUpdatedAt = $proUpdatedAt;

        return $this;
    }

    /**
     * Get proUpdatedAt
     *
     * @return \DateTime
     */
    public function getProUpdatedAt()
    {
        return $this->proUpdatedAt;
    }
}
