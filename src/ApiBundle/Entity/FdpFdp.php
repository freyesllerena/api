<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FdpFdp
 *
 * @ORM\Table(name="fdp_fdp", uniqueConstraints={@ORM\UniqueConstraint(name="fdp_nom_appli", columns={"fdp_nom", "fdp_id_application"})})
 * @ORM\Entity
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class FdpFdp
{
    const DEFAULT_FDP_X = 0;
    const DEFAULT_FDP_Y = 0;
    const DEFAULT_FDP_SCALE_X = 100;
    const DEFAULT_FDP_SCALE_Y = 100;
    const DEFAULT_FDP_ROTATION = 0;
    const DEFAULT_FDP_AS_X = 0;
    const DEFAULT_FDP_AS_Y = 0;
    const DEFAULT_FDP_AS_SCALE_X = 0;
    const DEFAULT_FDP_AS_SCALE_Y = 0;
    const DEFAULT_FDP_AS_ROTATION = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fdpId;

    /**
     * @var string
     *
     * @ORM\Column(name="fdp_nom", type="string", length=100, nullable=false)
     */
    private $fdpNom;

    /**
     * @var string
     *
     * @ORM\Column(name="fdp_id_application", type="string", length=100, nullable=false)
     */
    private $fdpIdApplication;

    /**
     * @var string
     *
     * @ORM\Column(name="fdp_id_fdp", type="string", length=100, nullable=false)
     */
    private $fdpIdFdp;

    /**
     * @var string
     *
     * @ORM\Column(name="fdp_chemin_on", type="string", length=255, nullable=true)
     */
    private $fdpCheminOn;

    /**
     * @var string
     *
     * @ORM\Column(name="fdp_chemin_off", type="string", length=255, nullable=true)
     */
    private $fdpCheminOff;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_x", type="smallint", nullable=false)
     */
    private $fdpX = self::DEFAULT_FDP_X;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_y", type="smallint", nullable=false)
     */
    private $fdpY = self::DEFAULT_FDP_Y;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_scale_x", type="smallint", nullable=false)
     */
    private $fdpScaleX = self::DEFAULT_FDP_SCALE_X;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_scale_y", type="smallint", nullable=false)
     */
    private $fdpScaleY = self::DEFAULT_FDP_SCALE_Y;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_rotation", type="smallint", nullable=false)
     */
    private $fdpRotation = self::DEFAULT_FDP_ROTATION;

    /**
     * @var string
     *
     * @ORM\Column(name="fdp_cgv", type="string", length=255, nullable=true)
     */
    private $fdpCgv;

    /**
     * @var string
     *
     * @ORM\Column(name="fdp_as_chemin_on", type="string", length=255, nullable=true)
     */
    private $fdpAsCheminOn;

    /**
     * @var float
     *
     * @ORM\Column(name="fdp_as_x", type="float", precision=10, scale=0, nullable=false)
     */
    private $fdpAsX = self::DEFAULT_FDP_AS_X;

    /**
     * @var float
     *
     * @ORM\Column(name="fdp_as_y", type="float", precision=10, scale=0, nullable=false)
     */
    private $fdpAsY = self::DEFAULT_FDP_AS_Y;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_as_scale_x", type="smallint", nullable=false)
     */
    private $fdpAsScaleX = self::DEFAULT_FDP_AS_SCALE_X;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_as_scale_y", type="smallint", nullable=false)
     */
    private $fdpAsScaleY = self::DEFAULT_FDP_AS_SCALE_Y;

    /**
     * @var integer
     *
     * @ORM\Column(name="fdp_as_rotation", type="smallint", nullable=false)
     */
    private $fdpAsRotation = self::DEFAULT_FDP_AS_ROTATION;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fdp_created_at", type="datetime", nullable=true)
     */
    private $fdpCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fdp_updated_at", type="datetime", nullable=true)
     */
    private $fdpUpdatedAt;


    /**
     * Get fdpId
     *
     * @return integer
     */
    public function getFdpId()
    {
        return $this->fdpId;
    }

    /**
     * Set fdpNom
     *
     * @param string $fdpNom
     *
     * @return FdpFdp
     */
    public function setFdpNom($fdpNom)
    {
        $this->fdpNom = $fdpNom;

        return $this;
    }

    /**
     * Get fdpNom
     *
     * @return string
     */
    public function getFdpNom()
    {
        return $this->fdpNom;
    }

    /**
     * Set fdpIdApplication
     *
     * @param string $fdpIdApplication
     *
     * @return FdpFdp
     */
    public function setFdpIdApplication($fdpIdApplication)
    {
        $this->fdpIdApplication = $fdpIdApplication;

        return $this;
    }

    /**
     * Get fdpIdApplication
     *
     * @return string
     */
    public function getFdpIdApplication()
    {
        return $this->fdpIdApplication;
    }

    /**
     * Set fdpIdFdp
     *
     * @param string $fdpIdFdp
     *
     * @return FdpFdp
     */
    public function setFdpIdFdp($fdpIdFdp)
    {
        $this->fdpIdFdp = $fdpIdFdp;

        return $this;
    }

    /**
     * Get fdpIdFdp
     *
     * @return string
     */
    public function getFdpIdFdp()
    {
        return $this->fdpIdFdp;
    }

    /**
     * Set fdpCheminOn
     *
     * @param string $fdpCheminOn
     *
     * @return FdpFdp
     */
    public function setFdpCheminOn($fdpCheminOn)
    {
        $this->fdpCheminOn = $fdpCheminOn;

        return $this;
    }

    /**
     * Get fdpCheminOn
     *
     * @return string
     */
    public function getFdpCheminOn()
    {
        return $this->fdpCheminOn;
    }

    /**
     * Set fdpCheminOff
     *
     * @param string $fdpCheminOff
     *
     * @return FdpFdp
     */
    public function setFdpCheminOff($fdpCheminOff)
    {
        $this->fdpCheminOff = $fdpCheminOff;

        return $this;
    }

    /**
     * Get fdpCheminOff
     *
     * @return string
     */
    public function getFdpCheminOff()
    {
        return $this->fdpCheminOff;
    }

    /**
     * Set fdpX
     *
     * @param integer $fdpX
     *
     * @return FdpFdp
     */
    public function setFdpX($fdpX)
    {
        $this->fdpX = $fdpX;

        return $this;
    }

    /**
     * Get fdpX
     *
     * @return integer
     */
    public function getFdpX()
    {
        return $this->fdpX;
    }

    /**
     * Set fdpY
     *
     * @param integer $fdpY
     *
     * @return FdpFdp
     */
    public function setFdpY($fdpY)
    {
        $this->fdpY = $fdpY;

        return $this;
    }

    /**
     * Get fdpY
     *
     * @return integer
     */
    public function getFdpY()
    {
        return $this->fdpY;
    }

    /**
     * Set fdpScaleX
     *
     * @param integer $fdpScaleX
     *
     * @return FdpFdp
     */
    public function setFdpScaleX($fdpScaleX)
    {
        $this->fdpScaleX = $fdpScaleX;

        return $this;
    }

    /**
     * Get fdpScaleX
     *
     * @return integer
     */
    public function getFdpScaleX()
    {
        return $this->fdpScaleX;
    }

    /**
     * Set fdpScaleY
     *
     * @param integer $fdpScaleY
     *
     * @return FdpFdp
     */
    public function setFdpScaleY($fdpScaleY)
    {
        $this->fdpScaleY = $fdpScaleY;

        return $this;
    }

    /**
     * Get fdpScaleY
     *
     * @return integer
     */
    public function getFdpScaleY()
    {
        return $this->fdpScaleY;
    }

    /**
     * Set fdpRotation
     *
     * @param integer $fdpRotation
     *
     * @return FdpFdp
     */
    public function setFdpRotation($fdpRotation)
    {
        $this->fdpRotation = $fdpRotation;

        return $this;
    }

    /**
     * Get fdpRotation
     *
     * @return integer
     */
    public function getFdpRotation()
    {
        return $this->fdpRotation;
    }

    /**
     * Set fdpCgv
     *
     * @param string $fdpCgv
     *
     * @return FdpFdp
     */
    public function setFdpCgv($fdpCgv)
    {
        $this->fdpCgv = $fdpCgv;

        return $this;
    }

    /**
     * Get fdpCgv
     *
     * @return string
     */
    public function getFdpCgv()
    {
        return $this->fdpCgv;
    }

    /**
     * Set fdpAsCheminOn
     *
     * @param string $fdpAsCheminOn
     *
     * @return FdpFdp
     */
    public function setFdpAsCheminOn($fdpAsCheminOn)
    {
        $this->fdpAsCheminOn = $fdpAsCheminOn;

        return $this;
    }

    /**
     * Get fdpAsCheminOn
     *
     * @return string
     */
    public function getFdpAsCheminOn()
    {
        return $this->fdpAsCheminOn;
    }

    /**
     * Set fdpAsX
     *
     * @param float $fdpAsX
     *
     * @return FdpFdp
     */
    public function setFdpAsX($fdpAsX)
    {
        $this->fdpAsX = $fdpAsX;

        return $this;
    }

    /**
     * Get fdpAsX
     *
     * @return float
     */
    public function getFdpAsX()
    {
        return $this->fdpAsX;
    }

    /**
     * Set fdpAsY
     *
     * @param float $fdpAsY
     *
     * @return FdpFdp
     */
    public function setFdpAsY($fdpAsY)
    {
        $this->fdpAsY = $fdpAsY;

        return $this;
    }

    /**
     * Get fdpAsY
     *
     * @return float
     */
    public function getFdpAsY()
    {
        return $this->fdpAsY;
    }

    /**
     * Set fdpAsScaleX
     *
     * @param integer $fdpAsScaleX
     *
     * @return FdpFdp
     */
    public function setFdpAsScaleX($fdpAsScaleX)
    {
        $this->fdpAsScaleX = $fdpAsScaleX;

        return $this;
    }

    /**
     * Get fdpAsScaleX
     *
     * @return integer
     */
    public function getFdpAsScaleX()
    {
        return $this->fdpAsScaleX;
    }

    /**
     * Set fdpAsScaleY
     *
     * @param integer $fdpAsScaleY
     *
     * @return FdpFdp
     */
    public function setFdpAsScaleY($fdpAsScaleY)
    {
        $this->fdpAsScaleY = $fdpAsScaleY;

        return $this;
    }

    /**
     * Get fdpAsScaleY
     *
     * @return integer
     */
    public function getFdpAsScaleY()
    {
        return $this->fdpAsScaleY;
    }

    /**
     * Set fdpAsRotation
     *
     * @param integer $fdpAsRotation
     *
     * @return FdpFdp
     */
    public function setFdpAsRotation($fdpAsRotation)
    {
        $this->fdpAsRotation = $fdpAsRotation;

        return $this;
    }

    /**
     * Get fdpAsRotation
     *
     * @return integer
     */
    public function getFdpAsRotation()
    {
        return $this->fdpAsRotation;
    }

    /**
     * Set fdpCreatedAt
     *
     * @param \DateTime $fdpCreatedAt
     *
     * @return FdpFdp
     */
    public function setFdpCreatedAt($fdpCreatedAt)
    {
        $this->fdpCreatedAt = $fdpCreatedAt;

        return $this;
    }

    /**
     * Get fdpCreatedAt
     *
     * @return \DateTime
     */
    public function getFdpCreatedAt()
    {
        return $this->fdpCreatedAt;
    }

    /**
     * Set fdpUpdatedAt
     *
     * @param \DateTime $fdpUpdatedAt
     *
     * @return FdpFdp
     */
    public function setFdpUpdatedAt($fdpUpdatedAt)
    {
        $this->fdpUpdatedAt = $fdpUpdatedAt;

        return $this;
    }

    /**
     * Get fdpUpdatedAt
     *
     * @return \DateTime
     */
    public function getFdpUpdatedAt()
    {
        return $this->fdpUpdatedAt;
    }
}
