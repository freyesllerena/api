<?php

namespace ApiBundle\Entity;

use ApiBundle\Enum\EnumLabelTypeHabilitationType;
use ApiBundle\Enum\EnumLabelUserType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * UsrUsers
 *
 * @ORM\Table(
 *     name="usr_users", uniqueConstraints={
 *      @ORM\UniqueConstraint(
 *          name="usr_login_pass", columns={
 *              "usr_login", "usr_pass"
 *          }
 *     )},
 *     indexes={
 *      @ORM\Index(name="usr_orsid", columns={"usr_orsid"}),
 *      @ORM\Index(name="usr_groupe", columns={"usr_groupe"}),
 *      @ORM\Index(name="usr_adp", columns={"usr_adp"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UsrUsersRepository")
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class UsrUsers
{
    const DEFAULT_USR_CHANGE_PASS = false;
    const DEFAULT_USR_PASS_LAST_MODIF = 0;
    const DEFAULT_USR_PASS_NEVER_EXPIRES = true;
    const DEFAULT_USR_MUST_CHANGE_PASS = false;
    const DEFAULT_USR_SESSION_TIME = 1800;
    const DEFAULT_USR_ACTIF = true;
    const DEFAULT_USR_PREND_CONF_GROUPE = false;
    const DEFAULT_USR_PROFILS = 'P1';
    const DEFAULT_USR_STATS = 0;
    const DEFAULT_USR_ORSID = false;
    const DEFAULT_USR_MAIL = false;
    const DEFAULT_USR_GROUPE = 'PAPERLESS';
    const DEFAULT_USR_EXPORT_CSV = false;
    const DEFAULT_USR_EXPORT_XLS = false;
    const DEFAULT_USR_EXPORT_DOC = false;
    const DEFAULT_USR_SEARCH_LLK = false;
    const DEFAULT_USR_USR_DOWNLOADS = false;
    const DEFAULT_USR_ADMIN_TELE = false;
    const DEFAULT_USR_THIRD_ARCHIVE = false;
    const DEFAULT_USR_ATTEMPTS = 0;
    const DEFAULT_USR_MAIL_CCI_AUTO = false;
    const DEFAULT_USR_NB_PANIER = 10;
    const DEFAULT_USR_NB_QUERY = 10;
    const DEFAULT_USR_LANGUAGE = 'fr_FR';
    const DEFAULT_USR_ADP = false;
    const DEFAULT_USR_CREATOR = 'orsid';
    const DEFAULT_USR_RIGHT_DOCUMENT_ANNOTATION = 0;
    const DEFAULT_USR_RIGHT_FOLDER_ANNOTATION = 0;
    const DEFAULT_USR_RIGHT_ORDER = 0;
    const DEFAULT_USR_RIGHT_LIFE_CYCLE = 0;
    const DEFAULT_USR_RIGHT_DOC_SEARCH = 0;
    const DEFAULT_USR_RIGHT_RECYCLE = 0;
    const DEFAULT_USR_RIGHT_MANAGE_USERS = 0;
    const DEFAULT_USR_ACCESS_CEL_EXPORT = false;
    const DEFAULT_USR_ACCESS_UNIT_IMPORT = false;

    const FILE_TYPE_MIME_IMPORT = 'text/plain';
    const FILE_EXTENSION_IMPORT = 'csv';

    const ERR_HABILITATION_IMPORT_MISSING_PROFILE = 'errUsrUsersHabilitationImportMissingProfile';
    const ERR_HABILITATION_IMPORT_MULTIPLE_PROFILES = 'errUsrUsersHabilitationImportMultipleProfiles';
    const ERR_HABILITATION_IMPORT_WRONG_LOGIN = 'errUsrUsersHabilitationImportWrongLogin';
    const ERR_HABILITATION_IMPORT_POPULATION_FILTER_ALREADY_DEFINED =
        'errUsrUsersHabilitationImportPopulationFilterAlreadyDefined';
    const ERR_HABILITATION_IMPORT_POPULATION_FILTER_DOES_NOT_EXIST =
        'errUsrUsersHabilitationImportPopulationFilterDoesNotExist';
    const ERR_HABILITATION_IMPORT_RIGHT_WRONG_VALUE = 'errUsrUsersHabilitationImportRightWrongValue';
    const ERR_HABILITATION_IMPORT_APPLICATIVE_FILTER_ALREADY_DEFINED =
        'errUsrUsersHabilitationImportApplicativeFilterAlreadyDefined';
    const ERR_HABILITATION_IMPORT_APPLICATIVE_FILTER_DOES_NOT_EXIST =
        'errUsrUsersHabilitationImportApplicativeFilterDoesNotExist';
    const ERR_HABILITATION_IMPORT_INCORRECT_STRUCTURE = 'errUsrUsersHabilitationImportIncorrectStructure';

    const HEADER_HABILITATION_EXPORT_LOGIN = 'headerUsrUsersHabilitationExportLogin';
    const HEADER_HABILITATION_EXPORT_SURNAME = 'headerUsrUsersHabilitationExportSurname';
    const HEADER_HABILITATION_EXPORT_FIRSTNAME = 'headerUsrUsersHabilitationExportFirstname';
    const HEADER_HABILITATION_EXPORT_POPULATION_FILTER = 'headerUsrUsersHabilitationExportPopulationFilter';
    const HEADER_HABILITATION_EXPORT_APPLICATIVE_FILTER = 'headerUsrUsersHabilitationExportApplicativeFilter';
    const HEADER_HABILITATION_EXPORT_RIGHT_DOC_SEARCH = 'headerUsrUsersHabilitationExportRightDocSearch';
    const HEADER_HABILITATION_EXPORT_RIGHT_ORDER = 'headerUsrUsersHabilitationExportRightOrder';
    const HEADER_HABILITATION_EXPORT_RIGHT_LIFE_CYCLE = 'headerUsrUsersHabilitationExportRightLifeCycle';
    const HEADER_HABILITATION_EXPORT_RIGHT_MANAGE_USERS = 'headerUsrUsersHabilitationExportRightManageUsers';
    const HEADER_HABILITATION_EXPORT_RIGHT_DOCUMENT_ANNOTATION = 'headerUsrUsersHabilitationExportRightDocAnnotation';
    const HEADER_HABILITATION_EXPORT_RIGHT_FOLDER_ANNOTATION = 'headerUsrUsersHabilitationExportRightFolderAnnotation';
    const HEADER_HABILITATION_EXPORT_ACCESS_CEL_EXPORT = 'headerUsrUsersHabilitationExportAccessCELExport';
    const HEADER_HABILITATION_EXPORT_ACCESS_UNIT_IMPORT = 'headerUsrUsersHabilitationExportAccessUnitImport';
    const HEADER_HABILITATION_EXPORT_STATUS = 'headerUsrUsersHabilitationExportStatus';
    const HEADER_HABILITATION_EXPORT_ERROR = 'headerUsrUsersHabilitationExportError';

    /**
     * @var string
     *
     * @ORM\Column(name="usr_login", type="string", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $usrLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_pass", type="string", length=32, nullable=true)
     */
    private $usrPass;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_old_password_list", type="text", length=65535, nullable=false)
     */
    private $usrOldPasswordList;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_change_pass", type="boolean", nullable=false)
     */
    private $usrChangePass = self::DEFAULT_USR_CHANGE_PASS;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_pass_last_modif", type="bigint", nullable=false)
     */
    private $usrPassLastModif = self::DEFAULT_USR_PASS_LAST_MODIF;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_pass_never_expires", type="boolean", nullable=false)
     */
    private $usrPassNeverExpires = self::DEFAULT_USR_PASS_NEVER_EXPIRES;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_must_change_pass", type="boolean", nullable=false)
     */
    private $usrMustChangePass = self::DEFAULT_USR_MUST_CHANGE_PASS;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_session_time", type="smallint", nullable=false)
     */
    private $usrSessionTime = self::DEFAULT_USR_SESSION_TIME;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_actif", type="boolean", nullable=false)
     */
    private $usrActif = self::DEFAULT_USR_ACTIF;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_raison", type="text", length=65535, nullable=false)
     */
    private $usrRaison;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_confidentialite", type="text", length=65535, nullable=false)
     */
    private $usrConfidentialite;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_prend_conf_groupe", type="boolean", nullable=false)
     */
    private $usrPrendConfGroupe = self::DEFAULT_USR_PREND_CONF_GROUPE;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_profils", type="string", length=255, nullable=false)
     */
    private $usrProfils = self::DEFAULT_USR_PROFILS;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_type", type="string", length=10, nullable=false)
     */
    private $usrType = EnumLabelUserType::USR_USER;
    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_statistiques", type="boolean", nullable=false)
     */
    private $usrStatistiques = self::DEFAULT_USR_STATS;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_orsid", type="boolean", nullable=false)
     */
    private $usrOrsid = self::DEFAULT_USR_ORSID;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_mail", type="boolean", nullable=false)
     */
    private $usrMail = self::DEFAULT_USR_MAIL;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_adresse_mail", type="text", length=65535, nullable=false)
     */
    private $usrAdresseMail;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_commentaires", type="text", length=65535, nullable=false)
     */
    private $usrCommentaires;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_nom", type="text", length=65535, nullable=false)
     */
    private $usrNom;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_prenom", type="text", length=65535, nullable=false)
     */
    private $usrPrenom;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_adresse_post", type="text", length=65535, nullable=false)
     */
    private $usrAdressePost;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_tel", type="text", length=65535, nullable=false)
     */
    private $usrTel;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_rsoc", type="text", length=65535, nullable=false)
     */
    private $usrRsoc;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_groupe", type="string", length=255, nullable=false)
     */
    private $usrGroupe = self::DEFAULT_USR_GROUPE;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_export_csv", type="boolean", nullable=false)
     */
    private $usrExportCsv = self::DEFAULT_USR_EXPORT_CSV;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_export_xls", type="boolean", nullable=false)
     */
    private $usrExportXls = self::DEFAULT_USR_EXPORT_XLS;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_export_doc", type="boolean", nullable=false)
     */
    private $usrExportDoc = self::DEFAULT_USR_EXPORT_DOC;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_recherche_llk", type="boolean", nullable=false)
     */
    private $usrRechercheLlk = self::DEFAULT_USR_SEARCH_LLK;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_usr_telechargements", type="boolean", nullable=false)
     */
    private $usrUsrTelechargements = self::DEFAULT_USR_USR_DOWNLOADS;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_admin_tele", type="boolean", nullable=false)
     */
    private $usrAdminTele = self::DEFAULT_USR_ADMIN_TELE;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_tiers_archivage", type="boolean", nullable=false)
     */
    private $usrTiersArchivage = self::DEFAULT_USR_THIRD_ARCHIVE;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_createur", type="string", length=255, nullable=false)
     */
    private $usrCreateur = self::DEFAULT_USR_CREATOR;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_tentatives", type="smallint", nullable=false)
     */
    private $usrTentatives = self::DEFAULT_USR_ATTEMPTS;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_mail_cci_auto", type="boolean", nullable=false)
     */
    private $usrMailCciAuto = self::DEFAULT_USR_MAIL_CCI_AUTO;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_adresse_mail_cci_auto", type="text", length=65535, nullable=false)
     */
    private $usrAdresseMailCciAuto;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_nb_panier", type="smallint", nullable=false)
     */
    private $usrNbPanier = self::DEFAULT_USR_NB_PANIER;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_nb_requete", type="smallint", nullable=false)
     */
    private $usrNbRequete = self::DEFAULT_USR_NB_QUERY;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_type_habilitation", type="string", length=20, nullable=false)
     */
    private $usrTypeHabilitation = EnumLabelTypeHabilitationType::EXPERT_HABILITATION;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_langue", type="string", length=5, nullable=false)
     */
    private $usrLangue = self::DEFAULT_USR_LANGUAGE;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_adp", type="boolean", nullable=false)
     */
    private $usrAdp = self::DEFAULT_USR_ADP;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="usr_beginningdate", type="datetime", nullable=false)
     */
    private $usrBeginningdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="usr_endingdate", type="datetime", nullable=false)
     */
    private $usrEndingdate;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_status", type="string", length=100, nullable=false)
     */
    private $usrStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_num_boite_archive", type="string", length=25, nullable=true)
     */
    private $usrNumBoiteArchive;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_mail_cycle_de_vie", type="string", length=250, nullable=true)
     */
    private $usrMailCycleDeVie;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_right_annotation_doc", type="smallint", nullable=false)
     */
    private $usrRightAnnotationDoc = self::DEFAULT_USR_RIGHT_DOCUMENT_ANNOTATION;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_right_annotation_dossier", type="smallint", nullable=false)
     */
    private $usrRightAnnotationDossier = self::DEFAULT_USR_RIGHT_FOLDER_ANNOTATION;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_right_classer", type="smallint", nullable=false)
     */
    private $usrRightClasser = self::DEFAULT_USR_RIGHT_ORDER;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_right_cycle_de_vie", type="smallint", nullable=false)
     */
    private $usrRightCycleDeVie = self::DEFAULT_USR_RIGHT_LIFE_CYCLE;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_right_recherche_doc", type="smallint", nullable=false)
     */
    private $usrRightRechercheDoc = self::DEFAULT_USR_RIGHT_DOC_SEARCH;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_right_recycler", type="smallint", nullable=false)
     */
    private $usrRightRecycler = self::DEFAULT_USR_RIGHT_RECYCLE;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_right_utilisateurs", type="smallint", nullable=false)
     */
    private $usrRightUtilisateurs = self::DEFAULT_USR_RIGHT_MANAGE_USERS;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_access_export_cel", type="boolean", nullable=false)
     */
    private $usrAccessExportCel = self::DEFAULT_USR_ACCESS_CEL_EXPORT;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_access_import_unitaire", type="boolean", nullable=false)
     */
    private $usrAccessImportUnitaire = self::DEFAULT_USR_ACCESS_UNIT_IMPORT;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="usr_created_at", type="datetime", nullable=true)
     */
    private $usrCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="usr_updated_at", type="datetime", nullable=true)
     */
    private $usrUpdatedAt;



    /**
     * Set usrLogin
     *
     * @param string $usrLogin
     *
     * @return UsrUsers
     */
    public function setUsrLogin($usrLogin)
    {
        $this->usrLogin = $usrLogin;

        return $this;
    }

    /**
     * Get usrLogin
     *
     * @return string
     */
    public function getUsrLogin()
    {
        return $this->usrLogin;
    }

    /**
     * Set usrPass
     *
     * @param string $usrPass
     *
     * @return UsrUsers
     */
    public function setUsrPass($usrPass)
    {
        $this->usrPass = $usrPass;

        return $this;
    }

    /**
     * Get usrPass
     *
     * @return string
     */
    public function getUsrPass()
    {
        return $this->usrPass;
    }

    /**
     * Set usrOldPasswordList
     *
     * @param string $usrOldPasswordList
     *
     * @return UsrUsers
     */
    public function setUsrOldPasswordList($usrOldPasswordList)
    {
        $this->usrOldPasswordList = $usrOldPasswordList;

        return $this;
    }

    /**
     * Get usrOldPasswordList
     *
     * @return string
     */
    public function getUsrOldPasswordList()
    {
        return $this->usrOldPasswordList;
    }

    /**
     * Set usrChangePass
     *
     * @param boolean $usrChangePass
     *
     * @return UsrUsers
     */
    public function setUsrChangePass($usrChangePass)
    {
        $this->usrChangePass = $usrChangePass;

        return $this;
    }

    /**
     * Get usrChangePass
     *
     * @return boolean
     */
    public function isUsrChangePass()
    {
        return $this->usrChangePass;
    }

    /**
     * Set usrPassLastModif
     *
     * @param integer $usrPassLastModif
     *
     * @return UsrUsers
     */
    public function setUsrPassLastModif($usrPassLastModif)
    {
        $this->usrPassLastModif = $usrPassLastModif;

        return $this;
    }

    /**
     * Get usrPassLastModif
     *
     * @return integer
     */
    public function getUsrPassLastModif()
    {
        return $this->usrPassLastModif;
    }

    /**
     * Set usrPassNeverExpires
     *
     * @param boolean $usrPassNeverExpires
     *
     * @return UsrUsers
     */
    public function setUsrPassNeverExpires($usrPassNeverExpires)
    {
        $this->usrPassNeverExpires = $usrPassNeverExpires;

        return $this;
    }

    /**
     * Get usrPassNeverExpires
     *
     * @return boolean
     */
    public function isUsrPassNeverExpires()
    {
        return $this->usrPassNeverExpires;
    }

    /**
     * Set usrMustChangePass
     *
     * @param boolean $usrMustChangePass
     *
     * @return UsrUsers
     */
    public function setUsrMustChangePass($usrMustChangePass)
    {
        $this->usrMustChangePass = $usrMustChangePass;

        return $this;
    }

    /**
     * Get usrMustChangePass
     *
     * @return boolean
     */
    public function isUsrMustChangePass()
    {
        return $this->usrMustChangePass;
    }

    /**
     * Set usrSessionTime
     *
     * @param integer $usrSessionTime
     *
     * @return UsrUsers
     */
    public function setUsrSessionTime($usrSessionTime)
    {
        $this->usrSessionTime = $usrSessionTime;

        return $this;
    }

    /**
     * Get usrSessionTime
     *
     * @return integer
     */
    public function getUsrSessionTime()
    {
        return $this->usrSessionTime;
    }

    /**
     * Set usrActif
     *
     * @param boolean $usrActif
     *
     * @return UsrUsers
     */
    public function setUsrActif($usrActif)
    {
        $this->usrActif = $usrActif;

        return $this;
    }

    /**
     * Get usrActif
     *
     * @return boolean
     */
    public function isUsrActif()
    {
        return $this->usrActif;
    }

    /**
     * Set usrRaison
     *
     * @param string $usrRaison
     *
     * @return UsrUsers
     */
    public function setUsrRaison($usrRaison)
    {
        $this->usrRaison = $usrRaison;

        return $this;
    }

    /**
     * Get usrRaison
     *
     * @return string
     */
    public function getUsrRaison()
    {
        return $this->usrRaison;
    }

    /**
     * Set usrConfidentialite
     *
     * @param string $usrConfidentialite
     *
     * @return UsrUsers
     */
    public function setUsrConfidentialite($usrConfidentialite)
    {
        $this->usrConfidentialite = $usrConfidentialite;

        return $this;
    }

    /**
     * Get usrConfidentialite
     *
     * @return string
     */
    public function getUsrConfidentialite()
    {
        return $this->usrConfidentialite;
    }

    /**
     * Set usrPrendConfGroupe
     *
     * @param boolean $usrPrendConfGroupe
     *
     * @return UsrUsers
     */
    public function setUsrPrendConfGroupe($usrPrendConfGroupe)
    {
        $this->usrPrendConfGroupe = $usrPrendConfGroupe;

        return $this;
    }

    /**
     * Get usrPrendConfGroupe
     *
     * @return boolean
     */
    public function isUsrPrendConfGroupe()
    {
        return $this->usrPrendConfGroupe;
    }

    /**
     * Set usrProfils
     *
     * @param string $usrProfils
     *
     * @return UsrUsers
     */
    public function setUsrProfils($usrProfils)
    {
        $this->usrProfils = $usrProfils;

        return $this;
    }

    /**
     * Get usrProfils
     *
     * @return string
     */
    public function getUsrProfils()
    {
        return $this->usrProfils;
    }

    /**
     * Set usrType
     *
     * @param string $usrType
     *
     * @return UsrUsers
     */
    public function setUsrType($usrType)
    {
        $this->usrType = $usrType;

        return $this;
    }

    /**
     * Get usrType
     *
     * @return string
     */
    public function getUsrType()
    {
        return $this->usrType;
    }

    /**
     * Set usrStatistiques
     *
     * @param boolean $usrStatistiques
     *
     * @return UsrUsers
     */
    public function setUsrStatistiques($usrStatistiques)
    {
        $this->usrStatistiques = $usrStatistiques;

        return $this;
    }

    /**
     * Get usrStatistiques
     *
     * @return boolean
     */
    public function hasUsrStatistiques()
    {
        return $this->usrStatistiques;
    }

    /**
     * Set usrOrsid
     *
     * @param boolean $usrOrsid
     *
     * @return UsrUsers
     */
    public function setUsrOrsid($usrOrsid)
    {
        $this->usrOrsid = $usrOrsid;

        return $this;
    }

    /**
     * Get usrOrsid
     *
     * @return boolean
     */
    public function isUsrOrsid()
    {
        return $this->usrOrsid;
    }

    /**
     * Set usrMail
     *
     * @param boolean $usrMail
     *
     * @return UsrUsers
     */
    public function setUsrMail($usrMail)
    {
        $this->usrMail = $usrMail;

        return $this;
    }

    /**
     * Get usrMail
     *
     * @return boolean
     */
    public function isUsrMail()
    {
        return $this->usrMail;
    }

    /**
     * Set usrAdresseMail
     *
     * @param string $usrAdresseMail
     *
     * @return UsrUsers
     */
    public function setUsrAdresseMail($usrAdresseMail)
    {
        $this->usrAdresseMail = $usrAdresseMail;

        return $this;
    }

    /**
     * Get usrAdresseMail
     *
     * @return string
     */
    public function getUsrAdresseMail()
    {
        return $this->usrAdresseMail;
    }

    /**
     * Set usrCommentaires
     *
     * @param string $usrCommentaires
     *
     * @return UsrUsers
     */
    public function setUsrCommentaires($usrCommentaires)
    {
        $this->usrCommentaires = $usrCommentaires;

        return $this;
    }

    /**
     * Get usrCommentaires
     *
     * @return string
     */
    public function getUsrCommentaires()
    {
        return $this->usrCommentaires;
    }

    /**
     * Set usrNom
     *
     * @param string $usrNom
     *
     * @return UsrUsers
     */
    public function setUsrNom($usrNom)
    {
        $this->usrNom = $usrNom;

        return $this;
    }

    /**
     * Get usrNom
     *
     * @return string
     */
    public function getUsrNom()
    {
        return $this->usrNom;
    }

    /**
     * Set usrPrenom
     *
     * @param string $usrPrenom
     *
     * @return UsrUsers
     */
    public function setUsrPrenom($usrPrenom)
    {
        $this->usrPrenom = $usrPrenom;

        return $this;
    }

    /**
     * Get usrPrenom
     *
     * @return string
     */
    public function getUsrPrenom()
    {
        return $this->usrPrenom;
    }

    /**
     * Set usrAdressePost
     *
     * @param string $usrAdressePost
     *
     * @return UsrUsers
     */
    public function setUsrAdressePost($usrAdressePost)
    {
        $this->usrAdressePost = $usrAdressePost;

        return $this;
    }

    /**
     * Get usrAdressePost
     *
     * @return string
     */
    public function getUsrAdressePost()
    {
        return $this->usrAdressePost;
    }

    /**
     * Set usrTel
     *
     * @param string $usrTel
     *
     * @return UsrUsers
     */
    public function setUsrTel($usrTel)
    {
        $this->usrTel = $usrTel;

        return $this;
    }

    /**
     * Get usrTel
     *
     * @return string
     */
    public function getUsrTel()
    {
        return $this->usrTel;
    }

    /**
     * Set usrRsoc
     *
     * @param string $usrRsoc
     *
     * @return UsrUsers
     */
    public function setUsrRsoc($usrRsoc)
    {
        $this->usrRsoc = $usrRsoc;

        return $this;
    }

    /**
     * Get usrRsoc
     *
     * @return string
     */
    public function getUsrRsoc()
    {
        return $this->usrRsoc;
    }

    /**
     * Set usrGroupe
     *
     * @param string $usrGroupe
     *
     * @return UsrUsers
     */
    public function setUsrGroupe($usrGroupe)
    {
        $this->usrGroupe = $usrGroupe;

        return $this;
    }

    /**
     * Get usrGroupe
     *
     * @return string
     */
    public function getUsrGroupe()
    {
        return $this->usrGroupe;
    }

    /**
     * Set usrExportCsv
     *
     * @param boolean $usrExportCsv
     *
     * @return UsrUsers
     */
    public function setUsrExportCsv($usrExportCsv)
    {
        $this->usrExportCsv = $usrExportCsv;

        return $this;
    }

    /**
     * Get usrExportCsv
     *
     * @return boolean
     */
    public function hasUsrExportCsv()
    {
        return $this->usrExportCsv;
    }

    /**
     * Set usrExportXls
     *
     * @param boolean $usrExportXls
     *
     * @return UsrUsers
     */
    public function setUsrExportXls($usrExportXls)
    {
        $this->usrExportXls = $usrExportXls;

        return $this;
    }

    /**
     * Get usrExportXls
     *
     * @return boolean
     */
    public function hasUsrExportXls()
    {
        return $this->usrExportXls;
    }

    /**
     * Set usrExportDoc
     *
     * @param boolean $usrExportDoc
     *
     * @return UsrUsers
     */
    public function setUsrExportDoc($usrExportDoc)
    {
        $this->usrExportDoc = $usrExportDoc;

        return $this;
    }

    /**
     * Get usrExportDoc
     *
     * @return boolean
     */
    public function hasUsrExportDoc()
    {
        return $this->usrExportDoc;
    }

    /**
     * Set usrRechercheLlk
     *
     * @param boolean $usrRechercheLlk
     *
     * @return UsrUsers
     */
    public function setUsrRechercheLlk($usrRechercheLlk)
    {
        $this->usrRechercheLlk = $usrRechercheLlk;

        return $this;
    }

    /**
     * Get usrRechercheLlk
     *
     * @return boolean
     */
    public function hasUsrRechercheLlk()
    {
        return $this->usrRechercheLlk;
    }

    /**
     * Set usrUsrTelechargements
     *
     * @param boolean $usrUsrTelechargements
     *
     * @return UsrUsers
     */
    public function setUsrTelechargements($usrUsrTelechargements)
    {
        $this->usrUsrTelechargements = $usrUsrTelechargements;

        return $this;
    }

    /**
     * Get usrUsrTelechargements
     *
     * @return boolean
     */
    public function hasUsrTelechargements()
    {
        return $this->usrUsrTelechargements;
    }

    /**
     * Set usrAdminTele
     *
     * @param boolean $usrAdminTele
     *
     * @return UsrUsers
     */
    public function setUsrAdminTele($usrAdminTele)
    {
        $this->usrAdminTele = $usrAdminTele;

        return $this;
    }

    /**
     * Get usrAdminTele
     *
     * @return boolean
     */
    public function hasUsrAdminTele()
    {
        return $this->usrAdminTele;
    }

    /**
     * Set usrTiersArchivage
     *
     * @param boolean $usrTiersArchivage
     *
     * @return UsrUsers
     */
    public function setUsrTiersArchivage($usrTiersArchivage)
    {
        $this->usrTiersArchivage = $usrTiersArchivage;

        return $this;
    }

    /**
     * Get usrTiersArchivage
     *
     * @return boolean
     */
    public function hasUsrTiersArchivage()
    {
        return $this->usrTiersArchivage;
    }

    /**
     * Set usrCreateur
     *
     * @param string $usrCreateur
     *
     * @return UsrUsers
     */
    public function setUsrCreateur($usrCreateur)
    {
        $this->usrCreateur = $usrCreateur;

        return $this;
    }

    /**
     * Get usrCreateur
     *
     * @return string
     */
    public function getUsrCreateur()
    {
        return $this->usrCreateur;
    }

    /**
     * Set usrTentatives
     *
     * @param integer $usrTentatives
     *
     * @return UsrUsers
     */
    public function setUsrTentatives($usrTentatives)
    {
        $this->usrTentatives = $usrTentatives;

        return $this;
    }

    /**
     * Get usrTentatives
     *
     * @return integer
     */
    public function getUsrTentatives()
    {
        return $this->usrTentatives;
    }

    /**
     * Set usrMailCciAuto
     *
     * @param boolean $usrMailCciAuto
     *
     * @return UsrUsers
     */
    public function setUsrMailCciAuto($usrMailCciAuto)
    {
        $this->usrMailCciAuto = $usrMailCciAuto;

        return $this;
    }

    /**
     * Get usrMailCciAuto
     *
     * @return boolean
     */
    public function hasUsrMailCciAuto()
    {
        return $this->usrMailCciAuto;
    }

    /**
     * Set usrAdresseMailCciAuto
     *
     * @param string $usrAdresseMailCciAuto
     *
     * @return UsrUsers
     */
    public function setUsrAdresseMailCciAuto($usrAdresseMailCciAuto)
    {
        $this->usrAdresseMailCciAuto = $usrAdresseMailCciAuto;

        return $this;
    }

    /**
     * Get usrAdresseMailCciAuto
     *
     * @return string
     */
    public function getUsrAdresseMailCciAuto()
    {
        return $this->usrAdresseMailCciAuto;
    }

    /**
     * Set usrNbPanier
     *
     * @param integer $usrNbPanier
     *
     * @return UsrUsers
     */
    public function setUsrNbPanier($usrNbPanier)
    {
        $this->usrNbPanier = $usrNbPanier;

        return $this;
    }

    /**
     * Get usrNbPanier
     *
     * @return integer
     */
    public function getUsrNbPanier()
    {
        return $this->usrNbPanier;
    }

    /**
     * Set usrNbRequete
     *
     * @param integer $usrNbRequete
     *
     * @return UsrUsers
     */
    public function setUsrNbRequete($usrNbRequete)
    {
        $this->usrNbRequete = $usrNbRequete;

        return $this;
    }

    /**
     * Get usrNbRequete
     *
     * @return integer
     */
    public function getUsrNbRequete()
    {
        return $this->usrNbRequete;
    }

    /**
     * Set usrTypeHabilitation
     *
     * @param string $usrTypeHabilitation
     *
     * @return UsrUsers
     */
    public function setUsrTypeHabilitation($usrTypeHabilitation)
    {
        $this->usrTypeHabilitation = $usrTypeHabilitation;

        return $this;
    }

    /**
     * Get usrTypeHabilitation
     *
     * @return string
     */
    public function getUsrTypeHabilitation()
    {
        return $this->usrTypeHabilitation;
    }

    /**
     * Set usrLangue
     *
     * @param string $usrLangue
     *
     * @return UsrUsers
     */
    public function setUsrLangue($usrLangue)
    {
        $this->usrLangue = $usrLangue;

        return $this;
    }

    /**
     * Get usrLangue
     *
     * @return string
     */
    public function getUsrLangue()
    {
        return $this->usrLangue;
    }

    /**
     * Set usrAdp
     *
     * @param boolean $usrAdp
     *
     * @return UsrUsers
     */
    public function setUsrAdp($usrAdp)
    {
        $this->usrAdp = $usrAdp;

        return $this;
    }

    /**
     * Get usrAdp
     *
     * @return boolean
     */
    public function isUsrAdp()
    {
        return $this->usrAdp;
    }

    /**
     * Set usrBeginningdate
     *
     * @param \DateTime $usrBeginningdate
     *
     * @return UsrUsers
     */
    public function setUsrBeginningdate($usrBeginningdate)
    {
        $this->usrBeginningdate = $usrBeginningdate;

        return $this;
    }

    /**
     * Get usrBeginningdate
     *
     * @return \DateTime
     */
    public function getUsrBeginningdate()
    {
        return $this->usrBeginningdate;
    }

    /**
     * Set usrEndingdate
     *
     * @param \DateTime $usrEndingdate
     *
     * @return UsrUsers
     */
    public function setUsrEndingdate($usrEndingdate)
    {
        $this->usrEndingdate = $usrEndingdate;

        return $this;
    }

    /**
     * Get usrEndingdate
     *
     * @return \DateTime
     */
    public function getUsrEndingdate()
    {
        return $this->usrEndingdate;
    }

    /**
     * Set usrStatus
     *
     * @param string $usrStatus
     *
     * @return UsrUsers
     */
    public function setUsrStatus($usrStatus)
    {
        $this->usrStatus = $usrStatus;

        return $this;
    }

    /**
     * Get usrStatus
     *
     * @return string
     */
    public function getUsrStatus()
    {
        return $this->usrStatus;
    }

    /**
     * Set usrNumBoiteArchive
     *
     * @param string $usrNumBoiteArchive
     *
     * @return UsrUsers
     */
    public function setUsrNumBoiteArchive($usrNumBoiteArchive)
    {
        $this->usrNumBoiteArchive = $usrNumBoiteArchive;

        return $this;
    }

    /**
     * Get usrNumBoiteArchive
     *
     * @return string
     */
    public function getUsrNumBoiteArchive()
    {
        return $this->usrNumBoiteArchive;
    }

    /**
     * Set usrMailCycleDeVie
     *
     * @param string $usrMailCycleDeVie
     *
     * @return UsrUsers
     */
    public function setUsrMailCycleDeVie($usrMailCycleDeVie)
    {
        $this->usrMailCycleDeVie = $usrMailCycleDeVie;

        return $this;
    }

    /**
     * Get usrMailCycleDeVie
     *
     * @return string
     */
    public function getUsrMailCycleDeVie()
    {
        return $this->usrMailCycleDeVie;
    }

    /**
     * Set usrRightAnnotationDoc
     *
     * @param integer $usrRightAnnotationDoc
     *
     * @return UsrUsers
     */
    public function setUsrRightAnnotationDoc($usrRightAnnotationDoc)
    {
        $this->usrRightAnnotationDoc = $usrRightAnnotationDoc;

        return $this;
    }

    /**
     * Get usrRightAnnotationDoc
     *
     * @return integer
     */
    public function getUsrRightAnnotationDoc()
    {
        return $this->usrRightAnnotationDoc;
    }

    /**
     * Set usrRightAnnotationDossier
     *
     * @param integer $usrRightAnnotationDossier
     *
     * @return UsrUsers
     */
    public function setUsrRightAnnotationDossier($usrRightAnnotationDossier)
    {
        $this->usrRightAnnotationDossier = $usrRightAnnotationDossier;

        return $this;
    }

    /**
     * Get usrRightAnnotationDossier
     *
     * @return integer
     */
    public function getUsrRightAnnotationDossier()
    {
        return $this->usrRightAnnotationDossier;
    }

    /**
     * Set usrRightClasser
     *
     * @param integer $usrRightClasser
     *
     * @return UsrUsers
     */
    public function setUsrRightClasser($usrRightClasser)
    {
        $this->usrRightClasser = $usrRightClasser;

        return $this;
    }

    /**
     * Get usrRightClasser
     *
     * @return integer
     */
    public function getUsrRightClasser()
    {
        return $this->usrRightClasser;
    }

    /**
     * Set usrRightCycleDeVie
     *
     * @param integer $usrRightCycleDeVie
     *
     * @return UsrUsers
     */
    public function setUsrRightCycleDeVie($usrRightCycleDeVie)
    {
        $this->usrRightCycleDeVie = $usrRightCycleDeVie;

        return $this;
    }

    /**
     * Get usrRightCycleDeVie
     *
     * @return integer
     */
    public function getUsrRightCycleDeVie()
    {
        return $this->usrRightCycleDeVie;
    }

    /**
     * Set usrRightRechercheDoc
     *
     * @param integer $usrRightRechercheDoc
     *
     * @return UsrUsers
     */
    public function setUsrRightRechercheDoc($usrRightRechercheDoc)
    {
        $this->usrRightRechercheDoc = $usrRightRechercheDoc;

        return $this;
    }

    /**
     * Get usrRightRechercheDoc
     *
     * @return integer
     */
    public function getUsrRightRechercheDoc()
    {
        return $this->usrRightRechercheDoc;
    }

    /**
     * Set usrRightRecycler
     *
     * @param integer $usrRightRecycler
     *
     * @return UsrUsers
     */
    public function setUsrRightRecycler($usrRightRecycler)
    {
        $this->usrRightRecycler = $usrRightRecycler;

        return $this;
    }

    /**
     * Get usrRightRecycler
     *
     * @return integer
     */
    public function getUsrRightRecycler()
    {
        return $this->usrRightRecycler;
    }

    /**
     * Set usrRightUtilisateurs
     *
     * @param integer $usrRightUtilisateurs
     *
     * @return UsrUsers
     */
    public function setUsrRightUtilisateurs($usrRightUtilisateurs)
    {
        $this->usrRightUtilisateurs = $usrRightUtilisateurs;

        return $this;
    }

    /**
     * Get usrRightUtilisateurs
     *
     * @return integer
     */
    public function getUsrRightUtilisateurs()
    {
        return $this->usrRightUtilisateurs;
    }

    /**
     * Set usrAccessExportCel
     *
     * @param boolean $usrAccessExportCel
     *
     * @return UsrUsers
     */
    public function setUsrAccessExportCel($usrAccessExportCel)
    {
        $this->usrAccessExportCel = $usrAccessExportCel;

        return $this;
    }

    /**
     * Get usrAccessExportCel
     *
     * @return boolean
     */
    public function hasUsrAccessExportCel()
    {
        return $this->usrAccessExportCel;
    }

    /**
     * Set usrAccessImportUnitaire
     *
     * @param boolean $usrAccessImportUnitaire
     *
     * @return UsrUsers
     */
    public function setUsrAccessImportUnitaire($usrAccessImportUnitaire)
    {
        $this->usrAccessImportUnitaire = $usrAccessImportUnitaire;

        return $this;
    }

    /**
     * Get usrAccessImportUnitaire
     *
     * @return boolean
     */
    public function hasUsrAccessImportUnitaire()
    {
        return $this->usrAccessImportUnitaire;
    }

    /**
     * Set usrCreatedAt
     *
     * @param \DateTime $usrCreatedAt
     *
     * @return UsrUsers
     */
    public function setUsrCreatedAt($usrCreatedAt)
    {
        $this->usrCreatedAt = $usrCreatedAt;

        return $this;
    }

    /**
     * Get usrCreatedAt
     *
     * @return \DateTime
     */
    public function getUsrCreatedAt()
    {
        return $this->usrCreatedAt;
    }

    /**
     * Set usrUpdatedAt
     *
     * @param \DateTime $usrUpdatedAt
     *
     * @return UsrUsers
     */
    public function setUsrUpdatedAt($usrUpdatedAt)
    {
        $this->usrUpdatedAt = $usrUpdatedAt;

        return $this;
    }

    /**
     * Get usrUpdatedAt
     *
     * @return \DateTime
     */
    public function getUsrUpdatedAt()
    {
        return $this->usrUpdatedAt;
    }

    /**
     * @return bool
     */
    public function isMembershipActive()
    {
        return $this->usrStatus == 'ACTIV';
    }

    /**
     * Renvoie les droits d'un utilisateur
     *
     * @return array
     */
    public function getUsrAuthorizations()
    {
        $results = array();
        $properties = get_object_vars($this);
        foreach ($properties as $name => $value) {
            if ('usrRight' == substr($name, 0, 8) || 'usrAccess' == substr($name, 0, 9)) {
                $results[lcfirst(substr($name, 3))] = $value;
            }
        }
        return $results;
    }

    /**
     * Définit les droits d'un utilisateur
     *
     * @param array $values
     */
    public function setUsrAuthorizations(array $values)
    {
        $properties = get_object_vars($this);
        foreach (array_keys($properties) as $name) {
            if ('usrRight' == substr($name, 0, 8) || 'usrAccess' == substr($name, 0, 9)) {
                $authorization = lcfirst(substr($name, 3));
                if (isset($values[$authorization])) {
                    $this->{$name} = $values[$authorization];
                }
            }
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->usrLogin;
    }
}
