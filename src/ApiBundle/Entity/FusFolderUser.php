<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FusFolderUser
 *
 * @ORM\Table(name="fus_folder_user", uniqueConstraints={@ORM\UniqueConstraint(name="fus_id_fol_usr", columns={"fus_id_folder", "fus_id_user"})}, indexes={@ORM\Index(name="fus_id_user", columns={"fus_id_user"}), @ORM\Index(name="IDX_6F81DB591EC8F45C", columns={"fus_id_folder"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\FusFolderUserRepository")
 */
class FusFolderUser
{
    const ERR_DOES_NOT_EXIST = 'errFusFolderUserDoesNotExist';

    /**
     * @var integer
     *
     * @ORM\Column(name="fus_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fusId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fus_date_acces", type="datetime", nullable=true)
     */
    private $fusDateAcces;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fus_created_at", type="datetime", nullable=true)
     */
    private $fusCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fus_updated_at", type="datetime", nullable=true)
     */
    private $fusUpdatedAt;

    /**
     * @var \FolFolder
     *
     * @ORM\ManyToOne(targetEntity="FolFolder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fus_id_folder", referencedColumnName="fol_id", onDelete="CASCADE")
     * })
     */
    private $fusFolder;

    /**
     * @var \UsrUsers
     *
     * @ORM\ManyToOne(targetEntity="UsrUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fus_id_user", referencedColumnName="usr_login")
     * })
     */
    private $fusUser;


    /**
     * Get fusId
     *
     * @return integer
     */
    public function getFusId()
    {
        return $this->fusId;
    }

    /**
     * Set fusDateAcces
     *
     * @param \DateTime $fusDateAcces
     *
     * @return FusFolderUser
     */
    public function setFusDateAcces($fusDateAcces)
    {
        $this->fusDateAcces = $fusDateAcces;

        return $this;
    }

    /**
     * Get fusDateAcces
     *
     * @return \DateTime
     */
    public function getFusDateAcces()
    {
        return $this->fusDateAcces;
    }

    /**
     * Set fusCreatedAt
     *
     * @param \DateTime $fusCreatedAt
     *
     * @return FusFolderUser
     */
    public function setFusCreatedAt($fusCreatedAt)
    {
        $this->fusCreatedAt = $fusCreatedAt;

        return $this;
    }

    /**
     * Get fusCreatedAt
     *
     * @return \DateTime
     */
    public function getFusCreatedAt()
    {
        return $this->fusCreatedAt;
    }

    /**
     * Set fusUpdatedAt
     *
     * @param \DateTime $fusUpdatedAt
     *
     * @return FusFolderUser
     */
    public function setFusUpdatedAt($fusUpdatedAt)
    {
        $this->fusUpdatedAt = $fusUpdatedAt;

        return $this;
    }

    /**
     * Get fusUpdatedAt
     *
     * @return \DateTime
     */
    public function getFusUpdatedAt()
    {
        return $this->fusUpdatedAt;
    }

    /**
     * Set fusFolder
     *
     * @param \ApiBundle\Entity\FolFolder $fusFolder
     *
     * @return FusFolderUser
     */
    public function setFusFolder(\ApiBundle\Entity\FolFolder $fusFolder = null)
    {
        $this->fusFolder = $fusFolder;

        return $this;
    }

    /**
     * Get fusFolder
     *
     * @return \ApiBundle\Entity\FolFolder
     */
    public function getFusFolder()
    {
        return $this->fusFolder;
    }

    /**
     * Set fusUser
     *
     * @param \ApiBundle\Entity\UsrUsers $fusUser
     *
     * @return FusFolderUser
     */
    public function setFusUser(\ApiBundle\Entity\UsrUsers $fusUser = null)
    {
        $this->fusUser = $fusUser;

        return $this;
    }

    /**
     * Get fusUser
     *
     * @return \ApiBundle\Entity\UsrUsers
     */
    public function getFusUser()
    {
        return $this->fusUser;
    }
}
