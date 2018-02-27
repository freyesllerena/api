<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelEtatType;
use ApiBundle\Enum\EnumLabelStatutType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AdoAnnotationsDossier
 *
 * @ORM\Table(name="ado_annotations_dossier", indexes={@ORM\Index(name="ado_folder", columns={"ado_folder"}), @ORM\Index(name="ado_login", columns={"ado_login"}), @ORM\Index(name="ado_created_at", columns={"ado_created_at"}), @ORM\Index(name="ado_etat", columns={"ado_etat"}), @ORM\Index(name="ado_statut", columns={"ado_statut"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\AdoAnnotationsDossierRepository")
 */
class AdoAnnotationsDossier
{
    const ERR_LIST_EMPTY = 'errAdoAnnotationsDossierListEmpty';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="ado_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adoId;

    /**
     * @var string
     *
     * @ORM\Column(name="ado_etat", type="string", length=10, nullable=false)
     */
    private $adoEtat = EnumLabelEtatType::ACTIVE_ETAT;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "errAnoAnnotationsStatusEmpty")
     * @Assert\Choice(
     *     choices = {"PUBLIQUE", "PRIVEE"},
     *     message = "errAnoAnnotationsIncorrectValue"
     * )
     *
     * @ORM\Column(name="ado_statut", type="string", length=10, nullable=false)
     */
    private $adoStatut = EnumLabelStatutType::PRIVATE_STATUT;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "errAnoAnnotationsTextEmpty")
     *
     * @ORM\Column(name="ado_texte", type="text", length=65535, nullable=false)
     */
    private $adoTexte;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="ado_created_at", type="datetime", nullable=true)
     */
    private $adoCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="ado_updated_at", type="datetime", nullable=true)
     */
    private $adoUpdatedAt;

    /**
     * @var \FolFolder
     *
     * @ORM\ManyToOne(targetEntity="FolFolder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ado_folder", referencedColumnName="fol_id", onDelete="CASCADE")
     * })
     */
    private $adoFolder;

    /**
     * @var \UsrUsers
     *
     * @ORM\ManyToOne(targetEntity="UsrUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ado_login", referencedColumnName="usr_login")
     * })
     */
    private $adoLogin;



    /**
     * Get adoId
     *
     * @return integer
     */
    public function getAdoId()
    {
        return $this->adoId;
    }

    /**
     * Set adoEtat
     *
     * @param string $adoEtat
     *
     * @return AdoAnnotationsDossier
     */
    public function setAdoEtat($adoEtat)
    {
        $this->adoEtat = $adoEtat;

        return $this;
    }

    /**
     * Get adoEtat
     *
     * @return string
     */
    public function getAdoEtat()
    {
        return $this->adoEtat;
    }

    /**
     * Set adoStatut
     *
     * @param string $adoStatut
     *
     * @return AdoAnnotationsDossier
     */
    public function setAdoStatut($adoStatut)
    {
        $this->adoStatut = $adoStatut;

        return $this;
    }

    /**
     * Get adoStatut
     *
     * @return string
     */
    public function getAdoStatut()
    {
        return $this->adoStatut;
    }

    /**
     * Set adoTexte
     *
     * @param string $adoTexte
     *
     * @return AdoAnnotationsDossier
     */
    public function setAdoTexte($adoTexte)
    {
        $this->adoTexte = $adoTexte;

        return $this;
    }

    /**
     * Get adoTexte
     *
     * @return string
     */
    public function getAdoTexte()
    {
        return $this->adoTexte;
    }

    /**
     * Set adoCreatedAt
     *
     * @param \DateTime $adoCreatedAt
     *
     * @return AdoAnnotationsDossier
     */
    public function setAdoCreatedAt($adoCreatedAt)
    {
        $this->adoCreatedAt = $adoCreatedAt;

        return $this;
    }

    /**
     * Get adoCreatedAt
     *
     * @return \DateTime
     */
    public function getAdoCreatedAt()
    {
        return $this->adoCreatedAt;
    }

    /**
     * Set adoUpdatedAt
     *
     * @param \DateTime $adoUpdatedAt
     *
     * @return AdoAnnotationsDossier
     */
    public function setAdoUpdatedAt($adoUpdatedAt)
    {
        $this->adoUpdatedAt = $adoUpdatedAt;

        return $this;
    }

    /**
     * Get adoUpdatedAt
     *
     * @return \DateTime
     */
    public function getAdoUpdatedAt()
    {
        return $this->adoUpdatedAt;
    }

    /**
     * Set adoFolder
     *
     * @param \ApiBundle\Entity\FolFolder $adoFolder
     *
     * @return AdoAnnotationsDossier
     */
    public function setAdoFolder(\ApiBundle\Entity\FolFolder $adoFolder = null)
    {
        $this->adoFolder = $adoFolder;

        return $this;
    }

    /**
     * Get adoFolder
     *
     * @return \ApiBundle\Entity\FolFolder
     */
    public function getAdoFolder()
    {
        return $this->adoFolder;
    }

    /**
     * Set adoLogin
     *
     * @param \ApiBundle\Entity\UsrUsers $adoLogin
     *
     * @return AdoAnnotationsDossier
     */
    public function setAdoLogin(\ApiBundle\Entity\UsrUsers $adoLogin = null)
    {
        $this->adoLogin = $adoLogin;

        return $this;
    }

    /**
     * Get adoLogin
     *
     * @return \ApiBundle\Entity\UsrUsers
     */
    public function getAdoLogin()
    {
        return $this->adoLogin;
    }
}
