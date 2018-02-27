<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TseTimeSession
 *
 * @ORM\Table(name="tse_time_session", indexes={@ORM\Index(name="tse_user_login", columns={"tse_user_login"}), @ORM\Index(name="tse_time_session", columns={"tse_time_session"})})
 * @ORM\Entity
 */
class TseTimeSession
{
    /**
     * @var integer
     *
     * @ORM\Column(name="tse_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tseId;

    /**
     * @var string
     *
     * @ORM\Column(name="tse_id_session", type="string", length=32, nullable=false)
     */
    private $tseIdSession;

    /**
     * @var string
     *
     * @ORM\Column(name="tse_user_login", type="string", length=50, nullable=false)
     */
    private $tseUserLogin;

    /**
     * @var integer
     *
     * @ORM\Column(name="tse_first_action", type="bigint", nullable=false)
     */
    private $tseFirstAction;

    /**
     * @var integer
     *
     * @ORM\Column(name="tse_last_action", type="bigint", nullable=false)
     */
    private $tseLastAction;

    /**
     * @var integer
     *
     * @ORM\Column(name="tse_time_session", type="bigint", nullable=false)
     */
    private $tseTimeSession;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="tse_created_at", type="datetime", nullable=true)
     */
    private $tseCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="tse_updated_at", type="datetime", nullable=true)
     */
    private $tseUpdatedAt;


    /**
     * Get tseId
     *
     * @return integer
     */
    public function getTseId()
    {
        return $this->tseId;
    }

    /**
     * Set tseIdSession
     *
     * @param string $tseIdSession
     *
     * @return TseTimeSession
     */
    public function setTseIdSession($tseIdSession)
    {
        $this->tseIdSession = $tseIdSession;

        return $this;
    }

    /**
     * Get tseIdSession
     *
     * @return string
     */
    public function getTseIdSession()
    {
        return $this->tseIdSession;
    }

    /**
     * Set tseUserLogin
     *
     * @param string $tseUserLogin
     *
     * @return TseTimeSession
     */
    public function setTseUserLogin($tseUserLogin)
    {
        $this->tseUserLogin = $tseUserLogin;

        return $this;
    }

    /**
     * Get tseUserLogin
     *
     * @return string
     */
    public function getTseUserLogin()
    {
        return $this->tseUserLogin;
    }

    /**
     * Set tseFirstAction
     *
     * @param integer $tseFirstAction
     *
     * @return TseTimeSession
     */
    public function setTseFirstAction($tseFirstAction)
    {
        $this->tseFirstAction = $tseFirstAction;

        return $this;
    }

    /**
     * Get tseFirstAction
     *
     * @return integer
     */
    public function getTseFirstAction()
    {
        return $this->tseFirstAction;
    }

    /**
     * Set tseLastAction
     *
     * @param integer $tseLastAction
     *
     * @return TseTimeSession
     */
    public function setTseLastAction($tseLastAction)
    {
        $this->tseLastAction = $tseLastAction;

        return $this;
    }

    /**
     * Get tseLastAction
     *
     * @return integer
     */
    public function getTseLastAction()
    {
        return $this->tseLastAction;
    }

    /**
     * Set tseTimeSession
     *
     * @param integer $tseTimeSession
     *
     * @return TseTimeSession
     */
    public function setTseTimeSession($tseTimeSession)
    {
        $this->tseTimeSession = $tseTimeSession;

        return $this;
    }

    /**
     * Get tseTimeSession
     *
     * @return integer
     */
    public function getTseTimeSession()
    {
        return $this->tseTimeSession;
    }

    /**
     * Set tseCreatedAt
     *
     * @param \DateTime $tseCreatedAt
     *
     * @return TseTimeSession
     */
    public function setTseCreatedAt($tseCreatedAt)
    {
        $this->tseCreatedAt = $tseCreatedAt;

        return $this;
    }

    /**
     * Get tseCreatedAt
     *
     * @return \DateTime
     */
    public function getTseCreatedAt()
    {
        return $this->tseCreatedAt;
    }

    /**
     * Set tseUpdatedAt
     *
     * @param \DateTime $tseUpdatedAt
     *
     * @return TseTimeSession
     */
    public function setTseUpdatedAt($tseUpdatedAt)
    {
        $this->tseUpdatedAt = $tseUpdatedAt;

        return $this;
    }

    /**
     * Get tseUpdatedAt
     *
     * @return \DateTime
     */
    public function getTseUpdatedAt()
    {
        return $this->tseUpdatedAt;
    }
}
