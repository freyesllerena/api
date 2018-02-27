<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IanIdxAnomalies
 *
 * @ORM\Table(name="ian_idx_anomalies")
 * @ORM\Entity
 */
class IanIdxAnomalies
{
    const DEFAULT_IAN_INDIV = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="ian_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ianId;

    /**
     * @var string
     *
     * @ORM\Column(name="ian_anomalie", type="string", length=100, nullable=false)
     */
    private $ianAnomalie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ian_date", type="date", nullable=false)
     */
    private $ianDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ian_ref_adp", type="string", length=50, nullable=true)
     */
    private $ianRefAdp;

    /**
     * @var string
     *
     * @ORM\Column(name="ian_type", type="string", length=50, nullable=true)
     */
    private $ianType;

    /**
     * @var string
     *
     * @ORM\Column(name="ian_matricule", type="string", length=50, nullable=true)
     */
    private $ianMatricule;

    /**
     * @var string
     *
     * @ORM\Column(name="ian_etat_archive", type="string", length=50, nullable=true)
     */
    private $ianEtatArchive;

    /**
     * @var string
     *
     * @ORM\Column(name="ian_fichier", type="string", length=255, nullable=false)
     */
    private $ianFichier;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ian_indiv", type="boolean", nullable=false)
     */
    private $ianIndiv = self::DEFAULT_IAN_INDIV;

    /**
     * @var string
     *
     * @ORM\Column(name="ian_nom_prenom", type="string", length=255, nullable=true)
     */
    private $ianNomPrenom;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="ian_created_at", type="datetime", nullable=true)
     */
    private $ianCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="ian_updated_at", type="datetime", nullable=true)
     */
    private $ianUpdatedAt;


    /**
     * Get ianId
     *
     * @return integer
     */
    public function getIanId()
    {
        return $this->ianId;
    }

    /**
     * Set ianAnomalie
     *
     * @param string $ianAnomalie
     *
     * @return IanIdxAnomalies
     */
    public function setIanAnomalie($ianAnomalie)
    {
        $this->ianAnomalie = $ianAnomalie;

        return $this;
    }

    /**
     * Get ianAnomalie
     *
     * @return string
     */
    public function getIanAnomalie()
    {
        return $this->ianAnomalie;
    }

    /**
     * Set ianDate
     *
     * @param \DateTime $ianDate
     *
     * @return IanIdxAnomalies
     */
    public function setIanDate($ianDate)
    {
        $this->ianDate = $ianDate;

        return $this;
    }

    /**
     * Get ianDate
     *
     * @return \DateTime
     */
    public function getIanDate()
    {
        return $this->ianDate;
    }

    /**
     * Set ianRefAdp
     *
     * @param string $ianRefAdp
     *
     * @return IanIdxAnomalies
     */
    public function setIanRefAdp($ianRefAdp)
    {
        $this->ianRefAdp = $ianRefAdp;

        return $this;
    }

    /**
     * Get ianRefAdp
     *
     * @return string
     */
    public function getIanRefAdp()
    {
        return $this->ianRefAdp;
    }

    /**
     * Set ianType
     *
     * @param string $ianType
     *
     * @return IanIdxAnomalies
     */
    public function setIanType($ianType)
    {
        $this->ianType = $ianType;

        return $this;
    }

    /**
     * Get ianType
     *
     * @return string
     */
    public function getIanType()
    {
        return $this->ianType;
    }

    /**
     * Set ianMatricule
     *
     * @param string $ianMatricule
     *
     * @return IanIdxAnomalies
     */
    public function setIanMatricule($ianMatricule)
    {
        $this->ianMatricule = $ianMatricule;

        return $this;
    }

    /**
     * Get ianMatricule
     *
     * @return string
     */
    public function getIanMatricule()
    {
        return $this->ianMatricule;
    }

    /**
     * Set ianEtatArchive
     *
     * @param string $ianEtatArchive
     *
     * @return IanIdxAnomalies
     */
    public function setIanEtatArchive($ianEtatArchive)
    {
        $this->ianEtatArchive = $ianEtatArchive;

        return $this;
    }

    /**
     * Get ianEtatArchive
     *
     * @return string
     */
    public function getIanEtatArchive()
    {
        return $this->ianEtatArchive;
    }

    /**
     * Set ianFichier
     *
     * @param string $ianFichier
     *
     * @return IanIdxAnomalies
     */
    public function setIanFichier($ianFichier)
    {
        $this->ianFichier = $ianFichier;

        return $this;
    }

    /**
     * Get ianFichier
     *
     * @return string
     */
    public function getIanFichier()
    {
        return $this->ianFichier;
    }

    /**
     * Set ianIndiv
     *
     * @param boolean $ianIndiv
     *
     * @return IanIdxAnomalies
     */
    public function setIanIndiv($ianIndiv)
    {
        $this->ianIndiv = $ianIndiv;

        return $this;
    }

    /**
     * Get ianIndiv
     *
     * @return boolean
     */
    public function isIanIndiv()
    {
        return $this->ianIndiv;
    }

    /**
     * Set ianNomPrenom
     *
     * @param string $ianNomPrenom
     *
     * @return IanIdxAnomalies
     */
    public function setIanNomPrenom($ianNomPrenom)
    {
        $this->ianNomPrenom = $ianNomPrenom;

        return $this;
    }

    /**
     * Get ianNomPrenom
     *
     * @return string
     */
    public function getIanNomPrenom()
    {
        return $this->ianNomPrenom;
    }

    /**
     * Set ianCreatedAt
     *
     * @param \DateTime $ianCreatedAt
     *
     * @return IanIdxAnomalies
     */
    public function setIanCreatedAt($ianCreatedAt)
    {
        $this->ianCreatedAt = $ianCreatedAt;

        return $this;
    }

    /**
     * Get ianCreatedAt
     *
     * @return \DateTime
     */
    public function getIanCreatedAt()
    {
        return $this->ianCreatedAt;
    }

    /**
     * Set ianUpdatedAt
     *
     * @param \DateTime $ianUpdatedAt
     *
     * @return IanIdxAnomalies
     */
    public function setIanUpdatedAt($ianUpdatedAt)
    {
        $this->ianUpdatedAt = $ianUpdatedAt;

        return $this;
    }

    /**
     * Get ianUpdatedAt
     *
     * @return \DateTime
     */
    public function getIanUpdatedAt()
    {
        return $this->ianUpdatedAt;
    }
}
