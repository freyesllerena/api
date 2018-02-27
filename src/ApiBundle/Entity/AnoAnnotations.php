<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelEtatType;
use ApiBundle\Enum\EnumLabelStatutType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AnoAnnotations
 *
 * @ORM\Table(name="ano_annotations", indexes={@ORM\Index(name="ano_fiche", columns={"ano_fiche"}), @ORM\Index(name="ano_login", columns={"ano_login"}), @ORM\Index(name="ano_created_at", columns={"ano_created_at"}), @ORM\Index(name="ano_etat", columns={"ano_etat"}), @ORM\Index(name="ano_statut", columns={"ano_statut"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\AnoAnnotationsRepository")
 */
class AnoAnnotations
{
    const ERR_DOES_NOT_EXIST = 'errAnoAnnotationsDoesNotExist';
    const ERR_NOT_OWNER = 'errAnoAnnotationsNotOwner';
    const ERR_NOT_ALLOWED_TO_DELETE = 'errAnoAnnotationsNotAllowedToDelete';
    const ERR_LIST_EMPTY = 'errAnoAnnotationsListEmpty';

    /**
     * @var integer
     *
     * @ORM\Column(name="ano_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $anoId;

    /**
     * @var string
     *
     * @ORM\Column(name="ano_etat", type="string", length=10, nullable=false)
     */
    private $anoEtat = EnumLabelEtatType::ACTIVE_ETAT;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "errAnoAnnotationsStatusEmpty")
     * @Assert\Choice(
     *     choices = {"PUBLIQUE", "PRIVEE"},
     *     message = "errAnoAnnotationsIncorrectValue"
     * )
     *
     * @ORM\Column(name="ano_statut", type="string", length=10, nullable=false)
     */
    private $anoStatut = EnumLabelStatutType::PRIVATE_STATUT;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "errAnoAnnotationsTextEmpty")
     *
     * @ORM\Column(name="ano_texte", type="text", length=65535, nullable=false)
     */
    private $anoTexte;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="ano_created_at", type="datetime", nullable=true)
     */
    private $anoCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="ano_updated_at", type="datetime", nullable=true)
     */
    private $anoUpdatedAt;

    /**
     * @var \IfpIndexfichePaperless
     *
     * @ORM\ManyToOne(targetEntity="IfpIndexfichePaperless")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ano_fiche", referencedColumnName="ifp_id", onDelete="CASCADE")
     * })
     */
    private $anoFiche;

    /**
     * @var \UsrUsers
     *
     * @ORM\ManyToOne(targetEntity="UsrUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ano_login", referencedColumnName="usr_login")
     * })
     */
    private $anoLogin;



    /**
     * Get anoId
     *
     * @return integer
     */
    public function getAnoId()
    {
        return $this->anoId;
    }

    /**
     * Set anoEtat
     *
     * @param string $anoEtat
     *
     * @return AnoAnnotations
     */
    public function setAnoEtat($anoEtat)
    {
        $this->anoEtat = $anoEtat;

        return $this;
    }

    /**
     * Get anoEtat
     *
     * @return string
     */
    public function getAnoEtat()
    {
        return $this->anoEtat;
    }

    /**
     * Set anoStatut
     *
     * @param string $anoStatut
     *
     * @return AnoAnnotations
     */
    public function setAnoStatut($anoStatut)
    {
        $this->anoStatut = $anoStatut;

        return $this;
    }

    /**
     * Get anoStatut
     *
     * @return string
     */
    public function getAnoStatut()
    {
        return $this->anoStatut;
    }

    /**
     * Set anoTexte
     *
     * @param string $anoTexte
     *
     * @return AnoAnnotations
     */
    public function setAnoTexte($anoTexte)
    {
        $this->anoTexte = $anoTexte;

        return $this;
    }

    /**
     * Get anoTexte
     *
     * @return string
     */
    public function getAnoTexte()
    {
        return $this->anoTexte;
    }

    /**
     * Set anoCreatedAt
     *
     * @param \DateTime $anoCreatedAt
     *
     * @return AnoAnnotations
     */
    public function setAnoCreatedAt($anoCreatedAt)
    {
        $this->anoCreatedAt = $anoCreatedAt;

        return $this;
    }

    /**
     * Get anoCreatedAt
     *
     * @return \DateTime
     */
    public function getAnoCreatedAt()
    {
        return $this->anoCreatedAt;
    }

    /**
     * Set anoUpdatedAt
     *
     * @param \DateTime $anoUpdatedAt
     *
     * @return AnoAnnotations
     */
    public function setAnoUpdatedAt($anoUpdatedAt)
    {
        $this->anoUpdatedAt = $anoUpdatedAt;

        return $this;
    }

    /**
     * Get anoUpdatedAt
     *
     * @return \DateTime
     */
    public function getAnoUpdatedAt()
    {
        return $this->anoUpdatedAt;
    }

    /**
     * Set anoFiche
     *
     * @param \ApiBundle\Entity\IfpIndexfichePaperless $anoFiche
     *
     * @return AnoAnnotations
     */
    public function setAnoFiche(\ApiBundle\Entity\IfpIndexfichePaperless $anoFiche = null)
    {
        $this->anoFiche = $anoFiche;

        return $this;
    }

    /**
     * Get anoFiche
     *
     * @return \ApiBundle\Entity\IfpIndexfichePaperless
     */
    public function getAnoFiche()
    {
        return $this->anoFiche;
    }

    /**
     * Set anoLogin
     *
     * @param \ApiBundle\Entity\UsrUsers $anoLogin
     *
     * @return AnoAnnotations
     */
    public function setAnoLogin(\ApiBundle\Entity\UsrUsers $anoLogin = null)
    {
        $this->anoLogin = $anoLogin;

        return $this;
    }

    /**
     * Get anoLogin
     *
     * @return \ApiBundle\Entity\UsrUsers
     */
    public function getAnoLogin()
    {
        return $this->anoLogin;
    }
}
