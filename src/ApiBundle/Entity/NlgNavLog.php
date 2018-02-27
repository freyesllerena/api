<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelYesNoEmptyType;

/**
 * NlgNavLog
 *
 * @ORM\Table(name="nlg_nav_log", indexes={@ORM\Index(name="nlg_nav", columns={"nlg_nav"})})
 * @ORM\Entity
 */
class NlgNavLog
{
    const DEFAULT_NLG_PAGES = 0;
    const DEFAULT_NLG_OCTETS = 0;
    const DEFAULT_NLG_FACTURABLE = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="nlg_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nlgId;

    /**
     * @var string
     *
     * @ORM\Column(name="nlg_nav", type="string", length=50, nullable=true)
     */
    private $nlgNav;

    /**
     * @var string
     *
     * @ORM\Column(name="nlg_user", type="string", length=50, nullable=true)
     */
    private $nlgUser;

    /**
     * @var string
     *
     * @ORM\Column(name="nlg_groupe", type="string", length=255, nullable=true)
     */
    private $nlgGroupe;

    /**
     * @var string
     *
     * @ORM\Column(name="nlg_orsid", type="string", length=1, nullable=true)
     */
    private $nlgOrsid = EnumLabelYesNoEmptyType::EMPTY_VALUE_ENUM;

    /**
     * @var integer
     *
     * @ORM\Column(name="nlg_pages", type="integer", nullable=false)
     */
    private $nlgPages = self::DEFAULT_NLG_PAGES;

    /**
     * @var integer
     *
     * @ORM\Column(name="nlg_octets", type="integer", nullable=false)
     */
    private $nlgOctets = self::DEFAULT_NLG_OCTETS;

    /**
     * @var string
     *
     * @ORM\Column(name="nlg_id_session", type="string", length=50, nullable=true)
     */
    private $nlgIdSession;

    /**
     * @var string
     *
     * @ORM\Column(name="nlg_info", type="text", length=65535, nullable=false)
     */
    private $nlgInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="nlg_adresse_ip", type="string", length=15, nullable=true)
     */
    private $nlgAdresseIp;

    /**
     * @var string
     *
     * @ORM\Column(name="nlg_user_agent", type="string", length=255, nullable=true)
     */
    private $nlgUserAgent;

    /**
     * @var boolean
     *
     * @ORM\Column(name="nlg_facturable", type="boolean", nullable=false)
     */
    private $nlgFacturable = self::DEFAULT_NLG_FACTURABLE;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="nlg_created_at", type="datetime", nullable=true)
     */
    private $nlgCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="nlg_updated_at", type="datetime", nullable=true)
     */
    private $nlgUpdatedAt;


    /**
     * Get nlgId
     *
     * @return integer
     */
    public function getNlgId()
    {
        return $this->nlgId;
    }

    /**
     * Set nlgNav
     *
     * @param string $nlgNav
     *
     * @return NlgNavLog
     */
    public function setNlgNav($nlgNav)
    {
        $this->nlgNav = $nlgNav;

        return $this;
    }

    /**
     * Get nlgNav
     *
     * @return string
     */
    public function getNlgNav()
    {
        return $this->nlgNav;
    }

    /**
     * Set nlgUser
     *
     * @param string $nlgUser
     *
     * @return NlgNavLog
     */
    public function setNlgUser($nlgUser)
    {
        $this->nlgUser = $nlgUser;

        return $this;
    }

    /**
     * Get nlgUser
     *
     * @return string
     */
    public function getNlgUser()
    {
        return $this->nlgUser;
    }

    /**
     * Set nlgGroupe
     *
     * @param string $nlgGroupe
     *
     * @return NlgNavLog
     */
    public function setNlgGroupe($nlgGroupe)
    {
        $this->nlgGroupe = $nlgGroupe;

        return $this;
    }

    /**
     * Get nlgGroupe
     *
     * @return string
     */
    public function getNlgGroupe()
    {
        return $this->nlgGroupe;
    }

    /**
     * Set nlgOrsid
     *
     * @param string $nlgOrsid
     *
     * @return NlgNavLog
     */
    public function setNlgOrsid($nlgOrsid)
    {
        $this->nlgOrsid = $nlgOrsid;

        return $this;
    }

    /**
     * Get nlgOrsid
     *
     * @return string
     */
    public function getNlgOrsid()
    {
        return $this->nlgOrsid;
    }

    /**
     * Set nlgPages
     *
     * @param integer $nlgPages
     *
     * @return NlgNavLog
     */
    public function setNlgPages($nlgPages)
    {
        $this->nlgPages = $nlgPages;

        return $this;
    }

    /**
     * Get nlgPages
     *
     * @return integer
     */
    public function getNlgPages()
    {
        return $this->nlgPages;
    }

    /**
     * Set nlgOctets
     *
     * @param integer $nlgOctets
     *
     * @return NlgNavLog
     */
    public function setNlgOctets($nlgOctets)
    {
        $this->nlgOctets = $nlgOctets;

        return $this;
    }

    /**
     * Get nlgOctets
     *
     * @return integer
     */
    public function getNlgOctets()
    {
        return $this->nlgOctets;
    }

    /**
     * Set nlgIdSession
     *
     * @param string $nlgIdSession
     *
     * @return NlgNavLog
     */
    public function setNlgIdSession($nlgIdSession)
    {
        $this->nlgIdSession = $nlgIdSession;

        return $this;
    }

    /**
     * Get nlgIdSession
     *
     * @return string
     */
    public function getNlgIdSession()
    {
        return $this->nlgIdSession;
    }

    /**
     * Set nlgInfo
     *
     * @param string $nlgInfo
     *
     * @return NlgNavLog
     */
    public function setNlgInfo($nlgInfo)
    {
        $this->nlgInfo = $nlgInfo;

        return $this;
    }

    /**
     * Get nlgInfo
     *
     * @return string
     */
    public function getNlgInfo()
    {
        return $this->nlgInfo;
    }

    /**
     * Set nlgAdresseIp
     *
     * @param string $nlgAdresseIp
     *
     * @return NlgNavLog
     */
    public function setNlgAdresseIp($nlgAdresseIp)
    {
        $this->nlgAdresseIp = $nlgAdresseIp;

        return $this;
    }

    /**
     * Get nlgAdresseIp
     *
     * @return string
     */
    public function getNlgAdresseIp()
    {
        return $this->nlgAdresseIp;
    }

    /**
     * Set nlgUserAgent
     *
     * @param string $nlgUserAgent
     *
     * @return NlgNavLog
     */
    public function setNlgUserAgent($nlgUserAgent)
    {
        $this->nlgUserAgent = $nlgUserAgent;

        return $this;
    }

    /**
     * Get nlgUserAgent
     *
     * @return string
     */
    public function getNlgUserAgent()
    {
        return $this->nlgUserAgent;
    }

    /**
     * Set nlgFacturable
     *
     * @param boolean $nlgFacturable
     *
     * @return NlgNavLog
     */
    public function setNlgFacturable($nlgFacturable)
    {
        $this->nlgFacturable = $nlgFacturable;

        return $this;
    }

    /**
     * Get nlgFacturable
     *
     * @return boolean
     */
    public function isNlgFacturable()
    {
        return $this->nlgFacturable;
    }

    /**
     * Set nlgCreatedAt
     *
     * @param \DateTime $nlgCreatedAt
     *
     * @return NlgNavLog
     */
    public function setNlgCreatedAt($nlgCreatedAt)
    {
        $this->nlgCreatedAt = $nlgCreatedAt;

        return $this;
    }

    /**
     * Get nlgCreatedAt
     *
     * @return \DateTime
     */
    public function getNlgCreatedAt()
    {
        return $this->nlgCreatedAt;
    }

    /**
     * Set nlgUpdatedAt
     *
     * @param \DateTime $nlgUpdatedAt
     *
     * @return NlgNavLog
     */
    public function setNlgUpdatedAt($nlgUpdatedAt)
    {
        $this->nlgUpdatedAt = $nlgUpdatedAt;

        return $this;
    }

    /**
     * Get nlgUpdatedAt
     *
     * @return \DateTime
     */
    public function getNlgUpdatedAt()
    {
        return $this->nlgUpdatedAt;
    }
}
