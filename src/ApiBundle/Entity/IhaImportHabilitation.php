<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IhaImportHabilitation
 *
 * @ORM\Table(name="iha_import_habilitation", indexes={@ORM\Index(name="fk_iha_rap", columns={"iha_id_rapport"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\IhaImportHabilitationRepository")
 */
class IhaImportHabilitation
{
    const DEFAULT_IHA_DONE = 0;
    const DEFAULT_IHA_SUCCESS = 0;
    const DEFAULT_IHA_ERROR = 0;

    const ERR_IHA_HABILITATION_IMPORT_FAILED = 'errIhaImportHabilitationFailed';
    const INFO_IHA_HABILITATION_IMPORT_WAS_SUCCESSFUL = 'infoIhaImportHabilitationWasSuccessful';

    /**
     * @var integer
     *
     * @ORM\Column(name="iha_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ihaId;

    /**
     * @var integer
     *
     * @ORM\Column(name="iha_traite", type="smallint", nullable=false)
     */
    private $ihaTraite = self::DEFAULT_IHA_DONE;

    /**
     * @var integer
     *
     * @ORM\Column(name="iha_succes", type="smallint", nullable=false)
     */
    private $ihaSucces = self::DEFAULT_IHA_SUCCESS;

    /**
     * @var integer
     *
     * @ORM\Column(name="iha_erreur", type="smallint", nullable=false)
     */
    private $ihaErreur = self::DEFAULT_IHA_ERROR;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="iha_created_at", type="datetime", nullable=true)
     */
    private $ihaCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="iha_updated_at", type="datetime", nullable=true)
     */
    private $ihaUpdatedAt;

    /**
     * @var RapRapport
     *
     * @ORM\OneToOne(targetEntity="RapRapport", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iha_id_rapport", referencedColumnName="rap_id")
     * })
     */
    private $ihaRapport;


    /**
     * Get ihaId
     *
     * @return integer
     */
    public function getIhaId()
    {
        return $this->ihaId;
    }

    /**
     * Set ihaTraite
     *
     * @param integer $ihaTraite
     *
     * @return IhaImportHabilitation
     */
    public function setIhaTraite($ihaTraite)
    {
        $this->ihaTraite = $ihaTraite;

        return $this;
    }

    /**
     * Get ihaTraite
     *
     * @return integer
     */
    public function getIhaTraite()
    {
        return $this->ihaTraite;
    }

    /**
     * Set ihaSucces
     *
     * @param integer $ihaSucces
     *
     * @return IhaImportHabilitation
     */
    public function setIhaSucces($ihaSucces)
    {
        $this->ihaSucces = $ihaSucces;

        return $this;
    }

    /**
     * Get ihaSucces
     *
     * @return integer
     */
    public function getIhaSucces()
    {
        return $this->ihaSucces;
    }

    /**
     * Set ihaErreur
     *
     * @param integer $ihaErreur
     *
     * @return IhaImportHabilitation
     */
    public function setIhaErreur($ihaErreur)
    {
        $this->ihaErreur = $ihaErreur;

        return $this;
    }

    /**
     * Get ihaErreur
     *
     * @return integer
     */
    public function getIhaErreur()
    {
        return $this->ihaErreur;
    }

    /**
     * Set ihaCreatedAt
     *
     * @param \DateTime $ihaCreatedAt
     *
     * @return IhaImportHabilitation
     */
    public function setIhaCreatedAt($ihaCreatedAt)
    {
        $this->ihaCreatedAt = $ihaCreatedAt;

        return $this;
    }

    /**
     * Get ihaCreatedAt
     *
     * @return \DateTime
     */
    public function getIhaCreatedAt()
    {
        return $this->ihaCreatedAt;
    }

    /**
     * Set ihaUpdatedAt
     *
     * @param \DateTime $ihaUpdatedAt
     *
     * @return IhaImportHabilitation
     */
    public function setIhaUpdatedAt($ihaUpdatedAt)
    {
        $this->ihaUpdatedAt = $ihaUpdatedAt;

        return $this;
    }

    /**
     * Get ihaUpdatedAt
     *
     * @return \DateTime
     */
    public function getIhaUpdatedAt()
    {
        return $this->ihaUpdatedAt;
    }

    /**
     * Set ihaRapport
     *
     * @param \ApiBundle\Entity\RapRapport $ihaRapport
     *
     * @return IhaImportHabilitation
     */
    public function setIhaRapport(RapRapport $ihaRapport = null)
    {
        $this->ihaRapport = $ihaRapport;

        return $this;
    }

    /**
     * Get ihaRapport
     *
     * @return \ApiBundle\Entity\RapRapport
     */
    public function getIhaRapport()
    {
        return $this->ihaRapport;
    }
}
