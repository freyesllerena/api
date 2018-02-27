<?php

namespace ApiBundle\Entity;

use ApiBundle\Enum\EnumLabelEmployeeFilterType;
use ApiBundle\Enum\EnumLabelNotificationType;
use ApiBundle\Enum\EnumLabelPeriodType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ComCompletude
 *
 * @ORM\Table(
 *     name="com_completude", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="com_usr_lib", columns={"com_id_user", "com_libelle"})
 *     },
 *     indexes={
 *      @ORM\Index(name="IDX_EF799D0538A88C84", columns={"com_id_user"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ComCompletudeRepository")
 */
class ComCompletude
{
    const DEFAULT_COM_AUTO = false;
    const DEFAULT_COM_AVEC_DOCUMENTS = true;
    const DEFAULT_COM_PRIVEE = false;

    const ERR_DOES_NOT_EXIST = 'errComCompletudeDoesNotExist';
    const ERR_NOT_OWNER = 'errComCompletudeNotOwner';
    const ERR_OWNER_COMPLETUDE_LABEL_EXISTS = 'errComCompletudeLabelExists';
    const ERR_EMAIL_INCORRECT = 'errComCompletudeEmailIncorrect';

    /**
     * @var integer
     *
     * @ORM\Column(name="com_id_completude", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $comIdCompletude;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "errComCompletudeLabelEmpty")
     * @Assert\Length(max = "100", maxMessage = "errComCompletudeLabelMaxCharacters")
     *
     * @ORM\Column(name="com_libelle", type="string", length=100, nullable=false)
     */
    private $comLibelle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="com_privee", type="boolean", nullable=false)
     */
    private $comPrivee = self::DEFAULT_COM_PRIVEE;

    /**
     * @var boolean
     *
     * @ORM\Column(name="com_auto", type="boolean", nullable=false)
     */
    private $comAuto = self::DEFAULT_COM_AUTO;

    /**
     * @var string
     *
     * @ORM\Column(name="com_email", type="text", length=65535, nullable=true)
     */
    private $comEmail;

    /**
     * @var string
     *
     * @Assert\Choice(
     *     choices = {"quotidien", "hebdomadaire", "mensuel"},
     *     message = "errComCompletudeIncorrectPeriodeValue"
     * )
     *
     * @ORM\Column(name="com_periode", type="string", length=12, nullable=false)
     */
    private $comPeriode = EnumLabelPeriodType::DAILY_PERIOD;

    /**
     * @var boolean
     *
     * @ORM\Column(name="com_avec_documents", type="boolean", nullable=false)
     */
    private $comAvecDocuments = self::DEFAULT_COM_AVEC_DOCUMENTS;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "errComCompletudePopulationEmpty")
     * @Assert\Choice(
     *     choices = {"ALL_POP", "PRE_POP", "OUT_POP"},
     *     message = "errComCompletudeIncorrectEmployeeFilter"
     * )
     *
     * @ORM\Column(name="com_population", type="string", length=7, nullable=false)
     */
    private $comPopulation = EnumLabelEmployeeFilterType::ALL_POP;

    /**
     * @var string
     *
     * @ORM\Column(name="com_notification", type="string", length=9, nullable=false)
     */
    private $comNotification = EnumLabelNotificationType::WAITING_NOTIFICATION;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="com_created_at", type="datetime", nullable=true)
     */
    private $comCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="com_updated_at", type="datetime", nullable=true)
     */
    private $comUpdatedAt;

    /**
     * @var UsrUsers
     *
     * @ORM\ManyToOne(targetEntity="UsrUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="com_id_user", referencedColumnName="usr_login")
     * })
     */
    private $comUser;



    /**
     * Get comIdCompletude
     *
     * @return integer
     */
    public function getComIdCompletude()
    {
        return $this->comIdCompletude;
    }

    /**
     * Set comLibelle
     *
     * @param string $comLibelle
     *
     * @return ComCompletude
     */
    public function setComLibelle($comLibelle)
    {
        $this->comLibelle = $comLibelle;

        return $this;
    }

    /**
     * Get comLibelle
     *
     * @return string
     */
    public function getComLibelle()
    {
        return $this->comLibelle;
    }

    /**
     * Set comPrivee
     *
     * @param boolean $comPrivee
     *
     * @return ComCompletude
     */
    public function setComPrivee($comPrivee)
    {
        $this->comPrivee = $comPrivee;

        return $this;
    }

    /**
     * Get comPrivee
     *
     * @return boolean
     */
    public function isComPrivee()
    {
        return $this->comPrivee;
    }

    /**
     * Set comAuto
     *
     * @param boolean $comAuto
     *
     * @return ComCompletude
     */
    public function setComAuto($comAuto)
    {
        $this->comAuto = $comAuto;

        return $this;
    }

    /**
     * Get comAuto
     *
     * @return boolean
     */
    public function isComAuto()
    {
        return $this->comAuto;
    }

    /**
     * Set comEmail
     *
     * @param string $comEmail
     *
     * @return ComCompletude
     */
    public function setComEmail($comEmail)
    {
        $this->comEmail = $comEmail;

        return $this;
    }

    /**
     * Get comEmail
     *
     * @return string
     */
    public function getComEmail()
    {
        return $this->comEmail;
    }

    /**
     * Set comPeriode
     *
     * @param string $comPeriode
     *
     * @return ComCompletude
     */
    public function setComPeriode($comPeriode)
    {
        $this->comPeriode = $comPeriode;

        return $this;
    }

    /**
     * Get comPeriode
     *
     * @return string
     */
    public function getComPeriode()
    {
        return $this->comPeriode;
    }

    /**
     * Set comAvecDocuments
     *
     * @param boolean $comAvecDocuments
     *
     * @return ComCompletude
     */
    public function setComAvecDocuments($comAvecDocuments)
    {
        $this->comAvecDocuments = $comAvecDocuments;

        return $this;
    }

    /**
     * Get comAvecDocuments
     *
     * @return boolean
     */
    public function isComAvecDocuments()
    {
        return $this->comAvecDocuments;
    }

    /**
     * Set comPopulation
     *
     * @param string $comPopulation
     *
     * @return ComCompletude
     */
    public function setComPopulation($comPopulation)
    {
        $this->comPopulation = $comPopulation;

        return $this;
    }

    /**
     * Get comPopulation
     *
     * @return string
     */
    public function getComPopulation()
    {
        return $this->comPopulation;
    }

    /**
     * Set comNotification
     *
     * @param string $comNotification
     *
     * @return ComCompletude
     */
    public function setComNotification($comNotification)
    {
        $this->comNotification = $comNotification;

        return $this;
    }

    /**
     * Get comNotification
     *
     * @return string
     */
    public function getComNotification()
    {
        return $this->comNotification;
    }

    /**
     * Set comCreatedAt
     *
     * @param \DateTime $comCreatedAt
     *
     * @return ComCompletude
     */
    public function setComCreatedAt($comCreatedAt)
    {
        $this->comCreatedAt = $comCreatedAt;

        return $this;
    }

    /**
     * Get comCreatedAt
     *
     * @return \DateTime
     */
    public function getComCreatedAt()
    {
        return $this->comCreatedAt;
    }

    /**
     * Set comUpdatedAt
     *
     * @param \DateTime $comUpdatedAt
     *
     * @return ComCompletude
     */
    public function setComUpdatedAt($comUpdatedAt)
    {
        $this->comUpdatedAt = $comUpdatedAt;

        return $this;
    }

    /**
     * Get comUpdatedAt
     *
     * @return \DateTime
     */
    public function getComUpdatedAt()
    {
        return $this->comUpdatedAt;
    }

    /**
     * Set comUser
     *
     * @param \ApiBundle\Entity\UsrUsers $comUser
     *
     * @return ComCompletude
     */
    public function setComUser($comUser = null)
    {
        $this->comUser = $comUser;

        return $this;
    }

    /**
     * Get comUser
     *
     * @return \ApiBundle\Entity\UsrUsers
     */
    public function getComUser()
    {
        return $this->comUser;
    }
}
