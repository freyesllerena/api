<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IniInteruploadIndexation
 *
 * @ORM\Table(
 *     name="ini_interupload_indexation", uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *      name="ini_ticket", columns={"ini_ticket"}
 *     )
 * })
 * @ORM\Entity
 */
class IniInteruploadIndexation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ini_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iniId;

    /**
     * @var string
     *
     * @ORM\Column(name="ini_ticket", type="string", length=72, nullable=false)
     */
    private $iniTicket;

    /**
     * @var string
     *
     * @ORM\Column(name="ini_content", type="text", length=65535, nullable=false)
     */
    private $iniContent;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="ini_created_at", type="datetime", nullable=true)
     */
    private $iniCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="ini_updated_at", type="datetime", nullable=true)
     */
    private $iniUpdatedAt;


    /**
     * Get iniId
     *
     * @return integer
     */
    public function getIniId()
    {
        return $this->iniId;
    }

    /**
     * Set iniTicket
     *
     * @param string $iniTicket
     *
     * @return IniInteruploadIndexation
     */
    public function setIniTicket($iniTicket)
    {
        $this->iniTicket = $iniTicket;

        return $this;
    }

    /**
     * Get iniTicket
     *
     * @return string
     */
    public function getIniTicket()
    {
        return $this->iniTicket;
    }

    /**
     * Set iniContent
     *
     * @param string $iniContent
     *
     * @return IniInteruploadIndexation
     */
    public function setIniContent($iniContent)
    {
        $this->iniContent = $iniContent;

        return $this;
    }

    /**
     * Get iniContent
     *
     * @return string
     */
    public function getIniContent()
    {
        return $this->iniContent;
    }

    /**
     * Set iniCreatedAt
     *
     * @param \DateTime $iniCreatedAt
     *
     * @return IniInteruploadIndexation
     */
    public function setIniCreatedAt($iniCreatedAt)
    {
        $this->iniCreatedAt = $iniCreatedAt;

        return $this;
    }

    /**
     * Get iniCreatedAt
     *
     * @return \DateTime
     */
    public function getIniCreatedAt()
    {
        return $this->iniCreatedAt;
    }

    /**
     * Set iniUpdatedAt
     *
     * @param \DateTime $iniUpdatedAt
     *
     * @return IniInteruploadIndexation
     */
    public function setIniUpdatedAt($iniUpdatedAt)
    {
        $this->iniUpdatedAt = $iniUpdatedAt;

        return $this;
    }

    /**
     * Get iniUpdatedAt
     *
     * @return \DateTime
     */
    public function getIniUpdatedAt()
    {
        return $this->iniUpdatedAt;
    }
}
