<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelStockageType;

/**
 * StoStockage
 *
 * @ORM\Table(name="sto_stockage", indexes={@ORM\Index(name="sto_volume", columns={"sto_volume"}), @ORM\Index(name="sto_code_client", columns={"sto_code_client"}), @ORM\Index(name="sto_code_application", columns={"sto_code_application"})})
 * @ORM\Entity
 */
class StoStockage
{
    const DEFAULT_STO_USE_VIS = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="sto_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $stoId;

    /**
     * @var string
     *
     * @ORM\Column(name="sto_volume", type="string", length=20, nullable=true)
     */
    private $stoVolume;

    /**
     * @var string
     *
     * @ORM\Column(name="sto_libelle", type="string", length=50, nullable=true)
     */
    private $stoLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="sto_chemin", type="string", length=255, nullable=true)
     */
    private $stoChemin;

    /**
     * @var string
     *
     * @ORM\Column(name="sto_code_client", type="string", length=4, nullable=true)
     */
    private $stoCodeClient;

    /**
     * @var string
     *
     * @ORM\Column(name="sto_code_application", type="string", length=4, nullable=true)
     */
    private $stoCodeApplication;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sto_use_vis", type="boolean", nullable=false)
     */
    private $stoUseVis = self::DEFAULT_STO_USE_VIS;

    /**
     * @var string
     *
     * @ORM\Column(name="sto_type", type="string", length=10, nullable=false)
     */
    private $stoType = EnumLabelStockageType::STANDARD_STOCKAGE;

    /**
     * @var string
     *
     * @ORM\Column(name="sto_cfec_base", type="string", length=255, nullable=false)
     */
    private $stoCfecBase;

    /**
     * @var string
     *
     * @ORM\Column(name="sto_cfec_cert", type="string", length=100, nullable=false)
     */
    private $stoCfecCert;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="sto_created_at", type="datetime", nullable=true)
     */
    private $stoCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="sto_updated_at", type="datetime", nullable=true)
     */
    private $stoUpdatedAt;


    /**
     * Get stoId
     *
     * @return integer
     */
    public function getStoId()
    {
        return $this->stoId;
    }

    /**
     * Set stoVolume
     *
     * @param string $stoVolume
     *
     * @return StoStockage
     */
    public function setStoVolume($stoVolume)
    {
        $this->stoVolume = $stoVolume;

        return $this;
    }

    /**
     * Get stoVolume
     *
     * @return string
     */
    public function getStoVolume()
    {
        return $this->stoVolume;
    }

    /**
     * Set stoLibelle
     *
     * @param string $stoLibelle
     *
     * @return StoStockage
     */
    public function setStoLibelle($stoLibelle)
    {
        $this->stoLibelle = $stoLibelle;

        return $this;
    }

    /**
     * Get stoLibelle
     *
     * @return string
     */
    public function getStoLibelle()
    {
        return $this->stoLibelle;
    }

    /**
     * Set stoChemin
     *
     * @param string $stoChemin
     *
     * @return StoStockage
     */
    public function setStoChemin($stoChemin)
    {
        $this->stoChemin = $stoChemin;

        return $this;
    }

    /**
     * Get stoChemin
     *
     * @return string
     */
    public function getStoChemin()
    {
        return $this->stoChemin;
    }

    /**
     * Set stoCodeClient
     *
     * @param string $stoCodeClient
     *
     * @return StoStockage
     */
    public function setStoCodeClient($stoCodeClient)
    {
        $this->stoCodeClient = $stoCodeClient;

        return $this;
    }

    /**
     * Get stoCodeClient
     *
     * @return string
     */
    public function getStoCodeClient()
    {
        return $this->stoCodeClient;
    }

    /**
     * Set stoCodeApplication
     *
     * @param string $stoCodeApplication
     *
     * @return StoStockage
     */
    public function setStoCodeApplication($stoCodeApplication)
    {
        $this->stoCodeApplication = $stoCodeApplication;

        return $this;
    }

    /**
     * Get stoCodeApplication
     *
     * @return string
     */
    public function getStoCodeApplication()
    {
        return $this->stoCodeApplication;
    }

    /**
     * Set stoUseVis
     *
     * @param boolean $stoUseVis
     *
     * @return StoStockage
     */
    public function setStoUseVis($stoUseVis)
    {
        $this->stoUseVis = $stoUseVis;

        return $this;
    }

    /**
     * Get stoUseVis
     *
     * @return boolean
     */
    public function isStoUseVis()
    {
        return $this->stoUseVis;
    }

    /**
     * Set stoType
     *
     * @param string $stoType
     *
     * @return StoStockage
     */
    public function setStoType($stoType)
    {
        $this->stoType = $stoType;

        return $this;
    }

    /**
     * Get stoType
     *
     * @return string
     */
    public function getStoType()
    {
        return $this->stoType;
    }

    /**
     * Set stoCfecBase
     *
     * @param string $stoCfecBase
     *
     * @return StoStockage
     */
    public function setStoCfecBase($stoCfecBase)
    {
        $this->stoCfecBase = $stoCfecBase;

        return $this;
    }

    /**
     * Get stoCfecBase
     *
     * @return string
     */
    public function getStoCfecBase()
    {
        return $this->stoCfecBase;
    }

    /**
     * Set stoCfecCert
     *
     * @param string $stoCfecCert
     *
     * @return StoStockage
     */
    public function setStoCfecCert($stoCfecCert)
    {
        $this->stoCfecCert = $stoCfecCert;

        return $this;
    }

    /**
     * Get stoCfecCert
     *
     * @return string
     */
    public function getStoCfecCert()
    {
        return $this->stoCfecCert;
    }

    /**
     * Set stoCreatedAt
     *
     * @param \DateTime $stoCreatedAt
     *
     * @return StoStockage
     */
    public function setStoCreatedAt($stoCreatedAt)
    {
        $this->stoCreatedAt = $stoCreatedAt;

        return $this;
    }

    /**
     * Get stoCreatedAt
     *
     * @return \DateTime
     */
    public function getStoCreatedAt()
    {
        return $this->stoCreatedAt;
    }

    /**
     * Set stoUpdatedAt
     *
     * @param \DateTime $stoUpdatedAt
     *
     * @return StoStockage
     */
    public function setStoUpdatedAt($stoUpdatedAt)
    {
        $this->stoUpdatedAt = $stoUpdatedAt;

        return $this;
    }

    /**
     * Get stoUpdatedAt
     *
     * @return \DateTime
     */
    public function getStoUpdatedAt()
    {
        return $this->stoUpdatedAt;
    }
}
