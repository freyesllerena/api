<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IupInterupload
 *
 * @ORM\Table(
 *     name="iup_interupload",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="iup_ticket", columns={"iup_ticket"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\IupInteruploadRepository")
 */
class IupInterupload
{
    const ERR_UPLOAD_NO_BASE_PATH = 'errIupInteruploadNoBasePath';
    const METADATA_PRODUCTION_OK = 'OK';
    const METADATA_PRODUCTION_NON = 'NON';
    const STATUT_OK_PRODUCTION = 'OK_PRODUCTION';
    const STATUT_OK_ARCHIVAGE = 'OK_ARCHIVAGE';
    const ARCHIVING_SYSTEM = 'INTERUPLOAD';

    /**
     * @var integer
     *
     * @ORM\Column(name="iup_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iupId;

    /**
     * @var string
     *
     * @ORM\Column(name="iup_ticket", type="string", length=72, nullable=false)
     */
    private $iupTicket;

    /**
     * @var string
     *
     * @ORM\Column(name="iup_challenge", type="string", length=72, nullable=true)
     */
    private $iupChallenge;

    /**
     * @var string
     *
     * @ORM\Column(name="iup_statut", type="string", length=100, nullable=true)
     */
    private $iupStatut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="iup_date_production", type="datetime", nullable=true)
     */
    private $iupDateProduction;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="iup_date_archivage", type="datetime", nullable=true)
     */
    private $iupDateArchivage;

    /**
     * @var string
     *
     * @ORM\Column(name="iup_config", type="text", length=65535, nullable=true)
     */
    private $iupConfig;

    /**
     * @var string
     *
     * @ORM\Column(name="iup_metadata_creation", type="text", length=65535, nullable=true)
     */
    private $iupMetadataCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="iup_metadata_production", type="text", length=65535, nullable=true)
     */
    private $iupMetadataProduction;

    /**
     * @var string
     *
     * @ORM\Column(name="iup_metadata_archivage", type="text", length=65535, nullable=true)
     */
    private $iupMetadataArchivage;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="iup_created_at", type="datetime", nullable=true)
     */
    private $iupCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="iup_updated_at", type="datetime", nullable=true)
     */
    private $iupUpdatedAt;


    /**
     * Get iupId
     *
     * @return integer
     */
    public function getIupId()
    {
        return $this->iupId;
    }

    /**
     * Set iupTicket
     *
     * @param string $iupTicket
     *
     * @return IupInterupload
     */
    public function setIupTicket($iupTicket)
    {
        $this->iupTicket = $iupTicket;

        return $this;
    }

    /**
     * Get iupTicket
     *
     * @return string
     */
    public function getIupTicket()
    {
        return $this->iupTicket;
    }

    /**
     * Set iupChallenge
     *
     * @param string $iupChallenge
     *
     * @return IupInterupload
     */
    public function setIupChallenge($iupChallenge)
    {
        $this->iupChallenge = $iupChallenge;

        return $this;
    }

    /**
     * Get iupChallenge
     *
     * @return string
     */
    public function getIupChallenge()
    {
        return $this->iupChallenge;
    }

    /**
     * Set iupStatut
     *
     * @param string $iupStatut
     *
     * @return IupInterupload
     */
    public function setIupStatut($iupStatut)
    {
        $this->iupStatut = $iupStatut;

        return $this;
    }

    /**
     * Get iupStatut
     *
     * @return string
     */
    public function getIupStatut()
    {
        return $this->iupStatut;
    }

    /**
     * Set iupDateProduction
     *
     * @param \DateTime $iupDateProduction
     *
     * @return IupInterupload
     */
    public function setIupDateProduction($iupDateProduction)
    {
        $this->iupDateProduction = $iupDateProduction;

        return $this;
    }

    /**
     * Get iupDateProduction
     *
     * @return \DateTime
     */
    public function getIupDateProduction()
    {
        return $this->iupDateProduction;
    }

    /**
     * Set iupDateArchivage
     *
     * @param \DateTime $iupDateArchivage
     *
     * @return IupInterupload
     */
    public function setIupDateArchivage($iupDateArchivage)
    {
        $this->iupDateArchivage = $iupDateArchivage;

        return $this;
    }

    /**
     * Get iupDateArchivage
     *
     * @return \DateTime
     */
    public function getIupDateArchivage()
    {
        return $this->iupDateArchivage;
    }

    /**
     * Set iupConfig
     *
     * @param string $iupConfig
     *
     * @return IupInterupload
     */
    public function setIupConfig($iupConfig)
    {
        $this->iupConfig = $iupConfig;

        return $this;
    }

    /**
     * Get iupConfig
     *
     * @return string
     */
    public function getIupConfig()
    {
        return $this->iupConfig;
    }

    /**
     * Set iupMetadataCreation
     *
     * @param string $iupMetadataCreation
     *
     * @return IupInterupload
     */
    public function setIupMetadataCreation($iupMetadataCreation)
    {
        $this->iupMetadataCreation = $iupMetadataCreation;

        return $this;
    }

    /**
     * Get iupMetadataCreation
     *
     * @return string
     */
    public function getIupMetadataCreation()
    {
        return $this->iupMetadataCreation;
    }

    /**
     * Set iupMetadataProduction
     *
     * @param string $iupMetadataProduction
     *
     * @return IupInterupload
     */
    public function setIupMetadataProduction($iupMetadataProduction)
    {
        $this->iupMetadataProduction = $iupMetadataProduction;

        return $this;
    }

    /**
     * Get iupMetadataProduction
     *
     * @return string
     */
    public function getIupMetadataProduction()
    {
        return $this->iupMetadataProduction;
    }

    /**
     * Set iupMetadataArchivage
     *
     * @param string $iupMetadataArchivage
     *
     * @return IupInterupload
     */
    public function setIupMetadataArchivage($iupMetadataArchivage)
    {
        $this->iupMetadataArchivage = $iupMetadataArchivage;

        return $this;
    }

    /**
     * Get iupMetadataArchivage
     *
     * @return string
     */
    public function getIupMetadataArchivage()
    {
        return $this->iupMetadataArchivage;
    }

    /**
     * Set iupCreatedAt
     *
     * @param \DateTime $iupCreatedAt
     *
     * @return IupInterupload
     */
    public function setIupCreatedAt($iupCreatedAt)
    {
        $this->iupCreatedAt = $iupCreatedAt;

        return $this;
    }

    /**
     * Get iupCreatedAt
     *
     * @return \DateTime
     */
    public function getIupCreatedAt()
    {
        return $this->iupCreatedAt;
    }

    /**
     * Set iupUpdatedAt
     *
     * @param \DateTime $iupUpdatedAt
     *
     * @return IupInterupload
     */
    public function setIupUpdatedAt($iupUpdatedAt)
    {
        $this->iupUpdatedAt = $iupUpdatedAt;

        return $this;
    }

    /**
     * Get iupUpdatedAt
     *
     * @return \DateTime
     */
    public function getIupUpdatedAt()
    {
        return $this->iupUpdatedAt;
    }
}
