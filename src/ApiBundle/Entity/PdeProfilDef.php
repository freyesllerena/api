<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelTypePerimeterType;

/**
 * PdeProfilDef
 *
 * @ORM\Table(name="pde_profil_def")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\PdeProfilDefRepository")
 */
class PdeProfilDef
{
    /**
     * @var integer
     *
     * @ORM\Column(name="pde_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $pdeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="pde_id_profil_def", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $pdeIdProfilDef;

    /**
     * @var string
     *
     * @ORM\Column(name="pde_type", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $pdeType = EnumLabelTypePerimeterType::APPLI_PERIMETER;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="pde_created_at", type="datetime", nullable=true)
     */
    private $pdeCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="pde_updated_at", type="datetime", nullable=true)
     */
    private $pdeUpdatedAt;


    /**
     * Set pdeId
     *
     * @param integer $pdeId
     *
     * @return PdeProfilDef
     */
    public function setPdeId($pdeId)
    {
        $this->pdeId = $pdeId;

        return $this;
    }

    /**
     * Get pdeId
     *
     * @return integer
     */
    public function getPdeId()
    {
        return $this->pdeId;
    }

    /**
     * Set pdeIdProfilDef
     *
     * @param integer $pdeIdProfilDef
     *
     * @return PdeProfilDef
     */
    public function setPdeIdProfilDef($pdeIdProfilDef)
    {
        $this->pdeIdProfilDef = $pdeIdProfilDef;

        return $this;
    }

    /**
     * Get pdeIdProfilDef
     *
     * @return integer
     */
    public function getPdeIdProfilDef()
    {
        return $this->pdeIdProfilDef;
    }

    /**
     * Set pdeType
     *
     * @param string $pdeType
     *
     * @return PdeProfilDef
     */
    public function setPdeType($pdeType)
    {
        $this->pdeType = $pdeType;

        return $this;
    }

    /**
     * Get pdeType
     *
     * @return string
     */
    public function getPdeType()
    {
        return $this->pdeType;
    }

    /**
     * Set pdeCreatedAt
     *
     * @param \DateTime $pdeCreatedAt
     *
     * @return PdeProfilDef
     */
    public function setPdeCreatedAt($pdeCreatedAt)
    {
        $this->pdeCreatedAt = $pdeCreatedAt;

        return $this;
    }

    /**
     * Get pdeCreatedAt
     *
     * @return \DateTime
     */
    public function getPdeCreatedAt()
    {
        return $this->pdeCreatedAt;
    }

    /**
     * Set pdeUpdatedAt
     *
     * @param \DateTime $pdeUpdatedAt
     *
     * @return PdeProfilDef
     */
    public function setPdeUpdatedAt($pdeUpdatedAt)
    {
        $this->pdeUpdatedAt = $pdeUpdatedAt;

        return $this;
    }

    /**
     * Get pdeUpdatedAt
     *
     * @return \DateTime
     */
    public function getPdeUpdatedAt()
    {
        return $this->pdeUpdatedAt;
    }
}
