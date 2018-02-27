<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PusProfilUser
 *
 * @ORM\Table(
 *     name="pus_profil_user", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="pus_id_pro_usr", columns={"pus_id_profil", "pus_id_user"})
 * }, indexes={
 *      @ORM\Index(name="fk_pus_usr", columns={"pus_id_user"}),
 *      @ORM\Index(name="IDX_18DEA6135E3601", columns={"pus_id_profil"})
 * })
 * @ORM\Entity
 */
class PusProfilUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="pus_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $pusId;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="pus_created_at", type="datetime", nullable=true)
     */
    private $pusCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="pus_updated_at", type="datetime", nullable=true)
     */
    private $pusUpdatedAt;

    /**
     * @var ProProfil
     *
     * @ORM\ManyToOne(targetEntity="ProProfil")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pus_id_profil", referencedColumnName="pro_id", onDelete="CASCADE")
     * })
     */
    private $pusProfil;

    /**
     * @var UsrUsers
     *
     * @ORM\ManyToOne(targetEntity="UsrUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pus_id_user", referencedColumnName="usr_login", onDelete="CASCADE")
     * })
     */
    private $pusUser;


    /**
     * Get pusId
     *
     * @return integer
     */
    public function getPusId()
    {
        return $this->pusId;
    }

    /**
     * Set pusCreatedAt
     *
     * @param \DateTime $pusCreatedAt
     *
     * @return PusProfilUser
     */
    public function setPusCreatedAt($pusCreatedAt)
    {
        $this->pusCreatedAt = $pusCreatedAt;

        return $this;
    }

    /**
     * Get pusCreatedAt
     *
     * @return \DateTime
     */
    public function getPusCreatedAt()
    {
        return $this->pusCreatedAt;
    }

    /**
     * Set pusUpdatedAt
     *
     * @param \DateTime $pusUpdatedAt
     *
     * @return PusProfilUser
     */
    public function setPusUpdatedAt($pusUpdatedAt)
    {
        $this->pusUpdatedAt = $pusUpdatedAt;

        return $this;
    }

    /**
     * Get pusUpdatedAt
     *
     * @return \DateTime
     */
    public function getPusUpdatedAt()
    {
        return $this->pusUpdatedAt;
    }

    /**
     * Set pusProfil
     *
     * @param \ApiBundle\Entity\ProProfil $pusProfil
     *
     * @return PusProfilUser
     */
    public function setPusProfil(ProProfil $pusProfil = null)
    {
        $this->pusProfil = $pusProfil;

        return $this;
    }

    /**
     * Get pusProfil
     *
     * @return \ApiBundle\Entity\ProProfil
     */
    public function getPusProfil()
    {
        return $this->pusProfil;
    }

    /**
     * Set pusUser
     *
     * @param \ApiBundle\Entity\UsrUsers $pusUser
     *
     * @return PusProfilUser
     */
    public function setPusUser(UsrUsers $pusUser = null)
    {
        $this->pusUser = $pusUser;

        return $this;
    }

    /**
     * Get pusUser
     *
     * @return \ApiBundle\Entity\UsrUsers
     */
    public function getPusUser()
    {
        return $this->pusUser;
    }
}
