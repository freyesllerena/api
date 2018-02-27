<?php

namespace ApiBundle\Entity;

use ApiBundle\Enum\EnumLabelEtatReportType;
use ApiBundle\Enum\EnumLabelTypeRapportType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * RapRapport
 *
 * @ORM\Table(name="rap_rapport", indexes={@ORM\Index(name="fk_rap_usr", columns={"rap_id_user"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\RapRapportRepository")
 */
class RapRapport
{
    const ERR_RAP_DOES_NOT_EXIST = 'errRapRapportDoesNotExist';
    const ERR_RAP_CREATION_FAILED = 'errRapRapportCreationFailed';
    const INFO_RAP_CREATION_WAS_SUCCESSFUL = 'infoRapRapportCreationWasSuccessful';
    const HEADER_RAP_CEL_EXPORT_TYPE_STATS = 'headerRapRapportCelExportTypeStats';
    const HEADER_RAP_CEL_EXPORT_TYPE_DOCUMENT_NAME = 'headerRapRapportCelExportTypeDocumentName';
    const HEADER_RAP_CEL_EXPORT_TYPE_PGM = 'headerRapRapportCelExportTypePgm';
    const HEADER_RAP_CEL_EXPORT_DTR = 'headerRapRapportCelExportDtr';
    const HEADER_RAP_CEL_EXPORT_TYPE_NUMBER = 'headerRapRapportCelExportTypeNumber';
    const PROPERTIE_RAP_EXCEL_CATEGORY = 'propertieRapRapportExcelCategory';
    const PROPERTIE_RAP_SEARCH_EXPORT_TITLE = 'propertieRapRapportSearchExportTitle';
    const PROPERTIE_RAP_SEARCH_EXPORT_DESCRIPTION = 'propertieRapRapportSearchExportDescription';
    const PROPERTIE_RAP_SEARCH_EXPORT_SHEET_TITLE = 'propertieRapRapportSearchExportSheetTitle';
    const PROPERTIE_RAP_CEL_TYPE_EXPORT_TITLE = 'propertieRapRapportCelTypeExportTitle';
    const PROPERTIE_RAP_CEL_TYPE_EXPORT_DESCRIPTION = 'propertieRapRapportCelTypeExportDescription';
    const PROPERTIE_RAP_CEL_EXPORT_SHEET_TITLE = 'propertieRapRapportCelExportSheetTitle';
    const PROPERTIE_RAP_CEL_PERIOD_EXPORT_TITLE = 'propertieRapRapportCelPeriodExportTitle';
    const PROPERTIE_RAP_CEL_PERIOD_EXPORT_DESCRIPTION = 'propertieRapRapportCelPeriodExportDescription';
    const ERR_RAP_EXPORT_NO_DATA = 'errRapRapportExportNoData';
    const HEADER_RAP_EXPORT_TOTAL = 'headerRapRapportExportTotal';
    const HEADER_RAP_EXPORT_YEAR = 'headerRapRapportExportYear';
    const MONTH_RAP_JANUARY = 'monthJanuary';
    const MONTH_RAP_FEBRUARY = 'monthFebruary';
    const MONTH_RAP_MARCH = 'monthMarch';
    const MONTH_RAP_APRIL = 'monthApril';
    const MONTH_RAP_MAY = 'monthMay';
    const MONTH_RAP_JUNE = 'monthJune';
    const MONTH_RAP_JULY = 'monthJuly';
    const MONTH_RAP_AUGUST = 'monthAugust';
    const MONTH_RAP_SEPTEMBER = 'monthSeptember';
    const MONTH_RAP_OCTOBER = 'monthOctober';
    const MONTH_RAP_NOVEMBER = 'monthNovember';
    const MONTH_RAP_DECEMBER = 'monthDecember';
    const HEADER_RAP_CEL_EXPORT_PERIOD_PERIODICITY = 'headerRapRapportCelExportPeriodPeriodicity';

    /**
     * @var integer
     *
     * @ORM\Column(name="rap_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rapId;

    /**
     * @var string
     *
     * @ORM\Column(name="rap_type_rapport", type="string", length=100, nullable=false)
     */
    private $rapTypeRapport = EnumLabelTypeRapportType::IMPORT_TYPE;

    /**
     * @var string
     *
     * @ORM\Column(name="rap_fichier", type="text", nullable=true)
     */
    private $rapFichier;

    /**
     * @var string
     *
     * @ORM\Column(name="rap_libelle_fic", type="string", length=255, nullable=true)
     */
    private $rapLibelleFic;

    /**
     * @var string
     *
     * @ORM\Column(name="rap_etat", type="string", length=2, nullable=false)
     */
    private $rapEtat = EnumLabelEtatReportType::OK_ETAT_REPORT;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="rap_created_at", type="datetime", nullable=true)
     */
    private $rapCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="rap_updated_at", type="datetime", nullable=true)
     */
    private $rapUpdatedAt;

    /**
     * @var UsrUsers
     *
     * @ORM\ManyToOne(targetEntity="UsrUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rap_id_user", referencedColumnName="usr_login")
     * })
     */
    private $rapUser;


    /**
     * Get rapId
     *
     * @return integer
     */
    public function getRapId()
    {
        return $this->rapId;
    }

    /**
     * Set rapTypeRapport
     *
     * @param string $rapTypeRapport
     *
     * @return RapRapport
     */
    public function setRapTypeRapport($rapTypeRapport)
    {
        $this->rapTypeRapport = $rapTypeRapport;

        return $this;
    }

    /**
     * Get rapTypeRapport
     *
     * @return string
     */
    public function getRapTypeRapport()
    {
        return $this->rapTypeRapport;
    }

    /**
     * Set rapFichier
     *
     * @param string $rapFichier
     *
     * @return RapRapport
     */
    public function setRapFichier($rapFichier)
    {
        $this->rapFichier = $rapFichier;

        return $this;
    }

    /**
     * Get rapFichier
     *
     * @return string
     */
    public function getRapFichier()
    {
        return $this->rapFichier;
    }

    /**
     * Set rapLibelleFic
     *
     * @param string $rapLibelleFic
     *
     * @return RapRapport
     */
    public function setRapLibelleFic($rapLibelleFic)
    {
        $this->rapLibelleFic = $rapLibelleFic;

        return $this;
    }

    /**
     * Get rapLibelleFic
     *
     * @return string
     */
    public function getRapLibelleFic()
    {
        return $this->rapLibelleFic;
    }

    /**
     * Set rapEtat
     *
     * @param string $rapEtat
     *
     * @return RapRapport
     */
    public function setRapEtat($rapEtat)
    {
        $this->rapEtat = $rapEtat;

        return $this;
    }

    /**
     * Get rapEtat
     *
     * @return string
     */
    public function getRapEtat()
    {
        return $this->rapEtat;
    }

    /**
     * Set rapCreatedAt
     *
     * @param \DateTime $rapCreatedAt
     *
     * @return RapRapport
     */
    public function setRapCreatedAt($rapCreatedAt)
    {
        $this->rapCreatedAt = $rapCreatedAt;

        return $this;
    }

    /**
     * Get rapCreatedAt
     *
     * @return \DateTime
     */
    public function getRapCreatedAt()
    {
        return $this->rapCreatedAt;
    }

    /**
     * Set rapUpdatedAt
     *
     * @param \DateTime $rapUpdatedAt
     *
     * @return RapRapport
     */
    public function setRapUpdatedAt($rapUpdatedAt)
    {
        $this->rapUpdatedAt = $rapUpdatedAt;

        return $this;
    }

    /**
     * Get rapUpdatedAt
     *
     * @return \DateTime
     */
    public function getRapUpdatedAt()
    {
        return $this->rapUpdatedAt;
    }

    /**
     * Set rapUser
     *
     * @param \ApiBundle\Entity\UsrUsers $rapUser
     *
     * @return RapRapport
     */
    public function setRapUser(UsrUsers $rapUser = null)
    {
        $this->rapUser = $rapUser;

        return $this;
    }

    /**
     * Get rapUser
     *
     * @return \ApiBundle\Entity\UsrUsers
     */
    public function getRapUser()
    {
        return $this->rapUser;
    }
}
