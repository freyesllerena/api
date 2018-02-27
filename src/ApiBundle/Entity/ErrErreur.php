<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ErrErreur
 *
 * @ORM\Table(name="err_erreur")
 * @ORM\Entity
 */
class ErrErreur
{
    /**
     * @var integer
     *
     * @ORM\Column(name="err_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $errId;

    /**
     * @var integer
     *
     * @ORM\Column(name="err_code", type="smallint", nullable=false)
     */
    private $errCode;

    /**
     * @var string
     *
     * @ORM\Column(name="err_message", type="string", length=100, nullable=false)
     */
    private $errMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="err_server", type="text", length=65535, nullable=false)
     */
    private $errServer;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="err_created_at", type="datetime", nullable=true)
     */
    private $errCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="err_updated_at", type="datetime", nullable=true)
     */
    private $errUpdatedAt;


    /**
     * Get errId
     *
     * @return integer
     */
    public function getErrId()
    {
        return $this->errId;
    }

    /**
     * Set errCode
     *
     * @param integer $errCode
     *
     * @return ErrErreur
     */
    public function setErrCode($errCode)
    {
        $this->errCode = $errCode;

        return $this;
    }

    /**
     * Get errCode
     *
     * @return integer
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * Set errMessage
     *
     * @param string $errMessage
     *
     * @return ErrErreur
     */
    public function setErrMessage($errMessage)
    {
        $this->errMessage = $errMessage;

        return $this;
    }

    /**
     * Get errMessage
     *
     * @return string
     */
    public function getErrMessage()
    {
        return $this->errMessage;
    }

    /**
     * Set errServer
     *
     * @param string $errServer
     *
     * @return ErrErreur
     */
    public function setErrServer($errServer)
    {
        $this->errServer = $errServer;

        return $this;
    }

    /**
     * Get errServer
     *
     * @return string
     */
    public function getErrServer()
    {
        return $this->errServer;
    }

    /**
     * Set errCreatedAt
     *
     * @param \DateTime $errCreatedAt
     *
     * @return ErrErreur
     */
    public function setErrCreatedAt($errCreatedAt)
    {
        $this->errCreatedAt = $errCreatedAt;

        return $this;
    }

    /**
     * Get errCreatedAt
     *
     * @return \DateTime
     */
    public function getErrCreatedAt()
    {
        return $this->errCreatedAt;
    }

    /**
     * Set errUpdatedAt
     *
     * @param \DateTime $errUpdatedAt
     *
     * @return ErrErreur
     */
    public function setErrUpdatedAt($errUpdatedAt)
    {
        $this->errUpdatedAt = $errUpdatedAt;

        return $this;
    }

    /**
     * Get errUpdatedAt
     *
     * @return \DateTime
     */
    public function getErrUpdatedAt()
    {
        return $this->errUpdatedAt;
    }
}
