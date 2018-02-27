<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * AsoAlerteSecuriteOpn
 *
 * @ORM\Table(name="aso_alerte_securite_opn")
 * @ORM\Entity
 */
class AsoAlerteSecuriteOpn
{
    /**
     * @var integer
     *
     * @ORM\Column(name="aso_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $asoId;

    /**
     * @var string
     *
     * @ORM\Column(name="aso_referer", type="text", length=65535, nullable=false)
     */
    private $asoReferer;

    /**
     * @var string
     *
     * @ORM\Column(name="aso_machine", type="text", length=65535, nullable=false)
     */
    private $asoMachine;

    /**
     * @var string
     *
     * @ORM\Column(name="aso_request_method", type="text", length=65535, nullable=false)
     */
    private $asoRequestMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="aso_request_uri", type="text", length=65535, nullable=false)
     */
    private $asoRequestUri;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="aso_created_at", type="datetime", nullable=true)
     */
    private $asoCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="aso_updated_at", type="datetime", nullable=true)
     */
    private $asoUpdatedAt;


    /**
     * Get asoId
     *
     * @return integer
     */
    public function getAsoId()
    {
        return $this->asoId;
    }

    /**
     * Set asoReferer
     *
     * @param string $asoReferer
     *
     * @return AsoAlerteSecuriteOpn
     */
    public function setAsoReferer($asoReferer)
    {
        $this->asoReferer = $asoReferer;

        return $this;
    }

    /**
     * Get asoReferer
     *
     * @return string
     */
    public function getAsoReferer()
    {
        return $this->asoReferer;
    }

    /**
     * Set asoMachine
     *
     * @param string $asoMachine
     *
     * @return AsoAlerteSecuriteOpn
     */
    public function setAsoMachine($asoMachine)
    {
        $this->asoMachine = $asoMachine;

        return $this;
    }

    /**
     * Get asoMachine
     *
     * @return string
     */
    public function getAsoMachine()
    {
        return $this->asoMachine;
    }

    /**
     * Set asoRequestMethod
     *
     * @param string $asoRequestMethod
     *
     * @return AsoAlerteSecuriteOpn
     */
    public function setAsoRequestMethod($asoRequestMethod)
    {
        $this->asoRequestMethod = $asoRequestMethod;

        return $this;
    }

    /**
     * Get asoRequestMethod
     *
     * @return string
     */
    public function getAsoRequestMethod()
    {
        return $this->asoRequestMethod;
    }

    /**
     * Set asoRequestUri
     *
     * @param string $asoRequestUri
     *
     * @return AsoAlerteSecuriteOpn
     */
    public function setAsoRequestUri($asoRequestUri)
    {
        $this->asoRequestUri = $asoRequestUri;

        return $this;
    }

    /**
     * Get asoRequestUri
     *
     * @return string
     */
    public function getAsoRequestUri()
    {
        return $this->asoRequestUri;
    }

    /**
     * Set asoCreatedAt
     *
     * @param \DateTime $asoCreatedAt
     *
     * @return AsoAlerteSecuriteOpn
     */
    public function setAsoCreatedAt($asoCreatedAt)
    {
        $this->asoCreatedAt = $asoCreatedAt;

        return $this;
    }

    /**
     * Get asoCreatedAt
     *
     * @return \DateTime
     */
    public function getAsoCreatedAt()
    {
        return $this->asoCreatedAt;
    }

    /**
     * Set asoUpdatedAt
     *
     * @param \DateTime $asoUpdatedAt
     *
     * @return AsoAlerteSecuriteOpn
     */
    public function setAsoUpdatedAt($asoUpdatedAt)
    {
        $this->asoUpdatedAt = $asoUpdatedAt;

        return $this;
    }

    /**
     * Get asoUpdatedAt
     *
     * @return \DateTime
     */
    public function getAsoUpdatedAt()
    {
        return $this->asoUpdatedAt;
    }
}
