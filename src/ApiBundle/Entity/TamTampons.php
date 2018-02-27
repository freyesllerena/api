<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TamTampons
 *
 * @ORM\Table(name="tam_tampons", uniqueConstraints={@ORM\UniqueConstraint(name="tam_id_fdp", columns={"tam_id_fdp"})})
 * @ORM\Entity
 */
class TamTampons
{
    const DEFAULT_TAM_X = 0;
    const DEFAULT_TAM_Y = 0;
    const DEFAULT_TAM_SCALE_X = 100;
    const DEFAULT_TAM_SCALE_Y = 100;
    const DEFAULT_TAM_ROTATION = 0;
    const DEFAULT_TAM_MODE_OVERLAY = false;
    const DEFAULT_TAM_AS_X = 0;
    const DEFAULT_TAM_AS_Y = 0;
    const DEFAULT_TAM_AS_SCALE_X = 0;
    const DEFAULT_TAM_AS_SCALE_Y = 0;
    const DEFAULT_TAM_AS_ROTATION = 0;
    const DEFAULT_TAM_AS_MODE_OVERLAY = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tamId;

    /**
     * @var string
     *
     * @ORM\Column(name="tam_id_fdp", type="string", length=100, nullable=false)
     */
    private $tamIdFdp;

    /**
     * @var string
     *
     * @ORM\Column(name="tam_chemin", type="string", length=255, nullable=true)
     */
    private $tamChemin;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_x", type="integer", nullable=false)
     */
    private $tamX = self::DEFAULT_TAM_X;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_y", type="integer", nullable=false)
     */
    private $tamY = self::DEFAULT_TAM_Y;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_scale_x", type="integer", nullable=false)
     */
    private $tamScaleX = self::DEFAULT_TAM_SCALE_X;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_scale_y", type="integer", nullable=false)
     */
    private $tamScaleY = self::DEFAULT_TAM_SCALE_Y;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_rotation", type="integer", nullable=false)
     */
    private $tamRotation = self::DEFAULT_TAM_ROTATION;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tam_mode_overlay", type="boolean", nullable=false)
     */
    private $tamModeOverlay = self::DEFAULT_TAM_MODE_OVERLAY;

    /**
     * @var string
     *
     * @ORM\Column(name="tam_add_free_text", type="text", length=65535, nullable=false)
     */
    private $tamAddFreeText;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_as_x", type="integer", nullable=false)
     */
    private $tamAsX = self::DEFAULT_TAM_AS_X;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_as_y", type="integer", nullable=false)
     */
    private $tamAsY = self::DEFAULT_TAM_AS_Y;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_as_scale_x", type="integer", nullable=false)
     */
    private $tamAsScaleX = self::DEFAULT_TAM_AS_SCALE_X;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_as_scale_y", type="integer", nullable=false)
     */
    private $tamAsScaleY = self::DEFAULT_TAM_AS_SCALE_Y;

    /**
     * @var integer
     *
     * @ORM\Column(name="tam_as_rotation", type="integer", nullable=false)
     */
    private $tamAsRotation = self::DEFAULT_TAM_AS_ROTATION;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tam_as_mode_overlay", type="boolean", nullable=false)
     */
    private $tamAsModeOverlay = self::DEFAULT_TAM_AS_MODE_OVERLAY;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="tam_created_at", type="datetime", nullable=true)
     */
    private $tamCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="tam_updated_at", type="datetime", nullable=true)
     */
    private $tamUpdatedAt;


    /**
     * Get tamId
     *
     * @return integer
     */
    public function getTamId()
    {
        return $this->tamId;
    }

    /**
     * Set tamIdFdp
     *
     * @param string $tamIdFdp
     *
     * @return TamTampons
     */
    public function setTamIdFdp($tamIdFdp)
    {
        $this->tamIdFdp = $tamIdFdp;

        return $this;
    }

    /**
     * Get tamIdFdp
     *
     * @return string
     */
    public function getTamIdFdp()
    {
        return $this->tamIdFdp;
    }

    /**
     * Set tamChemin
     *
     * @param string $tamChemin
     *
     * @return TamTampons
     */
    public function setTamChemin($tamChemin)
    {
        $this->tamChemin = $tamChemin;

        return $this;
    }

    /**
     * Get tamChemin
     *
     * @return string
     */
    public function getTamChemin()
    {
        return $this->tamChemin;
    }

    /**
     * Set tamX
     *
     * @param integer $tamX
     *
     * @return TamTampons
     */
    public function setTamX($tamX)
    {
        $this->tamX = $tamX;

        return $this;
    }

    /**
     * Get tamX
     *
     * @return integer
     */
    public function getTamX()
    {
        return $this->tamX;
    }

    /**
     * Set tamY
     *
     * @param integer $tamY
     *
     * @return TamTampons
     */
    public function setTamY($tamY)
    {
        $this->tamY = $tamY;

        return $this;
    }

    /**
     * Get tamY
     *
     * @return integer
     */
    public function getTamY()
    {
        return $this->tamY;
    }

    /**
     * Set tamScaleX
     *
     * @param integer $tamScaleX
     *
     * @return TamTampons
     */
    public function setTamScaleX($tamScaleX)
    {
        $this->tamScaleX = $tamScaleX;

        return $this;
    }

    /**
     * Get tamScaleX
     *
     * @return integer
     */
    public function getTamScaleX()
    {
        return $this->tamScaleX;
    }

    /**
     * Set tamScaleY
     *
     * @param integer $tamScaleY
     *
     * @return TamTampons
     */
    public function setTamScaleY($tamScaleY)
    {
        $this->tamScaleY = $tamScaleY;

        return $this;
    }

    /**
     * Get tamScaleY
     *
     * @return integer
     */
    public function getTamScaleY()
    {
        return $this->tamScaleY;
    }

    /**
     * Set tamRotation
     *
     * @param integer $tamRotation
     *
     * @return TamTampons
     */
    public function setTamRotation($tamRotation)
    {
        $this->tamRotation = $tamRotation;

        return $this;
    }

    /**
     * Get tamRotation
     *
     * @return integer
     */
    public function getTamRotation()
    {
        return $this->tamRotation;
    }

    /**
     * Set tamModeOverlay
     *
     * @param boolean $tamModeOverlay
     *
     * @return TamTampons
     */
    public function setTamModeOverlay($tamModeOverlay)
    {
        $this->tamModeOverlay = $tamModeOverlay;

        return $this;
    }

    /**
     * Get tamModeOverlay
     *
     * @return boolean
     */
    public function isTamModeOverlay()
    {
        return $this->tamModeOverlay;
    }

    /**
     * Set tamAddFreeText
     *
     * @param string $tamAddFreeText
     *
     * @return TamTampons
     */
    public function setTamAddFreeText($tamAddFreeText)
    {
        $this->tamAddFreeText = $tamAddFreeText;

        return $this;
    }

    /**
     * Get tamAddFreeText
     *
     * @return string
     */
    public function getTamAddFreeText()
    {
        return $this->tamAddFreeText;
    }

    /**
     * Set tamAsX
     *
     * @param integer $tamAsX
     *
     * @return TamTampons
     */
    public function setTamAsX($tamAsX)
    {
        $this->tamAsX = $tamAsX;

        return $this;
    }

    /**
     * Get tamAsX
     *
     * @return integer
     */
    public function getTamAsX()
    {
        return $this->tamAsX;
    }

    /**
     * Set tamAsY
     *
     * @param integer $tamAsY
     *
     * @return TamTampons
     */
    public function setTamAsY($tamAsY)
    {
        $this->tamAsY = $tamAsY;

        return $this;
    }

    /**
     * Get tamAsY
     *
     * @return integer
     */
    public function getTamAsY()
    {
        return $this->tamAsY;
    }

    /**
     * Set tamAsScaleX
     *
     * @param integer $tamAsScaleX
     *
     * @return TamTampons
     */
    public function setTamAsScaleX($tamAsScaleX)
    {
        $this->tamAsScaleX = $tamAsScaleX;

        return $this;
    }

    /**
     * Get tamAsScaleX
     *
     * @return integer
     */
    public function getTamAsScaleX()
    {
        return $this->tamAsScaleX;
    }

    /**
     * Set tamAsScaleY
     *
     * @param integer $tamAsScaleY
     *
     * @return TamTampons
     */
    public function setTamAsScaleY($tamAsScaleY)
    {
        $this->tamAsScaleY = $tamAsScaleY;

        return $this;
    }

    /**
     * Get tamAsScaleY
     *
     * @return integer
     */
    public function getTamAsScaleY()
    {
        return $this->tamAsScaleY;
    }

    /**
     * Set tamAsRotation
     *
     * @param integer $tamAsRotation
     *
     * @return TamTampons
     */
    public function setTamAsRotation($tamAsRotation)
    {
        $this->tamAsRotation = $tamAsRotation;

        return $this;
    }

    /**
     * Get tamAsRotation
     *
     * @return integer
     */
    public function getTamAsRotation()
    {
        return $this->tamAsRotation;
    }

    /**
     * Set tamAsModeOverlay
     *
     * @param boolean $tamAsModeOverlay
     *
     * @return TamTampons
     */
    public function setTamAsModeOverlay($tamAsModeOverlay)
    {
        $this->tamAsModeOverlay = $tamAsModeOverlay;

        return $this;
    }

    /**
     * Get tamAsModeOverlay
     *
     * @return boolean
     */
    public function isTamAsModeOverlay()
    {
        return $this->tamAsModeOverlay;
    }

    /**
     * Set tamCreatedAt
     *
     * @param \DateTime $tamCreatedAt
     *
     * @return TamTampons
     */
    public function setTamCreatedAt($tamCreatedAt)
    {
        $this->tamCreatedAt = $tamCreatedAt;

        return $this;
    }

    /**
     * Get tamCreatedAt
     *
     * @return \DateTime
     */
    public function getTamCreatedAt()
    {
        return $this->tamCreatedAt;
    }

    /**
     * Set tamUpdatedAt
     *
     * @param \DateTime $tamUpdatedAt
     *
     * @return TamTampons
     */
    public function setTamUpdatedAt($tamUpdatedAt)
    {
        $this->tamUpdatedAt = $tamUpdatedAt;

        return $this;
    }

    /**
     * Get tamUpdatedAt
     *
     * @return \DateTime
     */
    public function getTamUpdatedAt()
    {
        return $this->tamUpdatedAt;
    }
}
