<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MaiMails
 *
 * @ORM\Table(name="mai_mails", indexes={@ORM\Index(name="mai_user_login", columns={"mai_user_login"}), @ORM\Index(name="mai_mail_date", columns={"mai_mail_date"}), @ORM\Index(name="mai_mail_time", columns={"mai_mail_time"})})
 * @ORM\Entity
 */
class MaiMails
{
    const DEFAULT_MAI_MAIL_NOTIFY = false;
    const DEFAULT_MAI_COPY_TO_SENDER = false;
    const DEFAULT_MAI_MAIL_DATE = '0000-00-00';
    const DEFAULT_MAI_MAIL_TIME = '00:00:00';

    /**
     * @var integer
     *
     * @ORM\Column(name="mai_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $maiId;

    /**
     * @var string
     *
     * @ORM\Column(name="mai_user_login", type="string", length=50, nullable=false)
     */
    private $maiUserLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="mai_mail_from", type="string", length=255, nullable=true)
     */
    private $maiMailFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="mai_mail_to", type="text", length=65535, nullable=false)
     */
    private $maiMailTo;

    /**
     * @var string
     *
     * @ORM\Column(name="mai_mail_copy", type="text", length=65535, nullable=false)
     */
    private $maiMailCopy;

    /**
     * @var string
     *
     * @ORM\Column(name="mai_mail_hidden_copy", type="text", length=65535, nullable=false)
     */
    private $maiMailHiddenCopy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mai_mail_notify", type="boolean", nullable=false)
     */
    private $maiMailNotify = self::DEFAULT_MAI_MAIL_NOTIFY;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mai_mail_copy_to_sender", type="boolean", nullable=false)
     */
    private $maiMailCopyToSender = self::DEFAULT_MAI_COPY_TO_SENDER;

    /**
     * @var string
     *
     * @ORM\Column(name="mai_mail_subject", type="text", length=65535, nullable=false)
     */
    private $maiMailSubject;

    /**
     * @var string
     *
     * @ORM\Column(name="mai_mail_message", type="text", length=65535, nullable=false)
     */
    private $maiMailMessage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mai_mail_date", type="date", nullable=false)
     */
    private $maiMailDate = self::DEFAULT_MAI_MAIL_DATE;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mai_mail_time", type="time", nullable=false)
     */
    private $maiMailTime = self::DEFAULT_MAI_MAIL_TIME;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="mai_created_at", type="datetime", nullable=true)
     */
    private $maiCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="mai_updated_at", type="datetime", nullable=true)
     */
    private $maiUpdatedAt;



    /**
     * Get maiId
     *
     * @return integer
     */
    public function getMaiId()
    {
        return $this->maiId;
    }

    /**
     * Set maiUserLogin
     *
     * @param string $maiUserLogin
     *
     * @return MaiMails
     */
    public function setMaiUserLogin($maiUserLogin)
    {
        $this->maiUserLogin = $maiUserLogin;

        return $this;
    }

    /**
     * Get maiUserLogin
     *
     * @return string
     */
    public function getMaiUserLogin()
    {
        return $this->maiUserLogin;
    }

    /**
     * Set maiMailFrom
     *
     * @param string $maiMailFrom
     *
     * @return MaiMails
     */
    public function setMaiMailFrom($maiMailFrom)
    {
        $this->maiMailFrom = $maiMailFrom;

        return $this;
    }

    /**
     * Get maiMailFrom
     *
     * @return string
     */
    public function getMaiMailFrom()
    {
        return $this->maiMailFrom;
    }

    /**
     * Set maiMailTo
     *
     * @param string $maiMailTo
     *
     * @return MaiMails
     */
    public function setMaiMailTo($maiMailTo)
    {
        $this->maiMailTo = $maiMailTo;

        return $this;
    }

    /**
     * Get maiMailTo
     *
     * @return string
     */
    public function getMaiMailTo()
    {
        return $this->maiMailTo;
    }

    /**
     * Set maiMailCopy
     *
     * @param string $maiMailCopy
     *
     * @return MaiMails
     */
    public function setMaiMailCopy($maiMailCopy)
    {
        $this->maiMailCopy = $maiMailCopy;

        return $this;
    }

    /**
     * Get maiMailCopy
     *
     * @return string
     */
    public function getMaiMailCopy()
    {
        return $this->maiMailCopy;
    }

    /**
     * Set maiMailHiddenCopy
     *
     * @param string $maiMailHiddenCopy
     *
     * @return MaiMails
     */
    public function setMaiMailHiddenCopy($maiMailHiddenCopy)
    {
        $this->maiMailHiddenCopy = $maiMailHiddenCopy;

        return $this;
    }

    /**
     * Get maiMailHiddenCopy
     *
     * @return string
     */
    public function getMaiMailHiddenCopy()
    {
        return $this->maiMailHiddenCopy;
    }

    /**
     * Set maiMailNotify
     *
     * @param boolean $maiMailNotify
     *
     * @return MaiMails
     */
    public function setMaiMailNotify($maiMailNotify)
    {
        $this->maiMailNotify = $maiMailNotify;

        return $this;
    }

    /**
     * Get maiMailNotify
     *
     * @return boolean
     */
    public function isMaiMailNotify()
    {
        return $this->maiMailNotify;
    }

    /**
     * Set maiMailCopyToSender
     *
     * @param boolean $maiMailCopyToSender
     *
     * @return MaiMails
     */
    public function setMaiMailCopyToSender($maiMailCopyToSender)
    {
        $this->maiMailCopyToSender = $maiMailCopyToSender;

        return $this;
    }

    /**
     * Get maiMailCopyToSender
     *
     * @return boolean
     */
    public function isMaiMailCopyToSender()
    {
        return $this->maiMailCopyToSender;
    }

    /**
     * Set maiMailSubject
     *
     * @param string $maiMailSubject
     *
     * @return MaiMails
     */
    public function setMaiMailSubject($maiMailSubject)
    {
        $this->maiMailSubject = $maiMailSubject;

        return $this;
    }

    /**
     * Get maiMailSubject
     *
     * @return string
     */
    public function getMaiMailSubject()
    {
        return $this->maiMailSubject;
    }

    /**
     * Set maiMailMessage
     *
     * @param string $maiMailMessage
     *
     * @return MaiMails
     */
    public function setMaiMailMessage($maiMailMessage)
    {
        $this->maiMailMessage = $maiMailMessage;

        return $this;
    }

    /**
     * Get maiMailMessage
     *
     * @return string
     */
    public function getMaiMailMessage()
    {
        return $this->maiMailMessage;
    }

    /**
     * Set maiMailDate
     *
     * @param \DateTime $maiMailDate
     *
     * @return MaiMails
     */
    public function setMaiMailDate($maiMailDate)
    {
        $this->maiMailDate = $maiMailDate;

        return $this;
    }

    /**
     * Get maiMailDate
     *
     * @return \DateTime
     */
    public function getMaiMailDate()
    {
        return $this->maiMailDate;
    }

    /**
     * Set maiMailTime
     *
     * @param \DateTime $maiMailTime
     *
     * @return MaiMails
     */
    public function setMaiMailTime($maiMailTime)
    {
        $this->maiMailTime = $maiMailTime;

        return $this;
    }

    /**
     * Get maiMailTime
     *
     * @return \DateTime
     */
    public function getMaiMailTime()
    {
        return $this->maiMailTime;
    }

    /**
     * Set maiCreatedAt
     *
     * @param \DateTime $maiCreatedAt
     *
     * @return MaiMails
     */
    public function setMaiCreatedAt($maiCreatedAt)
    {
        $this->maiCreatedAt = $maiCreatedAt;

        return $this;
    }

    /**
     * Get maiCreatedAt
     *
     * @return \DateTime
     */
    public function getMaiCreatedAt()
    {
        return $this->maiCreatedAt;
    }

    /**
     * Set maiUpdatedAt
     *
     * @param \DateTime $maiUpdatedAt
     *
     * @return MaiMails
     */
    public function setMaiUpdatedAt($maiUpdatedAt)
    {
        $this->maiUpdatedAt = $maiUpdatedAt;

        return $this;
    }

    /**
     * Get maiUpdatedAt
     *
     * @return \DateTime
     */
    public function getMaiUpdatedAt()
    {
        return $this->maiUpdatedAt;
    }
}
