<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * UprUserPreferences
 *
 * @ORM\Table(
 *     name="upr_user_preferences",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(
 *       name="upr_usr_dev_typ",
 *       columns={"upr_id_user", "upr_device", "upr_type"}
 *      )
 *     },
 *     indexes={
 *      @ORM\Index(name="IDX_7FEBA53EDB37D5C9",
 *      columns={"upr_id_user"}
 *     )
 *    }
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UprUserPreferencesRepository")
 */
class UprUserPreferences
{
    /**
     * @var integer
     *
     * @ORM\Column(name="upr_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uprId;

    /**
     * @var string
     *
     * @ORM\Column(name="upr_device", type="string", length=5, nullable=false)
     */
    private $uprDevice;

    /**
     * @var string
     *
     * @ORM\Column(name="upr_type", type="string", length=50, nullable=false)
     */
    private $uprType;

    /**
     * @var string
     *
     * @ORM\Column(name="upr_data", type="text", length=65535, nullable=false)
     */
    private $uprData;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="upr_created_at", type="datetime", nullable=true)
     */
    private $uprCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="upr_updated_at", type="datetime", nullable=true)
     */
    private $uprUpdatedAt;

    /**
     * @var UsrUsers
     *
     * @ORM\ManyToOne(targetEntity="UsrUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="upr_id_user", referencedColumnName="usr_login")
     * })
     */
    private $uprUser;



    /**
     * Get uprId
     *
     * @return integer
     */
    public function getUprId()
    {
        return $this->uprId;
    }

    /**
     * Set uprDevice
     *
     * @param string $uprDevice
     *
     * @return UprUserPreferences
     */
    public function setUprDevice($uprDevice)
    {
        $this->uprDevice = $uprDevice;

        return $this;
    }

    /**
     * Get uprDevice
     *
     * @return string
     */
    public function getUprDevice()
    {
        return $this->uprDevice;
    }

    /**
     * Set uprType
     *
     * @param string $uprType
     *
     * @return UprUserPreferences
     */
    public function setUprType($uprType)
    {
        $this->uprType = $uprType;

        return $this;
    }

    /**
     * Get uprType
     *
     * @return string
     */
    public function getUprType()
    {
        return $this->uprType;
    }

    /**
     * Set uprData
     *
     * @param string $uprData
     *
     * @return UprUserPreferences
     */
    public function setUprData($uprData)
    {
        $this->uprData = $uprData;

        return $this;
    }

    /**
     * Get uprData
     *
     * @return string
     */
    public function getUprData()
    {
        return $this->uprData;
    }

    /**
     * Set uprCreatedAt
     *
     * @param \DateTime $uprCreatedAt
     *
     * @return UprUserPreferences
     */
    public function setUprCreatedAt($uprCreatedAt)
    {
        $this->uprCreatedAt = $uprCreatedAt;

        return $this;
    }

    /**
     * Get uprCreatedAt
     *
     * @return \DateTime
     */
    public function getUprCreatedAt()
    {
        return $this->uprCreatedAt;
    }

    /**
     * Set uprUpdatedAt
     *
     * @param \DateTime $uprUpdatedAt
     *
     * @return UprUserPreferences
     */
    public function setUprUpdatedAt($uprUpdatedAt)
    {
        $this->uprUpdatedAt = $uprUpdatedAt;

        return $this;
    }

    /**
     * Get uprUpdatedAt
     *
     * @return \DateTime
     */
    public function getUprUpdatedAt()
    {
        return $this->uprUpdatedAt;
    }

    /**
     * Set uprUser
     *
     * @param \ApiBundle\Entity\UsrUsers $uprUser
     *
     * @return UprUserPreferences
     */
    public function setUprUser($uprUser = null)
    {
        $this->uprUser = $uprUser;

        return $this;
    }

    /**
     * Get uprUser
     *
     * @return \ApiBundle\Entity\UsrUsers
     */
    public function getUprUser()
    {
        return $this->uprUser;
    }
}
