<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelStatusDocumentType;
use ApiBundle\Enum\EnumLabelYesNoEmptyType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IfpIndexfichePaperless
 *
 * @ORM\Table(name="ifp_indexfiche_paperless", indexes={
 *     @ORM\Index(name="ifp_nombrepages", columns={"ifp_nombrepages"}),
 *     @ORM\Index(name="ifp_id_code_document_societe_jalon", columns={"ifp_id_code_document", "ifp_id_code_societe", "ifp_id_code_jalon"}),
 *     @ORM\Index(name="ifp_mat_cli_doc", columns={"ifp_id_num_matricule", "ifp_id_code_client", "ifp_id_code_document"}),
 *     @ORM\Index(name="ifp_id_indice_classement", columns={"ifp_id_indice_classement"}),
 *     @ORM\Index(name="ifp_id_unique_document", columns={"ifp_id_unique_document"}),
 *     @ORM\Index(name="ifp_id_type_document", columns={"ifp_id_type_document"}),
 *     @ORM\Index(name="ifp_id_poids_document", columns={"ifp_id_poids_document"}),
 *     @ORM\Index(name="ifp_id_nbr_pages_document", columns={"ifp_id_nbr_pages_document"}),
 *     @ORM\Index(name="ifp_id_periode_paie", columns={"ifp_id_periode_paie"}),
 *     @ORM\Index(name="ifp_id_periode_exercice_sociale", columns={"ifp_id_periode_exercice_sociale"}),
 *     @ORM\Index(name="ifp_id_date_dernier_acces_document", columns={"ifp_id_date_dernier_acces_document"}),
 *     @ORM\Index(name="ifp_id_date_archivage_document", columns={"ifp_id_date_archivage_document"}),
 *     @ORM\Index(name="ifp_id_date_fin_archivage_document", columns={"ifp_id_date_fin_archivage_document"}),
 *     @ORM\Index(name="ifp_id_nom_salarie", columns={"ifp_id_nom_salarie"}),
 *     @ORM\Index(name="ifp_id_prenom_salarie", columns={"ifp_id_prenom_salarie"}),
 *     @ORM\Index(name="ifp_id_nom_jeune_fille_salarie", columns={"ifp_id_nom_jeune_fille_salarie"}),
 *     @ORM\Index(name="ifp_id_date_entree", columns={"ifp_id_date_entree"}),
 *     @ORM\Index(name="ifp_id_date_sortie", columns={"ifp_id_date_sortie"}),
 *     @ORM\Index(name="ifp_id_num_nir", columns={"ifp_id_num_nir"}),
 *     @ORM\Index(name="ifp_id_code_categ_professionnelle", columns={"ifp_id_code_categ_professionnelle"}),
 *     @ORM\Index(name="ifp_id_libre1", columns={"ifp_id_libre1"}),
 *     @ORM\Index(name="ifp_id_libre1_date", columns={"ifp_id_libre1_date"}),
 *     @ORM\Index(name="ifp_modedt", columns={"ifp_modedt"}),
 *     @ORM\Index(name="ifp_numdtr", columns={"ifp_numdtr"}),
 *     @ORM\Index(name="ifp_id_libelle_doc_code_doc", columns={"ifp_id_libelle_document", "ifp_id_code_document"}),
 *     @ORM\Index(name="id_numero_boite_archive", columns={"ifp_id_numero_boite_archive"}),
 *     @ORM\Index(name="ifp_id_code_activite", columns={"ifp_id_code_activite"}),
 *     @ORM\Index(name="ifp_id_num_matricule_rh", columns={"ifp_id_num_matricule_rh"}),
 *     @ORM\Index(name="IDX_AAC3BDEC593F7E2FD81E8387", columns={"ifp_id_num_matricule", "ifp_id_code_client"}),
 *     @ORM\Index(name="IDX_AAC3BDEC18BB2432", columns={"ifp_id_code_document"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\IfpIndexfichePaperlessRepository")
 */
class IfpIndexfichePaperless
{
    const DEFAULT_IFP_INTERBOX = false;
    const DEFAULT_IFP_IS_DOUBLON = false;
    const DEFAULT_IFP_IS_PERSONNEL = false;
    const DEFAULT_IFP_CYCLE_END_LIFE_DOCUMENT = false;
    const DEFAULT_IFP_CYCLE_FREEZE_DOCUMENT = false;
    const DEFAULT_IFP_SET_END_ARCHIVE = false;

    const ERR_NOT_INDIVIDUAL = 'errIfpIndexfichePaperlessNotIndividual';
    const ERR_ALREADY_UNFROZEN = 'errIfpIndexfichePaperlessAlreadyUnfrozen';
    const ERR_ALREADY_FROZEN = 'errIfpIndexfichePaperlessAlreadyFrozen';
    const ERR_USER_NOT_ALLOWED = 'errIfpIndexfichePaperlessUserNotAllowed';
    const ERR_NOT_MATRICULE_RH = 'errIfpIndexfichePaperlessNotMatriculeRh';
    const ERR_CEL_SOURCE_CODES_INCORRECT = 'errIfpIndexfichePaperlessCelSourceCodesIncorrect';

    const ERR_FILE_TYPE_NOT_VALID = 'errFileTypeNotValid';

    const STATUS_OK_DOCUMENT = 'OK';
    const STATUS_REJET_DOCUMENT = 'REJET';
    const SOURCE_DOCUMENT_EXCLUDED = 'BVRH UPLOAD';
    const IFP_PREFIX = 'ifp';

    const DEFAULT_AUTEUR_DOCUMENT = 'ADP';

    /**
     * @var integer
     *
     * @ORM\Column(name="ifp_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ifpId;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_documentsassocies", type="string", length=40, nullable=true)
     */
    private $ifpDocumentsassocies;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_vdm_localisation", type="text", length=65535, nullable=false)
     */
    private $ifpVdmLocalisation;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_refpapier", type="string", length=4, nullable=true)
     */
    private $ifpRefpapier;

    /**
     * @var integer
     *
     * @ORM\Column(name="ifp_nombrepages", type="smallint", nullable=true)
     */
    private $ifpNombrepages;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_code_chrono", type="string", length=255, nullable=true)
     */
    private $ifpIdCodeChrono;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_numero_boite_archive", type="string", length=255, nullable=true)
     */
    private $ifpIdNumeroBoiteArchive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ifp_interbox", type="boolean", nullable=false)
     */
    private $ifpInterbox = self::DEFAULT_IFP_INTERBOX;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_lot_index", type="string", length=100, nullable=true)
     */
    private $ifpLotIndex;

    /**
     * @var integer
     *
     * @ORM\Column(name="ifp_lot_production", type="smallint", nullable=false)
     */
    private $ifpLotProduction;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_nom_societe", type="string", length=255, nullable=true)
     */
    private $ifpIdNomSociete;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_company", type="string", length=255, nullable=true)
     */
    private $ifpIdCompany;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_nom_client", type="string", length=255, nullable=true)
     */
    private $ifpIdNomClient;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_code_societe", type="string", length=255, nullable=true)
     */
    private $ifpIdCodeSociete;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_code_etablissement", type="string", length=255, nullable=true)
     */
    private $ifpIdCodeEtablissement;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_lib_etablissement", type="string", length=255, nullable=true)
     */
    private $ifpIdLibEtablissement;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_code_jalon", type="string", length=255, nullable=true)
     */
    private $ifpIdCodeJalon;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_libelle_jalon", type="string", length=255, nullable=true)
     */
    private $ifpIdLibelleJalon;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_num_siren", type="string", length=255, nullable=true)
     */
    private $ifpIdNumSiren;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_num_siret", type="string", length=255, nullable=true)
     */
    private $ifpIdNumSiret;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_indice_classement", type="string", length=255, nullable=true)
     */
    private $ifpIdIndiceClassement;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_unique_document", type="string", length=255, nullable=true)
     */
    private $ifpIdUniqueDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_type_document", type="string", length=255, nullable=true)
     */
    private $ifpIdTypeDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_libelle_document", type="string", length=255, nullable=false)
     */
    private $ifpIdLibelleDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_format_document", type="string", length=255, nullable=true)
     */
    private $ifpIdFormatDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_auteur_document", type="string", length=255, nullable=true)
     */
    private $ifpIdAuteurDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_source_document", type="string", length=255, nullable=true)
     */
    private $ifpIdSourceDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_num_version_document", type="string", length=255, nullable=true)
     */
    private $ifpIdNumVersionDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_poids_document", type="string", length=255, nullable=true)
     */
    private $ifpIdPoidsDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_nbr_pages_document", type="string", length=255, nullable=true)
     */
    private $ifpIdNbrPagesDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_profil_archivage", type="string", length=255, nullable=true)
     */
    private $ifpIdProfilArchivage;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_etat_archive", type="string", length=255, nullable=true)
     */
    private $ifpIdEtatArchive;

    /**
     * @var string
     *
     * @Assert\Regex(
     *     pattern="/^\d{4}(0[1-9]{1})|(1[0-2]{1})$/",
     *     message="errIfpIndexfichePaperlessPeriodePaieValueIncorrect"
     * )
     *
     * @ORM\Column(name="ifp_id_periode_paie", type="string", length=6, nullable=true)
     */
    private $ifpIdPeriodePaie;

    /**
     * @var string
     *
     * @Assert\Regex(
     *     pattern="/^\d{4}$/",
     *     message="errIfpIndexfichePaperlessPeriodeExerciceSocialeValueIncorrect"
     * )
     *
     * @ORM\Column(name="ifp_id_periode_exercice_sociale", type="string", length=4, nullable=true)
     */
    private $ifpIdPeriodeExerciceSociale;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ifp_id_date_dernier_acces_document", type="datetime", nullable=true)
     */
    private $ifpIdDateDernierAccesDocument;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ifp_id_date_archivage_document", type="datetime", nullable=true)
     */
    private $ifpIdDateArchivageDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_duree_archivage_document", type="string", length=255, nullable=true)
     */
    private $ifpIdDureeArchivageDocument;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ifp_id_date_fin_archivage_document", type="datetime", nullable=true)
     */
    private $ifpIdDateFinArchivageDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_nom_salarie", type="string", length=255, nullable=true)
     */
    private $ifpIdNomSalarie;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_prenom_salarie", type="string", length=255, nullable=true)
     */
    private $ifpIdPrenomSalarie;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_nom_jeune_fille_salarie", type="string", length=255, nullable=true)
     */
    private $ifpIdNomJeuneFilleSalarie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ifp_id_date_naissance_salarie", type="datetime", nullable=true)
     */
    private $ifpIdDateNaissanceSalarie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ifp_id_date_entree", type="datetime", nullable=true)
     */
    private $ifpIdDateEntree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ifp_id_date_sortie", type="datetime", nullable=true)
     */
    private $ifpIdDateSortie;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_num_nir", type="string", length=255, nullable=true)
     */
    private $ifpIdNumNir;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_code_categ_professionnelle", type="string", length=255, nullable=true)
     */
    private $ifpIdCodeCategProfessionnelle;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_code_categ_socio_prof", type="string", length=255, nullable=true)
     */
    private $ifpIdCodeCategSocioProf;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_type_contrat", type="string", length=255, nullable=true)
     */
    private $ifpIdTypeContrat;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_affectation1", type="string", length=255, nullable=true)
     */
    private $ifpIdAffectation1;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_affectation2", type="string", length=255, nullable=true)
     */
    private $ifpIdAffectation2;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_affectation3", type="string", length=255, nullable=true)
     */
    private $ifpIdAffectation3;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_libre1", type="string", length=255, nullable=true)
     */
    private $ifpIdLibre1;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_libre2", type="string", length=255, nullable=true)
     */
    private $ifpIdLibre2;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_affectation1_date", type="string", length=255, nullable=true)
     */
    private $ifpIdAffectation1Date;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_affectation2_date", type="string", length=255, nullable=true)
     */
    private $ifpIdAffectation2Date;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_affectation3_date", type="string", length=255, nullable=true)
     */
    private $ifpIdAffectation3Date;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_libre1_date", type="string", length=255, nullable=true)
     */
    private $ifpIdLibre1Date;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_libre2_date", type="string", length=255, nullable=true)
     */
    private $ifpIdLibre2Date;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_num_matricule_rh", type="string", length=255, nullable=false)
     */
    private $ifpIdNumMatriculeRh;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_num_matricule_groupe", type="string", length=255, nullable=true)
     */
    private $ifpIdNumMatriculeGroupe;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_annotation", type="text", length=65535, nullable=false)
     */
    private $ifpIdAnnotation;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_conteneur", type="string", length=255, nullable=false)
     */
    private $ifpIdConteneur;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_boite", type="string", length=255, nullable=false)
     */
    private $ifpIdBoite;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_lot", type="string", length=255, nullable=false)
     */
    private $ifpIdLot;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_num_ordre", type="string", length=2, nullable=true)
     */
    private $ifpIdNumOrdre;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_archive_cfec", type="text", length=65535, nullable=false)
     */
    private $ifpArchiveCfec;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_archive_serialnumber", type="text", length=65535, nullable=false)
     */
    private $ifpArchiveSerialnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_archive_datetime", type="text", length=65535, nullable=false)
     */
    private $ifpArchiveDatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_archive_name", type="text", length=65535, nullable=false)
     */
    private $ifpArchiveName;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_archive_cfe", type="text", length=65535, nullable=false)
     */
    private $ifpArchiveCfe;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_numeropdf", type="string", length=50, nullable=false)
     */
    private $ifpNumeropdf;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_opn_provenance", type="string", length=100, nullable=false)
     */
    private $ifpOpnProvenance;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_status_num", type="string", length=10, nullable=false)
     */
    private $ifpStatusNum = EnumLabelStatusDocumentType::OK_STATUS_DOCUMENT;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ifp_is_doublon", type="boolean", nullable=false)
     */
    private $ifpIsDoublon = self::DEFAULT_IFP_IS_DOUBLON;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_login", type="string", length=100, nullable=false)
     */
    private $ifpLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_modedt", type="string", length=20, nullable=false)
     */
    private $ifpModedt;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_numdtr", type="string", length=20, nullable=false)
     */
    private $ifpNumdtr;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_old_id_date_dernier_acces_document", type="string", length=255, nullable=true)
     */
    private $ifpOldIdDateDernierAccesDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_old_id_date_archivage_document", type="string", length=255, nullable=true)
     */
    private $ifpOldIdDateArchivageDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_old_id_date_fin_archivage_document", type="string", length=255, nullable=true)
     */
    private $ifpOldIdDateFinArchivageDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_old_id_date_naissance_salarie", type="text", length=65535, nullable=true)
     */
    private $ifpOldIdDateNaissanceSalarie;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_old_id_date_entree", type="string", length=255, nullable=true)
     */
    private $ifpOldIdDateEntree;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_old_id_date_sortie", type="string", length=255, nullable=true)
     */
    private $ifpOldIdDateSortie;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_code_activite", type="string", length=255, nullable=false)
     */
    private $ifpIdCodeActivite;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ifp_cycle_fin_de_vie", type="boolean", nullable=false)
     */
    private $ifpCycleFinDeVie = self::DEFAULT_IFP_CYCLE_END_LIFE_DOCUMENT;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_cycle_purger", type="string", length=1, nullable=true)
     */
    private $ifpCyclePurger = EnumLabelYesNoEmptyType::EMPTY_VALUE_ENUM;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ifp_geler", type="boolean", nullable=false)
     */
    private $ifpGeler = self::DEFAULT_IFP_CYCLE_FREEZE_DOCUMENT;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_1", type="string", length=8, nullable=true)
     */
    private $ifpIdDate1;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_2", type="string", length=8, nullable=true)
     */
    private $ifpIdDate2;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_3", type="string", length=8, nullable=true)
     */
    private $ifpIdDate3;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_4", type="string", length=8, nullable=true)
     */
    private $ifpIdDate4;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_5", type="string", length=8, nullable=true)
     */
    private $ifpIdDate5;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_6", type="string", length=8, nullable=true)
     */
    private $ifpIdDate6;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_7", type="string", length=8, nullable=true)
     */
    private $ifpIdDate7;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_8", type="string", length=8, nullable=true)
     */
    private $ifpIdDate8;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_adp_1", type="string", length=8, nullable=true)
     */
    private $ifpIdDateAdp1;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_date_adp_2", type="string", length=8, nullable=true)
     */
    private $ifpIdDateAdp2;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_1", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum1;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_2", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum2;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_3", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum3;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_4", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum4;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_5", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum5;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_6", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum6;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_7", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum7;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_8", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum8;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_9", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum9;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_10", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum10;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_11", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum11;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_12", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum12;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_13", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum13;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_14", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum14;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_15", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum15;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_16", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum16;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_17", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum17;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_18", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanum18;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_adp_1", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanumAdp1;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_alphanum_adp_2", type="string", length=50, nullable=true)
     */
    private $ifpIdAlphanumAdp2;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_1", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum1;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_2", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum2;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_3", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum3;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_4", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum4;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_5", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum5;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_6", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum6;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_7", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum7;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_8", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum8;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_9", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum9;

    /**
     * @var float
     *
     * @ORM\Column(name="ifp_id_num_10", type="float", precision=10, scale=0, nullable=true)
     */
    private $ifpIdNum10;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_cycle_temps_parcouru", type="string", length=50, nullable=true)
     */
    private $ifpCycleTempsParcouru;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_cycle_temps_restant", type="string", length=50, nullable=true)
     */
    private $ifpCycleTempsRestant;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ifp_set_fin_archivage", type="boolean", nullable=false)
     */
    private $ifpSetFinArchivage = self::DEFAULT_IFP_SET_END_ARCHIVE;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ifp_is_personnel", type="boolean", nullable=false)
     */
    private $ifpIsPersonnel = self::DEFAULT_IFP_IS_PERSONNEL;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="ifp_created_at", type="datetime", nullable=true)
     */
    private $ifpCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="ifp_updated_at", type="datetime", nullable=true)
     */
    private $ifpUpdatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_num_matricule", type="string", nullable=true)
     */
    private $ifpNumMatricule;

    /**
     * @var TypType
     *
     * @ORM\ManyToOne(targetEntity="TypType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ifp_id_code_document", referencedColumnName="typ_code", nullable=true)
     * })
     */
    private $ifpCodeDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="ifp_id_code_client", type="string", nullable=false)
     */
    private $ifpCodeClient;

    /**
     * Get ifpId
     *
     * @return integer
     */
    public function getIfpId()
    {
        return $this->ifpId;
    }

    /**
     * Set ifpDocumentsassocies
     *
     * @param string $ifpDocumentsassocies
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpDocumentsassocies($ifpDocumentsassocies)
    {
        $this->ifpDocumentsassocies = $ifpDocumentsassocies;

        return $this;
    }

    /**
     * Get ifpDocumentsassocies
     *
     * @return string
     */
    public function getIfpDocumentsassocies()
    {
        return $this->ifpDocumentsassocies;
    }

    /**
     * Set ifpVdmLocalisation
     *
     * @param string $ifpVdmLocalisation
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpVdmLocalisation($ifpVdmLocalisation)
    {
        $this->ifpVdmLocalisation = $ifpVdmLocalisation;

        return $this;
    }

    /**
     * Get ifpVdmLocalisation
     *
     * @return string
     */
    public function getIfpVdmLocalisation()
    {
        return $this->ifpVdmLocalisation;
    }

    /**
     * Set ifpRefpapier
     *
     * @param string $ifpRefpapier
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpRefpapier($ifpRefpapier)
    {
        $this->ifpRefpapier = $ifpRefpapier;

        return $this;
    }

    /**
     * Get ifpRefpapier
     *
     * @return string
     */
    public function getIfpRefpapier()
    {
        return $this->ifpRefpapier;
    }

    /**
     * Set ifpNombrepages
     *
     * @param integer $ifpNombrepages
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpNombrepages($ifpNombrepages)
    {
        $this->ifpNombrepages = $ifpNombrepages;

        return $this;
    }

    /**
     * Get ifpNombrepages
     *
     * @return integer
     */
    public function getIfpNombrepages()
    {
        return $this->ifpNombrepages;
    }

    /**
     * Set ifpIdCodeChrono
     *
     * @param string $ifpIdCodeChrono
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdCodeChrono($ifpIdCodeChrono)
    {
        $this->ifpIdCodeChrono = $ifpIdCodeChrono;

        return $this;
    }

    /**
     * Get ifpIdCodeChrono
     *
     * @return string
     */
    public function getIfpIdCodeChrono()
    {
        return $this->ifpIdCodeChrono;
    }

    /**
     * Set ifpIdNumeroBoiteArchive
     *
     * @param string $ifpIdNumeroBoiteArchive
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNumeroBoiteArchive($ifpIdNumeroBoiteArchive)
    {
        $this->ifpIdNumeroBoiteArchive = $ifpIdNumeroBoiteArchive;

        return $this;
    }

    /**
     * Get ifpIdNumeroBoiteArchive
     *
     * @return string
     */
    public function getIfpIdNumeroBoiteArchive()
    {
        return $this->ifpIdNumeroBoiteArchive;
    }

    /**
     * Set ifpInterbox
     *
     * @param boolean $ifpInterbox
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpInterbox($ifpInterbox)
    {
        $this->ifpInterbox = $ifpInterbox;

        return $this;
    }

    /**
     * Get ifpInterbox
     *
     * @return boolean
     */
    public function isIfpInterbox()
    {
        return $this->ifpInterbox;
    }

    /**
     * Set ifpLotIndex
     *
     * @param string $ifpLotIndex
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpLotIndex($ifpLotIndex)
    {
        $this->ifpLotIndex = $ifpLotIndex;

        return $this;
    }

    /**
     * Get ifpLotIndex
     *
     * @return string
     */
    public function getIfpLotIndex()
    {
        return $this->ifpLotIndex;
    }

    /**
     * Set ifpLotProduction
     *
     * @param integer $ifpLotProduction
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpLotProduction($ifpLotProduction)
    {
        $this->ifpLotProduction = $ifpLotProduction;

        return $this;
    }

    /**
     * Get ifpLotProduction
     *
     * @return integer
     */
    public function getIfpLotProduction()
    {
        return $this->ifpLotProduction;
    }

    /**
     * Set ifpIdNomSociete
     *
     * @param string $ifpIdNomSociete
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNomSociete($ifpIdNomSociete)
    {
        $this->ifpIdNomSociete = $ifpIdNomSociete;

        return $this;
    }

    /**
     * Get ifpIdNomSociete
     *
     * @return string
     */
    public function getIfpIdNomSociete()
    {
        return $this->ifpIdNomSociete;
    }

    /**
     * Set ifpIdCompany
     *
     * @param string $ifpIdCompany
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdCompany($ifpIdCompany)
    {
        $this->ifpIdCompany = $ifpIdCompany;

        return $this;
    }

    /**
     * Get ifpIdCompany
     *
     * @return string
     */
    public function getIfpIdCompany()
    {
        return $this->ifpIdCompany;
    }

    /**
     * Set ifpIdNomClient
     *
     * @param string $ifpIdNomClient
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNomClient($ifpIdNomClient)
    {
        $this->ifpIdNomClient = $ifpIdNomClient;

        return $this;
    }

    /**
     * Get ifpIdNomClient
     *
     * @return string
     */
    public function getIfpIdNomClient()
    {
        return $this->ifpIdNomClient;
    }

    /**
     * Set ifpIdCodeSociete
     *
     * @param string $ifpIdCodeSociete
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdCodeSociete($ifpIdCodeSociete)
    {
        $this->ifpIdCodeSociete = $ifpIdCodeSociete;

        return $this;
    }

    /**
     * Get ifpIdCodeSociete
     *
     * @return string
     */
    public function getIfpIdCodeSociete()
    {
        return $this->ifpIdCodeSociete;
    }

    /**
     * Set ifpIdCodeEtablissement
     *
     * @param string $ifpIdCodeEtablissement
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdCodeEtablissement($ifpIdCodeEtablissement)
    {
        $this->ifpIdCodeEtablissement = $ifpIdCodeEtablissement;

        return $this;
    }

    /**
     * Get ifpIdCodeEtablissement
     *
     * @return string
     */
    public function getIfpIdCodeEtablissement()
    {
        return $this->ifpIdCodeEtablissement;
    }

    /**
     * Set ifpIdLibEtablissement
     *
     * @param string $ifpIdLibEtablissement
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdLibEtablissement($ifpIdLibEtablissement)
    {
        $this->ifpIdLibEtablissement = $ifpIdLibEtablissement;

        return $this;
    }

    /**
     * Get ifpIdLibEtablissement
     *
     * @return string
     */
    public function getIfpIdLibEtablissement()
    {
        return $this->ifpIdLibEtablissement;
    }

    /**
     * Set ifpIdCodeJalon
     *
     * @param string $ifpIdCodeJalon
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdCodeJalon($ifpIdCodeJalon)
    {
        $this->ifpIdCodeJalon = $ifpIdCodeJalon;

        return $this;
    }

    /**
     * Get ifpIdCodeJalon
     *
     * @return string
     */
    public function getIfpIdCodeJalon()
    {
        return $this->ifpIdCodeJalon;
    }

    /**
     * Set ifpIdLibelleJalon
     *
     * @param string $ifpIdLibelleJalon
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdLibelleJalon($ifpIdLibelleJalon)
    {
        $this->ifpIdLibelleJalon = $ifpIdLibelleJalon;

        return $this;
    }

    /**
     * Get ifpIdLibelleJalon
     *
     * @return string
     */
    public function getIfpIdLibelleJalon()
    {
        return $this->ifpIdLibelleJalon;
    }

    /**
     * Set ifpIdNumSiren
     *
     * @param string $ifpIdNumSiren
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNumSiren($ifpIdNumSiren)
    {
        $this->ifpIdNumSiren = $ifpIdNumSiren;

        return $this;
    }

    /**
     * Get ifpIdNumSiren
     *
     * @return string
     */
    public function getIfpIdNumSiren()
    {
        return $this->ifpIdNumSiren;
    }

    /**
     * Set ifpIdNumSiret
     *
     * @param string $ifpIdNumSiret
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNumSiret($ifpIdNumSiret)
    {
        $this->ifpIdNumSiret = $ifpIdNumSiret;

        return $this;
    }

    /**
     * Get ifpIdNumSiret
     *
     * @return string
     */
    public function getIfpIdNumSiret()
    {
        return $this->ifpIdNumSiret;
    }

    /**
     * Set ifpIdIndiceClassement
     *
     * @param string $ifpIdIndiceClassement
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdIndiceClassement($ifpIdIndiceClassement)
    {
        $this->ifpIdIndiceClassement = $ifpIdIndiceClassement;

        return $this;
    }

    /**
     * Get ifpIdIndiceClassement
     *
     * @return string
     */
    public function getIfpIdIndiceClassement()
    {
        return $this->ifpIdIndiceClassement;
    }

    /**
     * Set ifpIdUniqueDocument
     *
     * @param string $ifpIdUniqueDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdUniqueDocument($ifpIdUniqueDocument)
    {
        $this->ifpIdUniqueDocument = $ifpIdUniqueDocument;

        return $this;
    }

    /**
     * Get ifpIdUniqueDocument
     *
     * @return string
     */
    public function getIfpIdUniqueDocument()
    {
        return $this->ifpIdUniqueDocument;
    }

    /**
     * Set ifpIdTypeDocument
     *
     * @param string $ifpIdTypeDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdTypeDocument($ifpIdTypeDocument)
    {
        $this->ifpIdTypeDocument = $ifpIdTypeDocument;

        return $this;
    }

    /**
     * Get ifpIdTypeDocument
     *
     * @return string
     */
    public function getIfpIdTypeDocument()
    {
        return $this->ifpIdTypeDocument;
    }

    /**
     * Set ifpIdLibelleDocument
     *
     * @param string $ifpIdLibelleDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdLibelleDocument($ifpIdLibelleDocument)
    {
        $this->ifpIdLibelleDocument = $ifpIdLibelleDocument;

        return $this;
    }

    /**
     * Get ifpIdLibelleDocument
     *
     * @return string
     */
    public function getIfpIdLibelleDocument()
    {
        return $this->ifpIdLibelleDocument;
    }

    /**
     * Set ifpIdFormatDocument
     *
     * @param string $ifpIdFormatDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdFormatDocument($ifpIdFormatDocument)
    {
        $this->ifpIdFormatDocument = $ifpIdFormatDocument;

        return $this;
    }

    /**
     * Get ifpIdFormatDocument
     *
     * @return string
     */
    public function getIfpIdFormatDocument()
    {
        return $this->ifpIdFormatDocument;
    }

    /**
     * Set ifpIdAuteurDocument
     *
     * @param string $ifpIdAuteurDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAuteurDocument($ifpIdAuteurDocument)
    {
        $this->ifpIdAuteurDocument = $ifpIdAuteurDocument;

        return $this;
    }

    /**
     * Get ifpIdAuteurDocument
     *
     * @return string
     */
    public function getIfpIdAuteurDocument()
    {
        return $this->ifpIdAuteurDocument;
    }

    /**
     * Set ifpIdSourceDocument
     *
     * @param string $ifpIdSourceDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdSourceDocument($ifpIdSourceDocument)
    {
        $this->ifpIdSourceDocument = $ifpIdSourceDocument;

        return $this;
    }

    /**
     * Get ifpIdSourceDocument
     *
     * @return string
     */
    public function getIfpIdSourceDocument()
    {
        return $this->ifpIdSourceDocument;
    }

    /**
     * Set ifpIdNumVersionDocument
     *
     * @param string $ifpIdNumVersionDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNumVersionDocument($ifpIdNumVersionDocument)
    {
        $this->ifpIdNumVersionDocument = $ifpIdNumVersionDocument;

        return $this;
    }

    /**
     * Get ifpIdNumVersionDocument
     *
     * @return string
     */
    public function getIfpIdNumVersionDocument()
    {
        return $this->ifpIdNumVersionDocument;
    }

    /**
     * Set ifpIdPoidsDocument
     *
     * @param string $ifpIdPoidsDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdPoidsDocument($ifpIdPoidsDocument)
    {
        $this->ifpIdPoidsDocument = $ifpIdPoidsDocument;

        return $this;
    }

    /**
     * Get ifpIdPoidsDocument
     *
     * @return string
     */
    public function getIfpIdPoidsDocument()
    {
        return $this->ifpIdPoidsDocument;
    }

    /**
     * Set ifpIdNbrPagesDocument
     *
     * @param string $ifpIdNbrPagesDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNbrPagesDocument($ifpIdNbrPagesDocument)
    {
        $this->ifpIdNbrPagesDocument = $ifpIdNbrPagesDocument;

        return $this;
    }

    /**
     * Get ifpIdNbrPagesDocument
     *
     * @return string
     */
    public function getIfpIdNbrPagesDocument()
    {
        return $this->ifpIdNbrPagesDocument;
    }

    /**
     * Set ifpIdProfilArchivage
     *
     * @param string $ifpIdProfilArchivage
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdProfilArchivage($ifpIdProfilArchivage)
    {
        $this->ifpIdProfilArchivage = $ifpIdProfilArchivage;

        return $this;
    }

    /**
     * Get ifpIdProfilArchivage
     *
     * @return string
     */
    public function getIfpIdProfilArchivage()
    {
        return $this->ifpIdProfilArchivage;
    }

    /**
     * Set ifpIdEtatArchive
     *
     * @param string $ifpIdEtatArchive
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdEtatArchive($ifpIdEtatArchive)
    {
        $this->ifpIdEtatArchive = $ifpIdEtatArchive;

        return $this;
    }

    /**
     * Get ifpIdEtatArchive
     *
     * @return string
     */
    public function getIfpIdEtatArchive()
    {
        return $this->ifpIdEtatArchive;
    }

    /**
     * Set ifpIdPeriodePaie
     *
     * @param string $ifpIdPeriodePaie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdPeriodePaie($ifpIdPeriodePaie)
    {
        $this->ifpIdPeriodePaie = $ifpIdPeriodePaie;

        return $this;
    }

    /**
     * Get ifpIdPeriodePaie
     *
     * @return string
     */
    public function getIfpIdPeriodePaie()
    {
        return $this->ifpIdPeriodePaie;
    }

    /**
     * Set ifpIdPeriodeExerciceSociale
     *
     * @param string $ifpIdPeriodeExerciceSociale
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdPeriodeExerciceSociale($ifpIdPeriodeExerciceSociale)
    {
        $this->ifpIdPeriodeExerciceSociale = $ifpIdPeriodeExerciceSociale;

        return $this;
    }

    /**
     * Get ifpIdPeriodeExerciceSociale
     *
     * @return string
     */
    public function getIfpIdPeriodeExerciceSociale()
    {
        return $this->ifpIdPeriodeExerciceSociale;
    }

    /**
     * Set ifpIdDateDernierAccesDocument
     *
     * @param \DateTime $ifpIdDateDernierAccesDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDateDernierAccesDocument($ifpIdDateDernierAccesDocument)
    {
        $this->ifpIdDateDernierAccesDocument = $ifpIdDateDernierAccesDocument;

        return $this;
    }

    /**
     * Get ifpIdDateDernierAccesDocument
     *
     * @return \DateTime
     */
    public function getIfpIdDateDernierAccesDocument()
    {
        return $this->ifpIdDateDernierAccesDocument;
    }

    /**
     * Set ifpIdDateArchivageDocument
     *
     * @param \DateTime $ifpIdDateArchivageDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDateArchivageDocument($ifpIdDateArchivageDocument)
    {
        $this->ifpIdDateArchivageDocument = $ifpIdDateArchivageDocument;

        return $this;
    }

    /**
     * Get ifpIdDateArchivageDocument
     *
     * @return \DateTime
     */
    public function getIfpIdDateArchivageDocument()
    {
        return $this->ifpIdDateArchivageDocument;
    }

    /**
     * Set ifpIdDureeArchivageDocument
     *
     * @param string $ifpIdDureeArchivageDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDureeArchivageDocument($ifpIdDureeArchivageDocument)
    {
        $this->ifpIdDureeArchivageDocument = $ifpIdDureeArchivageDocument;

        return $this;
    }

    /**
     * Get ifpIdDureeArchivageDocument
     *
     * @return string
     */
    public function getIfpIdDureeArchivageDocument()
    {
        return $this->ifpIdDureeArchivageDocument;
    }

    /**
     * Set ifpIdDateFinArchivageDocument
     *
     * @param \DateTime $ifpIdDateFinArchivageDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDateFinArchivageDocument($ifpIdDateFinArchivageDocument)
    {
        $this->ifpIdDateFinArchivageDocument = $ifpIdDateFinArchivageDocument;

        return $this;
    }

    /**
     * Get ifpIdDateFinArchivageDocument
     *
     * @return \DateTime
     */
    public function getIfpIdDateFinArchivageDocument()
    {
        return $this->ifpIdDateFinArchivageDocument;
    }

    /**
     * Set ifpIdNomSalarie
     *
     * @param string $ifpIdNomSalarie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNomSalarie($ifpIdNomSalarie)
    {
        $this->ifpIdNomSalarie = $ifpIdNomSalarie;

        return $this;
    }

    /**
     * Get ifpIdNomSalarie
     *
     * @return string
     */
    public function getIfpIdNomSalarie()
    {
        return $this->ifpIdNomSalarie;
    }

    /**
     * Set ifpIdPrenomSalarie
     *
     * @param string $ifpIdPrenomSalarie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdPrenomSalarie($ifpIdPrenomSalarie)
    {
        $this->ifpIdPrenomSalarie = $ifpIdPrenomSalarie;

        return $this;
    }

    /**
     * Get ifpIdPrenomSalarie
     *
     * @return string
     */
    public function getIfpIdPrenomSalarie()
    {
        return $this->ifpIdPrenomSalarie;
    }

    /**
     * Set ifpIdNomJeuneFilleSalarie
     *
     * @param string $ifpIdNomJeuneFilleSalarie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNomJeuneFilleSalarie($ifpIdNomJeuneFilleSalarie)
    {
        $this->ifpIdNomJeuneFilleSalarie = $ifpIdNomJeuneFilleSalarie;

        return $this;
    }

    /**
     * Get ifpIdNomJeuneFilleSalarie
     *
     * @return string
     */
    public function getIfpIdNomJeuneFilleSalarie()
    {
        return $this->ifpIdNomJeuneFilleSalarie;
    }

    /**
     * Set ifpIdDateNaissanceSalarie
     *
     * @param \DateTime $ifpIdDateNaissanceSalarie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDateNaissanceSalarie($ifpIdDateNaissanceSalarie)
    {
        $this->ifpIdDateNaissanceSalarie = $ifpIdDateNaissanceSalarie;

        return $this;
    }

    /**
     * Get ifpIdDateNaissanceSalarie
     *
     * @return \DateTime
     */
    public function getIfpIdDateNaissanceSalarie()
    {
        return $this->ifpIdDateNaissanceSalarie;
    }

    /**
     * Set ifpIdDateEntree
     *
     * @param \DateTime $ifpIdDateEntree
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDateEntree($ifpIdDateEntree)
    {
        $this->ifpIdDateEntree = $ifpIdDateEntree;

        return $this;
    }

    /**
     * Get ifpIdDateEntree
     *
     * @return \DateTime
     */
    public function getIfpIdDateEntree()
    {
        return $this->ifpIdDateEntree;
    }

    /**
     * Set ifpIdDateSortie
     *
     * @param \DateTime $ifpIdDateSortie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDateSortie($ifpIdDateSortie)
    {
        $this->ifpIdDateSortie = $ifpIdDateSortie;

        return $this;
    }

    /**
     * Get ifpIdDateSortie
     *
     * @return \DateTime
     */
    public function getIfpIdDateSortie()
    {
        return $this->ifpIdDateSortie;
    }

    /**
     * Set ifpIdNumNir
     *
     * @param string $ifpIdNumNir
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNumNir($ifpIdNumNir)
    {
        $this->ifpIdNumNir = $ifpIdNumNir;

        return $this;
    }

    /**
     * Get ifpIdNumNir
     *
     * @return string
     */
    public function getIfpIdNumNir()
    {
        return $this->ifpIdNumNir;
    }

    /**
     * Set ifpIdCodeCategProfessionnelle
     *
     * @param string $ifpIdCodeCategProfessionnelle
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdCodeCategProfessionnelle($ifpIdCodeCategProfessionnelle)
    {
        $this->ifpIdCodeCategProfessionnelle = $ifpIdCodeCategProfessionnelle;

        return $this;
    }

    /**
     * Get ifpIdCodeCategProfessionnelle
     *
     * @return string
     */
    public function getIfpIdCodeCategProfessionnelle()
    {
        return $this->ifpIdCodeCategProfessionnelle;
    }

    /**
     * Set ifpIdCodeCategSocioProf
     *
     * @param string $ifpIdCodeCategSocioProf
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdCodeCategSocioProf($ifpIdCodeCategSocioProf)
    {
        $this->ifpIdCodeCategSocioProf = $ifpIdCodeCategSocioProf;

        return $this;
    }

    /**
     * Get ifpIdCodeCategSocioProf
     *
     * @return string
     */
    public function getIfpIdCodeCategSocioProf()
    {
        return $this->ifpIdCodeCategSocioProf;
    }

    /**
     * Set ifpIdTypeContrat
     *
     * @param string $ifpIdTypeContrat
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdTypeContrat($ifpIdTypeContrat)
    {
        $this->ifpIdTypeContrat = $ifpIdTypeContrat;

        return $this;
    }

    /**
     * Get ifpIdTypeContrat
     *
     * @return string
     */
    public function getIfpIdTypeContrat()
    {
        return $this->ifpIdTypeContrat;
    }

    /**
     * Set ifpIdAffectation1
     *
     * @param string $ifpIdAffectation1
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAffectation1($ifpIdAffectation1)
    {
        $this->ifpIdAffectation1 = $ifpIdAffectation1;

        return $this;
    }

    /**
     * Get ifpIdAffectation1
     *
     * @return string
     */
    public function getIfpIdAffectation1()
    {
        return $this->ifpIdAffectation1;
    }

    /**
     * Set ifpIdAffectation2
     *
     * @param string $ifpIdAffectation2
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAffectation2($ifpIdAffectation2)
    {
        $this->ifpIdAffectation2 = $ifpIdAffectation2;

        return $this;
    }

    /**
     * Get ifpIdAffectation2
     *
     * @return string
     */
    public function getIfpIdAffectation2()
    {
        return $this->ifpIdAffectation2;
    }

    /**
     * Set ifpIdAffectation3
     *
     * @param string $ifpIdAffectation3
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAffectation3($ifpIdAffectation3)
    {
        $this->ifpIdAffectation3 = $ifpIdAffectation3;

        return $this;
    }

    /**
     * Get ifpIdAffectation3
     *
     * @return string
     */
    public function getIfpIdAffectation3()
    {
        return $this->ifpIdAffectation3;
    }

    /**
     * Set ifpIdLibre1
     *
     * @param string $ifpIdLibre1
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdLibre1($ifpIdLibre1)
    {
        $this->ifpIdLibre1 = $ifpIdLibre1;

        return $this;
    }

    /**
     * Get ifpIdLibre1
     *
     * @return string
     */
    public function getIfpIdLibre1()
    {
        return $this->ifpIdLibre1;
    }

    /**
     * Set ifpIdLibre2
     *
     * @param string $ifpIdLibre2
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdLibre2($ifpIdLibre2)
    {
        $this->ifpIdLibre2 = $ifpIdLibre2;

        return $this;
    }

    /**
     * Get ifpIdLibre2
     *
     * @return string
     */
    public function getIfpIdLibre2()
    {
        return $this->ifpIdLibre2;
    }

    /**
     * Set ifpIdAffectation1Date
     *
     * @param string $ifpIdAffectation1Date
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAffectation1Date($ifpIdAffectation1Date)
    {
        $this->ifpIdAffectation1Date = $ifpIdAffectation1Date;

        return $this;
    }

    /**
     * Get ifpIdAffectation1Date
     *
     * @return string
     */
    public function getIfpIdAffectation1Date()
    {
        return $this->ifpIdAffectation1Date;
    }

    /**
     * Set ifpIdAffectation2Date
     *
     * @param string $ifpIdAffectation2Date
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAffectation2Date($ifpIdAffectation2Date)
    {
        $this->ifpIdAffectation2Date = $ifpIdAffectation2Date;

        return $this;
    }

    /**
     * Get ifpIdAffectation2Date
     *
     * @return string
     */
    public function getIfpIdAffectation2Date()
    {
        return $this->ifpIdAffectation2Date;
    }

    /**
     * Set ifpIdAffectation3Date
     *
     * @param string $ifpIdAffectation3Date
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAffectation3Date($ifpIdAffectation3Date)
    {
        $this->ifpIdAffectation3Date = $ifpIdAffectation3Date;

        return $this;
    }

    /**
     * Get ifpIdAffectation3Date
     *
     * @return string
     */
    public function getIfpIdAffectation3Date()
    {
        return $this->ifpIdAffectation3Date;
    }

    /**
     * Set ifpIdLibre1Date
     *
     * @param string $ifpIdLibre1Date
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdLibre1Date($ifpIdLibre1Date)
    {
        $this->ifpIdLibre1Date = $ifpIdLibre1Date;

        return $this;
    }

    /**
     * Get ifpIdLibre1Date
     *
     * @return string
     */
    public function getIfpIdLibre1Date()
    {
        return $this->ifpIdLibre1Date;
    }

    /**
     * Set ifpIdLibre2Date
     *
     * @param string $ifpIdLibre2Date
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdLibre2Date($ifpIdLibre2Date)
    {
        $this->ifpIdLibre2Date = $ifpIdLibre2Date;

        return $this;
    }

    /**
     * Get ifpIdLibre2Date
     *
     * @return string
     */
    public function getIfpIdLibre2Date()
    {
        return $this->ifpIdLibre2Date;
    }

    /**
     * Set ifpIdNumMatriculeRh
     *
     * @param string $ifpIdNumMatriculeRh
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNumMatriculeRh($ifpIdNumMatriculeRh)
    {
        $this->ifpIdNumMatriculeRh = $ifpIdNumMatriculeRh;

        return $this;
    }

    /**
     * Get ifpIdNumMatriculeRh
     *
     * @return string
     */
    public function getIfpIdNumMatriculeRh()
    {
        return $this->ifpIdNumMatriculeRh;
    }

    /**
     * Set ifpIdNumMatriculeGroupe
     *
     * @param string $ifpIdNumMatriculeGroupe
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNumMatriculeGroupe($ifpIdNumMatriculeGroupe)
    {
        $this->ifpIdNumMatriculeGroupe = $ifpIdNumMatriculeGroupe;

        return $this;
    }

    /**
     * Get ifpIdNumMatriculeGroupe
     *
     * @return string
     */
    public function getIfpIdNumMatriculeGroupe()
    {
        return $this->ifpIdNumMatriculeGroupe;
    }

    /**
     * Set ifpIdAnnotation
     *
     * @param string $ifpIdAnnotation
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAnnotation($ifpIdAnnotation)
    {
        $this->ifpIdAnnotation = $ifpIdAnnotation;

        return $this;
    }

    /**
     * Get ifpIdAnnotation
     *
     * @return string
     */
    public function getIfpIdAnnotation()
    {
        return $this->ifpIdAnnotation;
    }

    /**
     * Set ifpIdConteneur
     *
     * @param string $ifpIdConteneur
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdConteneur($ifpIdConteneur)
    {
        $this->ifpIdConteneur = $ifpIdConteneur;

        return $this;
    }

    /**
     * Get ifpIdConteneur
     *
     * @return string
     */
    public function getIfpIdConteneur()
    {
        return $this->ifpIdConteneur;
    }

    /**
     * Set ifpIdBoite
     *
     * @param string $ifpIdBoite
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdBoite($ifpIdBoite)
    {
        $this->ifpIdBoite = $ifpIdBoite;

        return $this;
    }

    /**
     * Get ifpIdBoite
     *
     * @return string
     */
    public function getIfpIdBoite()
    {
        return $this->ifpIdBoite;
    }

    /**
     * Set ifpIdLot
     *
     * @param string $ifpIdLot
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdLot($ifpIdLot)
    {
        $this->ifpIdLot = $ifpIdLot;

        return $this;
    }

    /**
     * Get ifpIdLot
     *
     * @return string
     */
    public function getIfpIdLot()
    {
        return $this->ifpIdLot;
    }

    /**
     * Set ifpIdNumOrdre
     *
     * @param string $ifpIdNumOrdre
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNumOrdre($ifpIdNumOrdre)
    {
        $this->ifpIdNumOrdre = $ifpIdNumOrdre;

        return $this;
    }

    /**
     * Get ifpIdNumOrdre
     *
     * @return string
     */
    public function getIfpIdNumOrdre()
    {
        return $this->ifpIdNumOrdre;
    }

    /**
     * Set ifpArchiveCfec
     *
     * @param string $ifpArchiveCfec
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpArchiveCfec($ifpArchiveCfec)
    {
        $this->ifpArchiveCfec = $ifpArchiveCfec;

        return $this;
    }

    /**
     * Get ifpArchiveCfec
     *
     * @return string
     */
    public function getIfpArchiveCfec()
    {
        return $this->ifpArchiveCfec;
    }

    /**
     * Set ifpArchiveSerialnumber
     *
     * @param string $ifpArchiveSerialnumber
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpArchiveSerialnumber($ifpArchiveSerialnumber)
    {
        $this->ifpArchiveSerialnumber = $ifpArchiveSerialnumber;

        return $this;
    }

    /**
     * Get ifpArchiveSerialnumber
     *
     * @return string
     */
    public function getIfpArchiveSerialnumber()
    {
        return $this->ifpArchiveSerialnumber;
    }

    /**
     * Set ifpArchiveDatetime
     *
     * @param string $ifpArchiveDatetime
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpArchiveDatetime($ifpArchiveDatetime)
    {
        $this->ifpArchiveDatetime = $ifpArchiveDatetime;

        return $this;
    }

    /**
     * Get ifpArchiveDatetime
     *
     * @return string
     */
    public function getIfpArchiveDatetime()
    {
        return $this->ifpArchiveDatetime;
    }

    /**
     * Set ifpArchiveName
     *
     * @param string $ifpArchiveName
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpArchiveName($ifpArchiveName)
    {
        $this->ifpArchiveName = $ifpArchiveName;

        return $this;
    }

    /**
     * Get ifpArchiveName
     *
     * @return string
     */
    public function getIfpArchiveName()
    {
        return $this->ifpArchiveName;
    }

    /**
     * Set ifpArchiveCfe
     *
     * @param string $ifpArchiveCfe
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpArchiveCfe($ifpArchiveCfe)
    {
        $this->ifpArchiveCfe = $ifpArchiveCfe;

        return $this;
    }

    /**
     * Get ifpArchiveCfe
     *
     * @return string
     */
    public function getIfpArchiveCfe()
    {
        return $this->ifpArchiveCfe;
    }

    /**
     * Set ifpNumeropdf
     *
     * @param string $ifpNumeropdf
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpNumeropdf($ifpNumeropdf)
    {
        $this->ifpNumeropdf = $ifpNumeropdf;

        return $this;
    }

    /**
     * Get ifpNumeropdf
     *
     * @return string
     */
    public function getIfpNumeropdf()
    {
        return $this->ifpNumeropdf;
    }

    /**
     * Set ifpOpnProvenance
     *
     * @param string $ifpOpnProvenance
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpOpnProvenance($ifpOpnProvenance)
    {
        $this->ifpOpnProvenance = $ifpOpnProvenance;

        return $this;
    }

    /**
     * Get ifpOpnProvenance
     *
     * @return string
     */
    public function getIfpOpnProvenance()
    {
        return $this->ifpOpnProvenance;
    }

    /**
     * Set ifpStatusNum
     *
     * @param string $ifpStatusNum
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpStatusNum($ifpStatusNum)
    {
        $this->ifpStatusNum = $ifpStatusNum;

        return $this;
    }

    /**
     * Get ifpStatusNum
     *
     * @return string
     */
    public function getIfpStatusNum()
    {
        return $this->ifpStatusNum;
    }

    /**
     * Set ifpIsDoublon
     *
     * @param boolean $ifpIsDoublon
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIsDoublon($ifpIsDoublon)
    {
        $this->ifpIsDoublon = $ifpIsDoublon;

        return $this;
    }

    /**
     * Get ifpIsDoublon
     *
     * @return boolean
     */
    public function isIfpIsDoublon()
    {
        return $this->ifpIsDoublon;
    }

    /**
     * Set ifpLogin
     *
     * @param string $ifpLogin
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpLogin($ifpLogin)
    {
        $this->ifpLogin = $ifpLogin;

        return $this;
    }

    /**
     * Get ifpLogin
     *
     * @return string
     */
    public function getIfpLogin()
    {
        return $this->ifpLogin;
    }

    /**
     * Set ifpModedt
     *
     * @param string $ifpModedt
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpModedt($ifpModedt)
    {
        $this->ifpModedt = $ifpModedt;

        return $this;
    }

    /**
     * Get ifpModedt
     *
     * @return string
     */
    public function getIfpModedt()
    {
        return $this->ifpModedt;
    }

    /**
     * Set ifpNumdtr
     *
     * @param string $ifpNumdtr
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpNumdtr($ifpNumdtr)
    {
        $this->ifpNumdtr = $ifpNumdtr;

        return $this;
    }

    /**
     * Get ifpNumdtr
     *
     * @return string
     */
    public function getIfpNumdtr()
    {
        return $this->ifpNumdtr;
    }

    /**
     * Set ifpOldIdDateDernierAccesDocument
     *
     * @param string $ifpOldIdDateDernierAccesDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpOldIdDateDernierAccesDocument($ifpOldIdDateDernierAccesDocument)
    {
        $this->ifpOldIdDateDernierAccesDocument = $ifpOldIdDateDernierAccesDocument;

        return $this;
    }

    /**
     * Get ifpOldIdDateDernierAccesDocument
     *
     * @return string
     */
    public function getIfpOldIdDateDernierAccesDocument()
    {
        return $this->ifpOldIdDateDernierAccesDocument;
    }

    /**
     * Set ifpOldIdDateArchivageDocument
     *
     * @param string $ifpOldIdDateArchivageDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpOldIdDateArchivageDocument($ifpOldIdDateArchivageDocument)
    {
        $this->ifpOldIdDateArchivageDocument = $ifpOldIdDateArchivageDocument;

        return $this;
    }

    /**
     * Get ifpOldIdDateArchivageDocument
     *
     * @return string
     */
    public function getIfpOldIdDateArchivageDocument()
    {
        return $this->ifpOldIdDateArchivageDocument;
    }

    /**
     * Set ifpOldIdDateFinArchivageDocument
     *
     * @param string $ifpOldIdDateFinArchivageDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpOldIdDateFinArchivageDocument($ifpOldIdDateFinArchivageDocument)
    {
        $this->ifpOldIdDateFinArchivageDocument = $ifpOldIdDateFinArchivageDocument;

        return $this;
    }

    /**
     * Get ifpOldIdDateFinArchivageDocument
     *
     * @return string
     */
    public function getIfpOldIdDateFinArchivageDocument()
    {
        return $this->ifpOldIdDateFinArchivageDocument;
    }

    /**
     * Set ifpOldIdDateNaissanceSalarie
     *
     * @param string $ifpOldIdDateNaissanceSalarie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpOldIdDateNaissanceSalarie($ifpOldIdDateNaissanceSalarie)
    {
        $this->ifpOldIdDateNaissanceSalarie = $ifpOldIdDateNaissanceSalarie;

        return $this;
    }

    /**
     * Get ifpOldIdDateNaissanceSalarie
     *
     * @return string
     */
    public function getIfpOldIdDateNaissanceSalarie()
    {
        return $this->ifpOldIdDateNaissanceSalarie;
    }

    /**
     * Set ifpOldIdDateEntree
     *
     * @param string $ifpOldIdDateEntree
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpOldIdDateEntree($ifpOldIdDateEntree)
    {
        $this->ifpOldIdDateEntree = $ifpOldIdDateEntree;

        return $this;
    }

    /**
     * Get ifpOldIdDateEntree
     *
     * @return string
     */
    public function getIfpOldIdDateEntree()
    {
        return $this->ifpOldIdDateEntree;
    }

    /**
     * Set ifpOldIdDateSortie
     *
     * @param string $ifpOldIdDateSortie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpOldIdDateSortie($ifpOldIdDateSortie)
    {
        $this->ifpOldIdDateSortie = $ifpOldIdDateSortie;

        return $this;
    }

    /**
     * Get ifpOldIdDateSortie
     *
     * @return string
     */
    public function getIfpOldIdDateSortie()
    {
        return $this->ifpOldIdDateSortie;
    }

    /**
     * Set ifpIdCodeActivite
     *
     * @param string $ifpIdCodeActivite
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdCodeActivite($ifpIdCodeActivite)
    {
        $this->ifpIdCodeActivite = $ifpIdCodeActivite;

        return $this;
    }

    /**
     * Get ifpIdCodeActivite
     *
     * @return string
     */
    public function getIfpIdCodeActivite()
    {
        return $this->ifpIdCodeActivite;
    }

    /**
     * Set ifpCycleFinDeVie
     *
     * @param boolean $ifpCycleFinDeVie
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpCycleFinDeVie($ifpCycleFinDeVie)
    {
        $this->ifpCycleFinDeVie = $ifpCycleFinDeVie;

        return $this;
    }

    /**
     * Get ifpCycleFinDeVie
     *
     * @return boolean
     */
    public function isIfpCycleFinDeVie()
    {
        return $this->ifpCycleFinDeVie;
    }

    /**
     * Set ifpCyclePurger
     *
     * @param string $ifpCyclePurger
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpCyclePurger($ifpCyclePurger)
    {
        $this->ifpCyclePurger = $ifpCyclePurger;

        return $this;
    }

    /**
     * Get ifpCyclePurger
     *
     * @return string
     */
    public function getIfpCyclePurger()
    {
        return $this->ifpCyclePurger;
    }

    /**
     * Set ifpGeler
     *
     * @param boolean $ifpGeler
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpGeler($ifpGeler)
    {
        $this->ifpGeler = $ifpGeler;

        return $this;
    }

    /**
     * Get ifpGeler
     *
     * @return boolean
     */
    public function isIfpGeler()
    {
        return $this->ifpGeler;
    }

    /**
     * Set ifpIdDate1
     *
     * @param string $ifpIdDate1
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDate1($ifpIdDate1)
    {
        $this->ifpIdDate1 = $ifpIdDate1;

        return $this;
    }

    /**
     * Get ifpIdDate1
     *
     * @return string
     */
    public function getIfpIdDate1()
    {
        return $this->ifpIdDate1;
    }

    /**
     * Set ifpIdDate2
     *
     * @param string $ifpIdDate2
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDate2($ifpIdDate2)
    {
        $this->ifpIdDate2 = $ifpIdDate2;

        return $this;
    }

    /**
     * Get ifpIdDate2
     *
     * @return string
     */
    public function getIfpIdDate2()
    {
        return $this->ifpIdDate2;
    }

    /**
     * Set ifpIdDate3
     *
     * @param string $ifpIdDate3
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDate3($ifpIdDate3)
    {
        $this->ifpIdDate3 = $ifpIdDate3;

        return $this;
    }

    /**
     * Get ifpIdDate3
     *
     * @return string
     */
    public function getIfpIdDate3()
    {
        return $this->ifpIdDate3;
    }

    /**
     * Set ifpIdDate4
     *
     * @param string $ifpIdDate4
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDate4($ifpIdDate4)
    {
        $this->ifpIdDate4 = $ifpIdDate4;

        return $this;
    }

    /**
     * Get ifpIdDate4
     *
     * @return string
     */
    public function getIfpIdDate4()
    {
        return $this->ifpIdDate4;
    }

    /**
     * Set ifpIdDate5
     *
     * @param string $ifpIdDate5
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDate5($ifpIdDate5)
    {
        $this->ifpIdDate5 = $ifpIdDate5;

        return $this;
    }

    /**
     * Get ifpIdDate5
     *
     * @return string
     */
    public function getIfpIdDate5()
    {
        return $this->ifpIdDate5;
    }

    /**
     * Set ifpIdDate6
     *
     * @param string $ifpIdDate6
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDate6($ifpIdDate6)
    {
        $this->ifpIdDate6 = $ifpIdDate6;

        return $this;
    }

    /**
     * Get ifpIdDate6
     *
     * @return string
     */
    public function getIfpIdDate6()
    {
        return $this->ifpIdDate6;
    }

    /**
     * Set ifpIdDate7
     *
     * @param string $ifpIdDate7
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDate7($ifpIdDate7)
    {
        $this->ifpIdDate7 = $ifpIdDate7;

        return $this;
    }

    /**
     * Get ifpIdDate7
     *
     * @return string
     */
    public function getIfpIdDate7()
    {
        return $this->ifpIdDate7;
    }

    /**
     * Set ifpIdDate8
     *
     * @param string $ifpIdDate8
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDate8($ifpIdDate8)
    {
        $this->ifpIdDate8 = $ifpIdDate8;

        return $this;
    }

    /**
     * Get ifpIdDate8
     *
     * @return string
     */
    public function getIfpIdDate8()
    {
        return $this->ifpIdDate8;
    }

    /**
     * Set ifpIdDateAdp1
     *
     * @param string $ifpIdDateAdp1
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDateAdp1($ifpIdDateAdp1)
    {
        $this->ifpIdDateAdp1 = $ifpIdDateAdp1;

        return $this;
    }

    /**
     * Get ifpIdDateAdp1
     *
     * @return string
     */
    public function getIfpIdDateAdp1()
    {
        return $this->ifpIdDateAdp1;
    }

    /**
     * Set ifpIdDateAdp2
     *
     * @param string $ifpIdDateAdp2
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdDateAdp2($ifpIdDateAdp2)
    {
        $this->ifpIdDateAdp2 = $ifpIdDateAdp2;

        return $this;
    }

    /**
     * Get ifpIdDateAdp2
     *
     * @return string
     */
    public function getIfpIdDateAdp2()
    {
        return $this->ifpIdDateAdp2;
    }

    /**
     * Set ifpIdAlphanum1
     *
     * @param string $ifpIdAlphanum1
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum1($ifpIdAlphanum1)
    {
        $this->ifpIdAlphanum1 = $ifpIdAlphanum1;

        return $this;
    }

    /**
     * Get ifpIdAlphanum1
     *
     * @return string
     */
    public function getIfpIdAlphanum1()
    {
        return $this->ifpIdAlphanum1;
    }

    /**
     * Set ifpIdAlphanum2
     *
     * @param string $ifpIdAlphanum2
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum2($ifpIdAlphanum2)
    {
        $this->ifpIdAlphanum2 = $ifpIdAlphanum2;

        return $this;
    }

    /**
     * Get ifpIdAlphanum2
     *
     * @return string
     */
    public function getIfpIdAlphanum2()
    {
        return $this->ifpIdAlphanum2;
    }

    /**
     * Set ifpIdAlphanum3
     *
     * @param string $ifpIdAlphanum3
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum3($ifpIdAlphanum3)
    {
        $this->ifpIdAlphanum3 = $ifpIdAlphanum3;

        return $this;
    }

    /**
     * Get ifpIdAlphanum3
     *
     * @return string
     */
    public function getIfpIdAlphanum3()
    {
        return $this->ifpIdAlphanum3;
    }

    /**
     * Set ifpIdAlphanum4
     *
     * @param string $ifpIdAlphanum4
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum4($ifpIdAlphanum4)
    {
        $this->ifpIdAlphanum4 = $ifpIdAlphanum4;

        return $this;
    }

    /**
     * Get ifpIdAlphanum4
     *
     * @return string
     */
    public function getIfpIdAlphanum4()
    {
        return $this->ifpIdAlphanum4;
    }

    /**
     * Set ifpIdAlphanum5
     *
     * @param string $ifpIdAlphanum5
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum5($ifpIdAlphanum5)
    {
        $this->ifpIdAlphanum5 = $ifpIdAlphanum5;

        return $this;
    }

    /**
     * Get ifpIdAlphanum5
     *
     * @return string
     */
    public function getIfpIdAlphanum5()
    {
        return $this->ifpIdAlphanum5;
    }

    /**
     * Set ifpIdAlphanum6
     *
     * @param string $ifpIdAlphanum6
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum6($ifpIdAlphanum6)
    {
        $this->ifpIdAlphanum6 = $ifpIdAlphanum6;

        return $this;
    }

    /**
     * Get ifpIdAlphanum6
     *
     * @return string
     */
    public function getIfpIdAlphanum6()
    {
        return $this->ifpIdAlphanum6;
    }

    /**
     * Set ifpIdAlphanum7
     *
     * @param string $ifpIdAlphanum7
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum7($ifpIdAlphanum7)
    {
        $this->ifpIdAlphanum7 = $ifpIdAlphanum7;

        return $this;
    }

    /**
     * Get ifpIdAlphanum7
     *
     * @return string
     */
    public function getIfpIdAlphanum7()
    {
        return $this->ifpIdAlphanum7;
    }

    /**
     * Set ifpIdAlphanum8
     *
     * @param string $ifpIdAlphanum8
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum8($ifpIdAlphanum8)
    {
        $this->ifpIdAlphanum8 = $ifpIdAlphanum8;

        return $this;
    }

    /**
     * Get ifpIdAlphanum8
     *
     * @return string
     */
    public function getIfpIdAlphanum8()
    {
        return $this->ifpIdAlphanum8;
    }

    /**
     * Set ifpIdAlphanum9
     *
     * @param string $ifpIdAlphanum9
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum9($ifpIdAlphanum9)
    {
        $this->ifpIdAlphanum9 = $ifpIdAlphanum9;

        return $this;
    }

    /**
     * Get ifpIdAlphanum9
     *
     * @return string
     */
    public function getIfpIdAlphanum9()
    {
        return $this->ifpIdAlphanum9;
    }

    /**
     * Set ifpIdAlphanum10
     *
     * @param string $ifpIdAlphanum10
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum10($ifpIdAlphanum10)
    {
        $this->ifpIdAlphanum10 = $ifpIdAlphanum10;

        return $this;
    }

    /**
     * Get ifpIdAlphanum10
     *
     * @return string
     */
    public function getIfpIdAlphanum10()
    {
        return $this->ifpIdAlphanum10;
    }

    /**
     * Set ifpIdAlphanum11
     *
     * @param string $ifpIdAlphanum11
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum11($ifpIdAlphanum11)
    {
        $this->ifpIdAlphanum11 = $ifpIdAlphanum11;

        return $this;
    }

    /**
     * Get ifpIdAlphanum11
     *
     * @return string
     */
    public function getIfpIdAlphanum11()
    {
        return $this->ifpIdAlphanum11;
    }

    /**
     * Set ifpIdAlphanum12
     *
     * @param string $ifpIdAlphanum12
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum12($ifpIdAlphanum12)
    {
        $this->ifpIdAlphanum12 = $ifpIdAlphanum12;

        return $this;
    }

    /**
     * Get ifpIdAlphanum12
     *
     * @return string
     */
    public function getIfpIdAlphanum12()
    {
        return $this->ifpIdAlphanum12;
    }

    /**
     * Set ifpIdAlphanum13
     *
     * @param string $ifpIdAlphanum13
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum13($ifpIdAlphanum13)
    {
        $this->ifpIdAlphanum13 = $ifpIdAlphanum13;

        return $this;
    }

    /**
     * Get ifpIdAlphanum13
     *
     * @return string
     */
    public function getIfpIdAlphanum13()
    {
        return $this->ifpIdAlphanum13;
    }

    /**
     * Set ifpIdAlphanum14
     *
     * @param string $ifpIdAlphanum14
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum14($ifpIdAlphanum14)
    {
        $this->ifpIdAlphanum14 = $ifpIdAlphanum14;

        return $this;
    }

    /**
     * Get ifpIdAlphanum14
     *
     * @return string
     */
    public function getIfpIdAlphanum14()
    {
        return $this->ifpIdAlphanum14;
    }

    /**
     * Set ifpIdAlphanum15
     *
     * @param string $ifpIdAlphanum15
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum15($ifpIdAlphanum15)
    {
        $this->ifpIdAlphanum15 = $ifpIdAlphanum15;

        return $this;
    }

    /**
     * Get ifpIdAlphanum15
     *
     * @return string
     */
    public function getIfpIdAlphanum15()
    {
        return $this->ifpIdAlphanum15;
    }

    /**
     * Set ifpIdAlphanum16
     *
     * @param string $ifpIdAlphanum16
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum16($ifpIdAlphanum16)
    {
        $this->ifpIdAlphanum16 = $ifpIdAlphanum16;

        return $this;
    }

    /**
     * Get ifpIdAlphanum16
     *
     * @return string
     */
    public function getIfpIdAlphanum16()
    {
        return $this->ifpIdAlphanum16;
    }

    /**
     * Set ifpIdAlphanum17
     *
     * @param string $ifpIdAlphanum17
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum17($ifpIdAlphanum17)
    {
        $this->ifpIdAlphanum17 = $ifpIdAlphanum17;

        return $this;
    }

    /**
     * Get ifpIdAlphanum17
     *
     * @return string
     */
    public function getIfpIdAlphanum17()
    {
        return $this->ifpIdAlphanum17;
    }

    /**
     * Set ifpIdAlphanum18
     *
     * @param string $ifpIdAlphanum18
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanum18($ifpIdAlphanum18)
    {
        $this->ifpIdAlphanum18 = $ifpIdAlphanum18;

        return $this;
    }

    /**
     * Get ifpIdAlphanum18
     *
     * @return string
     */
    public function getIfpIdAlphanum18()
    {
        return $this->ifpIdAlphanum18;
    }

    /**
     * Set ifpIdAlphanumAdp1
     *
     * @param string $ifpIdAlphanumAdp1
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanumAdp1($ifpIdAlphanumAdp1)
    {
        $this->ifpIdAlphanumAdp1 = $ifpIdAlphanumAdp1;

        return $this;
    }

    /**
     * Get ifpIdAlphanumAdp1
     *
     * @return string
     */
    public function getIfpIdAlphanumAdp1()
    {
        return $this->ifpIdAlphanumAdp1;
    }

    /**
     * Set ifpIdAlphanumAdp2
     *
     * @param string $ifpIdAlphanumAdp2
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdAlphanumAdp2($ifpIdAlphanumAdp2)
    {
        $this->ifpIdAlphanumAdp2 = $ifpIdAlphanumAdp2;

        return $this;
    }

    /**
     * Get ifpIdAlphanumAdp2
     *
     * @return string
     */
    public function getIfpIdAlphanumAdp2()
    {
        return $this->ifpIdAlphanumAdp2;
    }

    /**
     * Set ifpIdNum1
     *
     * @param float $ifpIdNum1
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum1($ifpIdNum1)
    {
        $this->ifpIdNum1 = $ifpIdNum1;

        return $this;
    }

    /**
     * Get ifpIdNum1
     *
     * @return float
     */
    public function getIfpIdNum1()
    {
        return $this->ifpIdNum1;
    }

    /**
     * Set ifpIdNum2
     *
     * @param float $ifpIdNum2
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum2($ifpIdNum2)
    {
        $this->ifpIdNum2 = $ifpIdNum2;

        return $this;
    }

    /**
     * Get ifpIdNum2
     *
     * @return float
     */
    public function getIfpIdNum2()
    {
        return $this->ifpIdNum2;
    }

    /**
     * Set ifpIdNum3
     *
     * @param float $ifpIdNum3
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum3($ifpIdNum3)
    {
        $this->ifpIdNum3 = $ifpIdNum3;

        return $this;
    }

    /**
     * Get ifpIdNum3
     *
     * @return float
     */
    public function getIfpIdNum3()
    {
        return $this->ifpIdNum3;
    }

    /**
     * Set ifpIdNum4
     *
     * @param float $ifpIdNum4
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum4($ifpIdNum4)
    {
        $this->ifpIdNum4 = $ifpIdNum4;

        return $this;
    }

    /**
     * Get ifpIdNum4
     *
     * @return float
     */
    public function getIfpIdNum4()
    {
        return $this->ifpIdNum4;
    }

    /**
     * Set ifpIdNum5
     *
     * @param float $ifpIdNum5
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum5($ifpIdNum5)
    {
        $this->ifpIdNum5 = $ifpIdNum5;

        return $this;
    }

    /**
     * Get ifpIdNum5
     *
     * @return float
     */
    public function getIfpIdNum5()
    {
        return $this->ifpIdNum5;
    }

    /**
     * Set ifpIdNum6
     *
     * @param float $ifpIdNum6
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum6($ifpIdNum6)
    {
        $this->ifpIdNum6 = $ifpIdNum6;

        return $this;
    }

    /**
     * Get ifpIdNum6
     *
     * @return float
     */
    public function getIfpIdNum6()
    {
        return $this->ifpIdNum6;
    }

    /**
     * Set ifpIdNum7
     *
     * @param float $ifpIdNum7
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum7($ifpIdNum7)
    {
        $this->ifpIdNum7 = $ifpIdNum7;

        return $this;
    }

    /**
     * Get ifpIdNum7
     *
     * @return float
     */
    public function getIfpIdNum7()
    {
        return $this->ifpIdNum7;
    }

    /**
     * Set ifpIdNum8
     *
     * @param float $ifpIdNum8
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum8($ifpIdNum8)
    {
        $this->ifpIdNum8 = $ifpIdNum8;

        return $this;
    }

    /**
     * Get ifpIdNum8
     *
     * @return float
     */
    public function getIfpIdNum8()
    {
        return $this->ifpIdNum8;
    }

    /**
     * Set ifpIdNum9
     *
     * @param float $ifpIdNum9
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum9($ifpIdNum9)
    {
        $this->ifpIdNum9 = $ifpIdNum9;

        return $this;
    }

    /**
     * Get ifpIdNum9
     *
     * @return float
     */
    public function getIfpIdNum9()
    {
        return $this->ifpIdNum9;
    }

    /**
     * Set ifpIdNum10
     *
     * @param float $ifpIdNum10
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIdNum10($ifpIdNum10)
    {
        $this->ifpIdNum10 = $ifpIdNum10;

        return $this;
    }

    /**
     * Get ifpIdNum10
     *
     * @return float
     */
    public function getIfpIdNum10()
    {
        return $this->ifpIdNum10;
    }

    /**
     * Set ifpCycleTempsParcouru
     *
     * @param string $ifpCycleTempsParcouru
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpCycleTempsParcouru($ifpCycleTempsParcouru)
    {
        $this->ifpCycleTempsParcouru = $ifpCycleTempsParcouru;

        return $this;
    }

    /**
     * Get ifpCycleTempsParcouru
     *
     * @return string
     */
    public function getIfpCycleTempsParcouru()
    {
        return $this->ifpCycleTempsParcouru;
    }

    /**
     * Set ifpCycleTempsRestant
     *
     * @param string $ifpCycleTempsRestant
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpCycleTempsRestant($ifpCycleTempsRestant)
    {
        $this->ifpCycleTempsRestant = $ifpCycleTempsRestant;

        return $this;
    }

    /**
     * Get ifpCycleTempsRestant
     *
     * @return string
     */
    public function getIfpCycleTempsRestant()
    {
        return $this->ifpCycleTempsRestant;
    }

    /**
     * Set ifpSetFinArchivage
     *
     * @param boolean $ifpSetFinArchivage
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpSetFinArchivage($ifpSetFinArchivage)
    {
        $this->ifpSetFinArchivage = $ifpSetFinArchivage;

        return $this;
    }

    /**
     * Get ifpSetFinArchivage
     *
     * @return boolean
     */
    public function isIfpSetFinArchivage()
    {
        return $this->ifpSetFinArchivage;
    }

    /**
     * Set ifpIsPersonnel
     *
     * @param boolean $ifpIsPersonnel
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpIsPersonnel($ifpIsPersonnel)
    {
        $this->ifpIsPersonnel = $ifpIsPersonnel;

        return $this;
    }

    /**
     * Get ifpIsPersonnel
     *
     * @return boolean
     */
    public function isIfpIsPersonnel()
    {
        return $this->ifpIsPersonnel;
    }

    /**
     * Set ifpCreatedAt
     *
     * @param \DateTime $ifpCreatedAt
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpCreatedAt($ifpCreatedAt)
    {
        $this->ifpCreatedAt = $ifpCreatedAt;

        return $this;
    }

    /**
     * Get ifpCreatedAt
     *
     * @return \DateTime
     */
    public function getIfpCreatedAt()
    {
        return $this->ifpCreatedAt;
    }

    /**
     * Set ifpUpdatedAt
     *
     * @param \DateTime $ifpUpdatedAt
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpUpdatedAt($ifpUpdatedAt)
    {
        $this->ifpUpdatedAt = $ifpUpdatedAt;

        return $this;
    }

    /**
     * Get ifpUpdatedAt
     *
     * @return \DateTime
     */
    public function getIfpUpdatedAt()
    {
        return $this->ifpUpdatedAt;
    }

    /**
     * Set ifpNumMatricule
     *
     * @param string $ifpNumMatricule
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpNumMatricule($ifpNumMatricule)
    {
        $this->ifpNumMatricule = $ifpNumMatricule;

        return $this;
    }

    /**
     * Get ifpNumMatricule
     *
     * @return \ApiBundle\Entity\IinIdxIndiv
     */
    public function getIfpNumMatricule()
    {
        return $this->ifpNumMatricule;
    }

    /**
     * Set ifpCodeDocument
     *
     * @param \ApiBundle\Entity\TypType $ifpCodeDocument
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpCodeDocument(\ApiBundle\Entity\TypType $ifpCodeDocument = null)
    {
        $this->ifpCodeDocument = $ifpCodeDocument;

        return $this;
    }

    /**
     * Get ifpCodeDocument
     *
     * @return string
     */
    public function getIfpCodeDocument()
    {
        return $this->ifpCodeDocument;
    }

    /**
     * Set ifpCodeClient
     *
     * @param string $ifpCodeClient
     *
     * @return IfpIndexfichePaperless
     */
    public function setIfpCodeClient($ifpCodeClient)
    {
        $this->ifpCodeClient = $ifpCodeClient;

        return $this;
    }

    /**
     * Get ifpCodeClient
     *
     * @return string
     */
    public function getIfpCodeClient()
    {
        return $this->ifpCodeClient;
    }
}
