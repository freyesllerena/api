<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CtyCompletudeType
 *
 * @ORM\Table(name="cty_completude_type", indexes={@ORM\Index(name="cty_id_completude", columns={"cty_id_completude", "cty_type"}), @ORM\Index(name="fk_typ_cty", columns={"cty_type"}), @ORM\Index(name="IDX_63DABC0310441B7D", columns={"cty_id_completude"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\CtyCompletudeTypeRepository")
 */
class CtyCompletudeType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cty_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ctyId;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="cty_created_at", type="datetime", nullable=true)
     */
    private $ctyCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="cty_updated_at", type="datetime", nullable=true)
     */
    private $ctyUpdatedAt;

    /**
     * @var \ComCompletude
     *
     * @ORM\ManyToOne(targetEntity="ComCompletude")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cty_id_completude", referencedColumnName="com_id_completude", onDelete="CASCADE")
     * })
     */
    private $ctyCompletude;

    /**
     * @var \TypType
     *
     * @ORM\ManyToOne(targetEntity="TypType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cty_type", referencedColumnName="typ_code")
     * })
     */
    private $ctyType;


    /**
     * Get ctyId
     *
     * @return integer
     */
    public function getCtyId()
    {
        return $this->ctyId;
    }

    /**
     * Set ctyCreatedAt
     *
     * @param \DateTime $ctyCreatedAt
     *
     * @return CtyCompletudeType
     */
    public function setCtyCreatedAt($ctyCreatedAt)
    {
        $this->ctyCreatedAt = $ctyCreatedAt;

        return $this;
    }

    /**
     * Get ctyCreatedAt
     *
     * @return \DateTime
     */
    public function getCtyCreatedAt()
    {
        return $this->ctyCreatedAt;
    }

    /**
     * Set ctyUpdatedAt
     *
     * @param \DateTime $ctyUpdatedAt
     *
     * @return CtyCompletudeType
     */
    public function setCtyUpdatedAt($ctyUpdatedAt)
    {
        $this->ctyUpdatedAt = $ctyUpdatedAt;

        return $this;
    }

    /**
     * Get ctyUpdatedAt
     *
     * @return \DateTime
     */
    public function getCtyUpdatedAt()
    {
        return $this->ctyUpdatedAt;
    }

    /**
     * Set ctyCompletude
     *
     * @param \ApiBundle\Entity\ComCompletude $ctyCompletude
     *
     * @return CtyCompletudeType
     */
    public function setCtyCompletude(\ApiBundle\Entity\ComCompletude $ctyCompletude = null)
    {
        $this->ctyCompletude = $ctyCompletude;

        return $this;
    }

    /**
     * Get ctyCompletude
     *
     * @return \ApiBundle\Entity\ComCompletude
     */
    public function getCtyCompletude()
    {
        return $this->ctyCompletude;
    }

    /**
     * Set ctyType
     *
     * @param \ApiBundle\Entity\TypType $ctyType
     *
     * @return CtyCompletudeType
     */
    public function setCtyType(\ApiBundle\Entity\TypType $ctyType = null)
    {
        $this->ctyType = $ctyType;

        return $this;
    }

    /**
     * Get ctyType
     *
     * @return \ApiBundle\Entity\TypType
     */
    public function getCtyType()
    {
        return $this->ctyType;
    }
}
