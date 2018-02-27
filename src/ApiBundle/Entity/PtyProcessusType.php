<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PtyProcessusType
 *
 * @ORM\Table(
 *     name="pty_processus_type", indexes={
 *      @ORM\Index(name="pty_id_pro_typ", columns={"pty_id_processus", "pty_type"}),
 *      @ORM\Index(name="pty_type", columns={"pty_type"}),
 *      @ORM\Index(name="IDX_3AF4A780C0341F97", columns={"pty_id_processus"})}
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\PtyProcessusTypeRepository")
 */
class PtyProcessusType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="pty_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ptyId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pty_created_at", type="datetime", nullable=true)
     */
    private $ptyCreatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pty_updated_at", type="datetime", nullable=true)
     */
    private $ptyUpdatedAt;

    /**
     * @var ProProcessus
     *
     * @ORM\ManyToOne(targetEntity="ProProcessus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pty_id_processus", referencedColumnName="pro_id", onDelete="CASCADE")
     * })
     */
    private $ptyProcessus;

    /**
     * @var TypType
     *
     * @ORM\ManyToOne(targetEntity="TypType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pty_type", referencedColumnName="typ_code")
     * })
     */
    private $ptyType;


    /**
     * Get ptyId
     *
     * @return integer
     */
    public function getPtyId()
    {
        return $this->ptyId;
    }

    /**
     * Set ptyCreatedAt
     *
     * @param \DateTime $ptyCreatedAt
     *
     * @return PtyProcessusType
     */
    public function setPtyCreatedAt($ptyCreatedAt)
    {
        $this->ptyCreatedAt = $ptyCreatedAt;

        return $this;
    }

    /**
     * Get ptyCreatedAt
     *
     * @return \DateTime
     */
    public function getPtyCreatedAt()
    {
        return $this->ptyCreatedAt;
    }

    /**
     * Set ptyUpdatedAt
     *
     * @param \DateTime $ptyUpdatedAt
     *
     * @return PtyProcessusType
     */
    public function setPtyUpdatedAt($ptyUpdatedAt)
    {
        $this->ptyUpdatedAt = $ptyUpdatedAt;

        return $this;
    }

    /**
     * Get ptyUpdatedAt
     *
     * @return \DateTime
     */
    public function getPtyUpdatedAt()
    {
        return $this->ptyUpdatedAt;
    }

    /**
     * Set ptyProcessus
     *
     * @param \ApiBundle\Entity\ProProcessus $ptyProcessus
     *
     * @return PtyProcessusType
     */
    public function setPtyProcessus(ProProcessus $ptyProcessus = null)
    {
        $this->ptyProcessus = $ptyProcessus;

        return $this;
    }

    /**
     * Get ptyProcessus
     *
     * @return \ApiBundle\Entity\ProProcessus
     */
    public function getPtyProcessus()
    {
        return $this->ptyProcessus;
    }

    /**
     * Set ptyType
     *
     * @param \ApiBundle\Entity\TypType $ptyType
     *
     * @return PtyProcessusType
     */
    public function setPtyType(TypType $ptyType = null)
    {
        $this->ptyType = $ptyType;

        return $this;
    }

    /**
     * Get ptyType
     *
     * @return \ApiBundle\Entity\TypType
     */
    public function getPtyType()
    {
        return $this->ptyType;
    }
}
