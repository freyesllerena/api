<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TcfTraceCfec
 *
 * @ORM\Table(name="tcf_trace_cfec", indexes={@ORM\Index(name="tcf_vdm_localisation", columns={"tcf_vdm_localisation"})})
 * @ORM\Entity
 */
class TcfTraceCfec
{
    /**
     * @var integer
     *
     * @ORM\Column(name="tcf_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tcfId;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_nom_fichier", type="string", length=255, nullable=false)
     */
    private $tcfNomFichier;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_num_fdr", type="string", length=10, nullable=false)
     */
    private $tcfNumFdr;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_vdm_localisation", type="string", length=50, nullable=false)
     */
    private $tcfVdmLocalisation;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_nom_lot", type="string", length=255, nullable=false)
     */
    private $tcfNomLot;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_identifiant_unique", type="text", length=65535, nullable=false)
     */
    private $tcfIdentifiantUnique;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_empreinte_archivage", type="text", length=65535, nullable=false)
     */
    private $tcfEmpreinteArchivage;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_archive_num_cfe", type="string", length=10, nullable=false)
     */
    private $tcfArchiveNumCfe;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_archive_num_cfec", type="string", length=10, nullable=false)
     */
    private $tcfArchiveNumCfec;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_archive_chemin_cfec", type="text", length=65535, nullable=false)
     */
    private $tcfArchiveCheminCfec;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_archive_serial_number", type="string", length=255, nullable=false)
     */
    private $tcfArchiveSerialNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_archive_serial_number_2", type="string", length=255, nullable=false)
     */
    private $tcfArchiveSerialNumber2;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_archive_datetime", type="string", length=255, nullable=false)
     */
    private $tcfArchiveDatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="tcf_chemin_cr_pec", type="string", length=255, nullable=false)
     */
    private $tcfCheminCrPec;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tcf_date_depot", type="datetime", nullable=false)
     */
    private $tcfDateDepot;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="tcf_created_at", type="datetime", nullable=true)
     */
    private $tcfCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="tcf_updated_at", type="datetime", nullable=true)
     */
    private $tcfUpdatedAt;


    /**
     * Get tcfId
     *
     * @return integer
     */
    public function getTcfId()
    {
        return $this->tcfId;
    }

    /**
     * Set tcfNomFichier
     *
     * @param string $tcfNomFichier
     *
     * @return TcfTraceCfec
     */
    public function setTcfNomFichier($tcfNomFichier)
    {
        $this->tcfNomFichier = $tcfNomFichier;

        return $this;
    }

    /**
     * Get tcfNomFichier
     *
     * @return string
     */
    public function getTcfNomFichier()
    {
        return $this->tcfNomFichier;
    }

    /**
     * Set tcfNumFdr
     *
     * @param string $tcfNumFdr
     *
     * @return TcfTraceCfec
     */
    public function setTcfNumFdr($tcfNumFdr)
    {
        $this->tcfNumFdr = $tcfNumFdr;

        return $this;
    }

    /**
     * Get tcfNumFdr
     *
     * @return string
     */
    public function getTcfNumFdr()
    {
        return $this->tcfNumFdr;
    }

    /**
     * Set tcfVdmLocalisation
     *
     * @param string $tcfVdmLocalisation
     *
     * @return TcfTraceCfec
     */
    public function setTcfVdmLocalisation($tcfVdmLocalisation)
    {
        $this->tcfVdmLocalisation = $tcfVdmLocalisation;

        return $this;
    }

    /**
     * Get tcfVdmLocalisation
     *
     * @return string
     */
    public function getTcfVdmLocalisation()
    {
        return $this->tcfVdmLocalisation;
    }

    /**
     * Set tcfNomLot
     *
     * @param string $tcfNomLot
     *
     * @return TcfTraceCfec
     */
    public function setTcfNomLot($tcfNomLot)
    {
        $this->tcfNomLot = $tcfNomLot;

        return $this;
    }

    /**
     * Get tcfNomLot
     *
     * @return string
     */
    public function getTcfNomLot()
    {
        return $this->tcfNomLot;
    }

    /**
     * Set tcfIdentifiantUnique
     *
     * @param string $tcfIdentifiantUnique
     *
     * @return TcfTraceCfec
     */
    public function setTcfIdentifiantUnique($tcfIdentifiantUnique)
    {
        $this->tcfIdentifiantUnique = $tcfIdentifiantUnique;

        return $this;
    }

    /**
     * Get tcfIdentifiantUnique
     *
     * @return string
     */
    public function getTcfIdentifiantUnique()
    {
        return $this->tcfIdentifiantUnique;
    }

    /**
     * Set tcfEmpreinteArchivage
     *
     * @param string $tcfEmpreinteArchivage
     *
     * @return TcfTraceCfec
     */
    public function setTcfEmpreinteArchivage($tcfEmpreinteArchivage)
    {
        $this->tcfEmpreinteArchivage = $tcfEmpreinteArchivage;

        return $this;
    }

    /**
     * Get tcfEmpreinteArchivage
     *
     * @return string
     */
    public function getTcfEmpreinteArchivage()
    {
        return $this->tcfEmpreinteArchivage;
    }

    /**
     * Set tcfArchiveNumCfe
     *
     * @param string $tcfArchiveNumCfe
     *
     * @return TcfTraceCfec
     */
    public function setTcfArchiveNumCfe($tcfArchiveNumCfe)
    {
        $this->tcfArchiveNumCfe = $tcfArchiveNumCfe;

        return $this;
    }

    /**
     * Get tcfArchiveNumCfe
     *
     * @return string
     */
    public function getTcfArchiveNumCfe()
    {
        return $this->tcfArchiveNumCfe;
    }

    /**
     * Set tcfArchiveNumCfec
     *
     * @param string $tcfArchiveNumCfec
     *
     * @return TcfTraceCfec
     */
    public function setTcfArchiveNumCfec($tcfArchiveNumCfec)
    {
        $this->tcfArchiveNumCfec = $tcfArchiveNumCfec;

        return $this;
    }

    /**
     * Get tcfArchiveNumCfec
     *
     * @return string
     */
    public function getTcfArchiveNumCfec()
    {
        return $this->tcfArchiveNumCfec;
    }

    /**
     * Set tcfArchiveCheminCfec
     *
     * @param string $tcfArchiveCheminCfec
     *
     * @return TcfTraceCfec
     */
    public function setTcfArchiveCheminCfec($tcfArchiveCheminCfec)
    {
        $this->tcfArchiveCheminCfec = $tcfArchiveCheminCfec;

        return $this;
    }

    /**
     * Get tcfArchiveCheminCfec
     *
     * @return string
     */
    public function getTcfArchiveCheminCfec()
    {
        return $this->tcfArchiveCheminCfec;
    }

    /**
     * Set tcfArchiveSerialNumber
     *
     * @param string $tcfArchiveSerialNumber
     *
     * @return TcfTraceCfec
     */
    public function setTcfArchiveSerialNumber($tcfArchiveSerialNumber)
    {
        $this->tcfArchiveSerialNumber = $tcfArchiveSerialNumber;

        return $this;
    }

    /**
     * Get tcfArchiveSerialNumber
     *
     * @return string
     */
    public function getTcfArchiveSerialNumber()
    {
        return $this->tcfArchiveSerialNumber;
    }

    /**
     * Set tcfArchiveSerialNumber2
     *
     * @param string $tcfArchiveSerialNumber2
     *
     * @return TcfTraceCfec
     */
    public function setTcfArchiveSerialNumber2($tcfArchiveSerialNumber2)
    {
        $this->tcfArchiveSerialNumber2 = $tcfArchiveSerialNumber2;

        return $this;
    }

    /**
     * Get tcfArchiveSerialNumber2
     *
     * @return string
     */
    public function getTcfArchiveSerialNumber2()
    {
        return $this->tcfArchiveSerialNumber2;
    }

    /**
     * Set tcfArchiveDatetime
     *
     * @param string $tcfArchiveDatetime
     *
     * @return TcfTraceCfec
     */
    public function setTcfArchiveDatetime($tcfArchiveDatetime)
    {
        $this->tcfArchiveDatetime = $tcfArchiveDatetime;

        return $this;
    }

    /**
     * Get tcfArchiveDatetime
     *
     * @return string
     */
    public function getTcfArchiveDatetime()
    {
        return $this->tcfArchiveDatetime;
    }

    /**
     * Set tcfCheminCrPec
     *
     * @param string $tcfCheminCrPec
     *
     * @return TcfTraceCfec
     */
    public function setTcfCheminCrPec($tcfCheminCrPec)
    {
        $this->tcfCheminCrPec = $tcfCheminCrPec;

        return $this;
    }

    /**
     * Get tcfCheminCrPec
     *
     * @return string
     */
    public function getTcfCheminCrPec()
    {
        return $this->tcfCheminCrPec;
    }

    /**
     * Set tcfDateDepot
     *
     * @param \DateTime $tcfDateDepot
     *
     * @return TcfTraceCfec
     */
    public function setTcfDateDepot($tcfDateDepot)
    {
        $this->tcfDateDepot = $tcfDateDepot;

        return $this;
    }

    /**
     * Get tcfDateDepot
     *
     * @return \DateTime
     */
    public function getTcfDateDepot()
    {
        return $this->tcfDateDepot;
    }

    /**
     * Set tcfCreatedAt
     *
     * @param \DateTime $tcfCreatedAt
     *
     * @return TcfTraceCfec
     */
    public function setTcfCreatedAt($tcfCreatedAt)
    {
        $this->tcfCreatedAt = $tcfCreatedAt;

        return $this;
    }

    /**
     * Get tcfCreatedAt
     *
     * @return \DateTime
     */
    public function getTcfCreatedAt()
    {
        return $this->tcfCreatedAt;
    }

    /**
     * Set tcfUpdatedAt
     *
     * @param \DateTime $tcfUpdatedAt
     *
     * @return TcfTraceCfec
     */
    public function setTcfUpdatedAt($tcfUpdatedAt)
    {
        $this->tcfUpdatedAt = $tcfUpdatedAt;

        return $this;
    }

    /**
     * Get tcfUpdatedAt
     *
     * @return \DateTime
     */
    public function getTcfUpdatedAt()
    {
        return $this->tcfUpdatedAt;
    }
}
