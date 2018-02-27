<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SesSessionsOpn
 *
 * @ORM\Table(name="ses_sessions_opn", indexes={@ORM\Index(name="ses_id_session", columns={"ses_id_session"})})
 * @ORM\Entity
 */
class SesSessionsOpn
{
    const DEFAULT_SES_FIRST_ACTION = 0;
    const DEFAULT_SES_LAST_ACTION = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="ses_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sesId;

    /**
     * @var string
     *
     * @ORM\Column(name="ses_id_session", type="string", length=32, nullable=true)
     */
    private $sesIdSession;

    /**
     * @var string
     *
     * @ORM\Column(name="ses_user_login", type="string", length=50, nullable=false)
     */
    private $sesUserLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="ses_machine", type="text", length=65535, nullable=false)
     */
    private $sesMachine;

    /**
     * @var integer
     *
     * @ORM\Column(name="ses_first_action", type="bigint", nullable=false)
     */
    private $sesFirstAction = self::DEFAULT_SES_FIRST_ACTION;

    /**
     * @var integer
     *
     * @ORM\Column(name="ses_last_action", type="bigint", nullable=false)
     */
    private $sesLastAction = self::DEFAULT_SES_LAST_ACTION;

    /**
     * @var string
     *
     * @ORM\Column(name="ses_variables", type="text", length=65535, nullable=false)
     */
    private $sesVariables;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="ses_created_at", type="datetime", nullable=true)
     */
    private $sesCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="ses_updated_at", type="datetime", nullable=true)
     */
    private $sesUpdatedAt;


    /**
     * Get sesId
     *
     * @return integer
     */
    public function getSesId()
    {
        return $this->sesId;
    }

    /**
     * Set sesIdSession
     *
     * @param string $sesIdSession
     *
     * @return SesSessionsOpn
     */
    public function setSesIdSession($sesIdSession)
    {
        $this->sesIdSession = $sesIdSession;

        return $this;
    }

    /**
     * Get sesIdSession
     *
     * @return string
     */
    public function getSesIdSession()
    {
        return $this->sesIdSession;
    }

    /**
     * Set sesUserLogin
     *
     * @param string $sesUserLogin
     *
     * @return SesSessionsOpn
     */
    public function setSesUserLogin($sesUserLogin)
    {
        $this->sesUserLogin = $sesUserLogin;

        return $this;
    }

    /**
     * Get sesUserLogin
     *
     * @return string
     */
    public function getSesUserLogin()
    {
        return $this->sesUserLogin;
    }

    /**
     * Set sesMachine
     *
     * @param string $sesMachine
     *
     * @return SesSessionsOpn
     */
    public function setSesMachine($sesMachine)
    {
        $this->sesMachine = $sesMachine;

        return $this;
    }

    /**
     * Get sesMachine
     *
     * @return string
     */
    public function getSesMachine()
    {
        return $this->sesMachine;
    }

    /**
     * Set sesFirstAction
     *
     * @param integer $sesFirstAction
     *
     * @return SesSessionsOpn
     */
    public function setSesFirstAction($sesFirstAction)
    {
        $this->sesFirstAction = $sesFirstAction;

        return $this;
    }

    /**
     * Get sesFirstAction
     *
     * @return integer
     */
    public function getSesFirstAction()
    {
        return $this->sesFirstAction;
    }

    /**
     * Set sesLastAction
     *
     * @param integer $sesLastAction
     *
     * @return SesSessionsOpn
     */
    public function setSesLastAction($sesLastAction)
    {
        $this->sesLastAction = $sesLastAction;

        return $this;
    }

    /**
     * Get sesLastAction
     *
     * @return integer
     */
    public function getSesLastAction()
    {
        return $this->sesLastAction;
    }

    /**
     * Set sesVariables
     *
     * @param string $sesVariables
     *
     * @return SesSessionsOpn
     */
    public function setSesVariables($sesVariables)
    {
        $this->sesVariables = $sesVariables;

        return $this;
    }

    /**
     * Get sesVariables
     *
     * @return string
     */
    public function getSesVariables()
    {
        return $this->sesVariables;
    }

    /**
     * Set sesCreatedAt
     *
     * @param \DateTime $sesCreatedAt
     *
     * @return SesSessionsOpn
     */
    public function setSesCreatedAt($sesCreatedAt)
    {
        $this->sesCreatedAt = $sesCreatedAt;

        return $this;
    }

    /**
     * Get sesCreatedAt
     *
     * @return \DateTime
     */
    public function getSesCreatedAt()
    {
        return $this->sesCreatedAt;
    }

    /**
     * Set sesUpdatedAt
     *
     * @param \DateTime $sesUpdatedAt
     *
     * @return SesSessionsOpn
     */
    public function setSesUpdatedAt($sesUpdatedAt)
    {
        $this->sesUpdatedAt = $sesUpdatedAt;

        return $this;
    }

    /**
     * Get sesUpdatedAt
     *
     * @return \DateTime
     */
    public function getSesUpdatedAt()
    {
        return $this->sesUpdatedAt;
    }
}
