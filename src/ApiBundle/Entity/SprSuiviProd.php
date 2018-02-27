<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelOperationType;

/**
 * SprSuiviProd
 *
 * @ORM\Table(
 *      name="spr_suivi_prod", indexes={
 *          @ORM\Index(name="spr_fichier_dat", columns={"spr_fichier_dat"}),
 *          @ORM\Index(name="spr_numero_bdl", columns={"spr_numero_bdl"}),
 *          @ORM\Index(name="spr_chrono_odin", columns={"spr_chrono_odin"}),
 *          @ORM\Index(name="spr_max_enreg_avant", columns={"spr_max_enreg_avant"}),
 *          @ORM\Index(name="spr_nombre_enregs_avant", columns={"spr_nombre_enregs_avant"}),
 *          @ORM\Index(name="spr_max_enreg_apres", columns={"spr_max_enreg_apres"}),
 *          @ORM\Index(name="spr_nombre_enregs_apres", columns={"spr_nombre_enregs_apres"}),
 *          @ORM\Index(name="spr_nombre_dossiers", columns={"spr_nombre_dossiers"}),
 *          @ORM\Index(name="spr_nombre_pages", columns={"spr_nombre_pages"}),
 *          @ORM\Index(name="spr_created_at", columns={"spr_created_at"}),
 *          @ORM\Index(name="spr_updated_at", columns={"spr_updated_at"}),
 *          @ORM\Index(name="spr_lot_index", columns={"spr_lot_index", "spr_lot_production"})
 *      }
 * )
 * @ORM\Entity
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class SprSuiviProd
{
    const DEFAULT_SPR_CHRONO_ODIN = 0;
    const DEFAULT_SPR_CHRONO_CLIENT = 0;
    const DEFAULT_SPR_MAX_ENREG_AVANT = 0;
    const DEFAULT_SPR_NOMBRE_ENREGS_AVANT = 0;
    const DEFAULT_SPR_MAX_ENREG_APRES = 0;
    const DEFAULT_SPR_NOMBRE_ENREGS_APRES = 0;
    const DEFAULT_SPR_NOMBRE_PAGES_HEBERGEES_AVANT = 0;
    const DEFAULT_SPR_NOMBRE_PAGES_HEBERGEES_APRES = 0;
    const DEFAULT_SPR_NOMBRE_DOSSIERS = 0;
    const DEFAULT_SPR_NOMBRE_PAGES = 0;
    const DEFAULT_SPR_NOMBRE_PAGES_VIDES = 0;
    const DEFAULT_SPR_TEMPS_PDF = 0;
    const DEFAULT_SPR_TEMPS_INDEXATION = 0;
    const DEFAULT_SPR_TEMPS_INJECTION = 0;
    const DEFAULT_SPR_TEMPS_AUTRE = 0;
    const DEFAULT_SPR_COMPTEUR_DOSSIERS_THEORIQUE = 0;
    const DEFAULT_SPR_COMPTEUR_PAGES_THEORIQUE = 0;
    const DEFAULT_SPR_LOT_PRODUCTION = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sprId;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_fichier_dat", type="string", length=50, nullable=true)
     */
    private $sprFichierDat;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_numero_bdl", type="string", length=20, nullable=false)
     */
    private $sprNumeroBdl;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_chrono_odin", type="integer", nullable=false)
     */
    private $sprChronoOdin = self::DEFAULT_SPR_CHRONO_ODIN;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_banniere_debut", type="string", length=20, nullable=true)
     */
    private $sprBanniereDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_banniere_fin", type="string", length=20, nullable=true)
     */
    private $sprBanniereFin;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_code_client", type="string", length=4, nullable=true)
     */
    private $sprCodeClient;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_code_application", type="string", length=4, nullable=true)
     */
    private $sprCodeApplication;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_type_operation", type="string", length=20, nullable=false)
     */
    private $sprTypeOperation = EnumLabelOperationType::INJECTION_OPERATION;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_chrono_client", type="integer", nullable=false)
     */
    private $sprChronoClient = self::DEFAULT_SPR_CHRONO_CLIENT;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_spool", type="string", length=20, nullable=true)
     */
    private $sprSpool;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_fichier_log_empreinte", type="string", length=255, nullable=true)
     */
    private $sprFichierLogEmpreinte;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_max_enreg_avant", type="integer", nullable=false)
     */
    private $sprMaxEnregAvant = self::DEFAULT_SPR_MAX_ENREG_AVANT;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_nombre_enregs_avant", type="integer", nullable=false)
     */
    private $sprNombreEnregsAvant = self::DEFAULT_SPR_NOMBRE_ENREGS_AVANT;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_max_enreg_apres", type="integer", nullable=false)
     */
    private $sprMaxEnregApres = self::DEFAULT_SPR_MAX_ENREG_APRES;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_nombre_enregs_apres", type="integer", nullable=false)
     */
    private $sprNombreEnregsApres = self::DEFAULT_SPR_NOMBRE_ENREGS_APRES;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_nombre_pages_hebergees_avant", type="integer", nullable=false)
     */
    private $sprNombrePagesHebergeesAvant = self::DEFAULT_SPR_NOMBRE_PAGES_HEBERGEES_AVANT;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_nombre_pages_hebergees_apres", type="integer", nullable=false)
     */
    private $sprNombrePagesHebergeesApres = self::DEFAULT_SPR_NOMBRE_PAGES_HEBERGEES_APRES;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_nombre_dossiers", type="integer", nullable=false)
     */
    private $sprNombreDossiers = self::DEFAULT_SPR_NOMBRE_DOSSIERS;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_nombre_pages", type="integer", nullable=false)
     */
    private $sprNombrePages = self::DEFAULT_SPR_NOMBRE_PAGES;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_nombre_pages_vides", type="integer", nullable=false)
     */
    private $sprNombrePagesVides = self::DEFAULT_SPR_NOMBRE_PAGES_VIDES;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_premier_index", type="string", length=50, nullable=false)
     */
    private $sprPremierIndex;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_dernier_index", type="string", length=50, nullable=false)
     */
    private $sprDernierIndex;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_temps_pdf", type="integer", nullable=false)
     */
    private $sprTempsPdf = self::DEFAULT_SPR_TEMPS_PDF;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_temps_indexation", type="integer", nullable=false)
     */
    private $sprTempsIndexation = self::DEFAULT_SPR_TEMPS_INDEXATION;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_temps_injection", type="integer", nullable=false)
     */
    private $sprTempsInjection = self::DEFAULT_SPR_TEMPS_INJECTION;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_temps_autre", type="integer", nullable=false)
     */
    private $sprTempsAutre = self::DEFAULT_SPR_TEMPS_AUTRE;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_compteur_dossiers_theorique", type="integer", nullable=false)
     */
    private $sprCompteurDossiersTheorique = self::DEFAULT_SPR_COMPTEUR_DOSSIERS_THEORIQUE;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_compteur_pages_theorique", type="integer", nullable=false)
     */
    private $sprCompteurPagesTheorique = self::DEFAULT_SPR_COMPTEUR_PAGES_THEORIQUE;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_table_indexfiche", type="string", length=50, nullable=true)
     */
    private $sprTableIndexfiche;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_audit", type="text", length=65535, nullable=false)
     */
    private $sprAudit;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_lot_index", type="string", length=100, nullable=true)
     */
    private $sprLotIndex;

    /**
     * @var integer
     *
     * @ORM\Column(name="spr_lot_production", type="integer", nullable=false)
     */
    private $sprLotProduction = self::DEFAULT_SPR_LOT_PRODUCTION;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_code_client_appelant", type="string", length=10, nullable=true)
     */
    private $sprCodeClientAppelant;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_code_application_appelant", type="string", length=10, nullable=true)
     */
    private $sprCodeApplicationAppelant;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_numerobdl_appelant", type="string", length=20, nullable=false)
     */
    private $sprNumerobdlAppelant;

    /**
     * @var string
     *
     * @ORM\Column(name="spr_information_client", type="text", length=65535, nullable=false)
     */
    private $sprInformationClient;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="spr_created_at", type="datetime", nullable=true)
     */
    private $sprCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="spr_updated_at", type="datetime", nullable=true)
     */
    private $sprUpdatedAt;


    /**
     * Get sprId
     *
     * @return integer
     */
    public function getSprId()
    {
        return $this->sprId;
    }

    /**
     * Set sprFichierDat
     *
     * @param string $sprFichierDat
     *
     * @return SprSuiviProd
     */
    public function setSprFichierDat($sprFichierDat)
    {
        $this->sprFichierDat = $sprFichierDat;

        return $this;
    }

    /**
     * Get sprFichierDat
     *
     * @return string
     */
    public function getSprFichierDat()
    {
        return $this->sprFichierDat;
    }

    /**
     * Set sprNumeroBdl
     *
     * @param string $sprNumeroBdl
     *
     * @return SprSuiviProd
     */
    public function setSprNumeroBdl($sprNumeroBdl)
    {
        $this->sprNumeroBdl = $sprNumeroBdl;

        return $this;
    }

    /**
     * Get sprNumeroBdl
     *
     * @return string
     */
    public function getSprNumeroBdl()
    {
        return $this->sprNumeroBdl;
    }

    /**
     * Set sprChronoOdin
     *
     * @param integer $sprChronoOdin
     *
     * @return SprSuiviProd
     */
    public function setSprChronoOdin($sprChronoOdin)
    {
        $this->sprChronoOdin = $sprChronoOdin;

        return $this;
    }

    /**
     * Get sprChronoOdin
     *
     * @return integer
     */
    public function getSprChronoOdin()
    {
        return $this->sprChronoOdin;
    }

    /**
     * Set sprBanniereDebut
     *
     * @param string $sprBanniereDebut
     *
     * @return SprSuiviProd
     */
    public function setSprBanniereDebut($sprBanniereDebut)
    {
        $this->sprBanniereDebut = $sprBanniereDebut;

        return $this;
    }

    /**
     * Get sprBanniereDebut
     *
     * @return string
     */
    public function getSprBanniereDebut()
    {
        return $this->sprBanniereDebut;
    }

    /**
     * Set sprBanniereFin
     *
     * @param string $sprBanniereFin
     *
     * @return SprSuiviProd
     */
    public function setSprBanniereFin($sprBanniereFin)
    {
        $this->sprBanniereFin = $sprBanniereFin;

        return $this;
    }

    /**
     * Get sprBanniereFin
     *
     * @return string
     */
    public function getSprBanniereFin()
    {
        return $this->sprBanniereFin;
    }

    /**
     * Set sprCodeClient
     *
     * @param string $sprCodeClient
     *
     * @return SprSuiviProd
     */
    public function setSprCodeClient($sprCodeClient)
    {
        $this->sprCodeClient = $sprCodeClient;

        return $this;
    }

    /**
     * Get sprCodeClient
     *
     * @return string
     */
    public function getSprCodeClient()
    {
        return $this->sprCodeClient;
    }

    /**
     * Set sprCodeApplication
     *
     * @param string $sprCodeApplication
     *
     * @return SprSuiviProd
     */
    public function setSprCodeApplication($sprCodeApplication)
    {
        $this->sprCodeApplication = $sprCodeApplication;

        return $this;
    }

    /**
     * Get sprCodeApplication
     *
     * @return string
     */
    public function getSprCodeApplication()
    {
        return $this->sprCodeApplication;
    }

    /**
     * Set sprTypeOperation
     *
     * @param string $sprTypeOperation
     *
     * @return SprSuiviProd
     */
    public function setSprTypeOperation($sprTypeOperation)
    {
        $this->sprTypeOperation = $sprTypeOperation;

        return $this;
    }

    /**
     * Get sprTypeOperation
     *
     * @return string
     */
    public function getSprTypeOperation()
    {
        return $this->sprTypeOperation;
    }

    /**
     * Set sprChronoClient
     *
     * @param integer $sprChronoClient
     *
     * @return SprSuiviProd
     */
    public function setSprChronoClient($sprChronoClient)
    {
        $this->sprChronoClient = $sprChronoClient;

        return $this;
    }

    /**
     * Get sprChronoClient
     *
     * @return integer
     */
    public function getSprChronoClient()
    {
        return $this->sprChronoClient;
    }

    /**
     * Set sprSpool
     *
     * @param string $sprSpool
     *
     * @return SprSuiviProd
     */
    public function setSprSpool($sprSpool)
    {
        $this->sprSpool = $sprSpool;

        return $this;
    }

    /**
     * Get sprSpool
     *
     * @return string
     */
    public function getSprSpool()
    {
        return $this->sprSpool;
    }

    /**
     * Set sprFichierLogEmpreinte
     *
     * @param string $sprFichierLogEmpreinte
     *
     * @return SprSuiviProd
     */
    public function setSprFichierLogEmpreinte($sprFichierLogEmpreinte)
    {
        $this->sprFichierLogEmpreinte = $sprFichierLogEmpreinte;

        return $this;
    }

    /**
     * Get sprFichierLogEmpreinte
     *
     * @return string
     */
    public function getSprFichierLogEmpreinte()
    {
        return $this->sprFichierLogEmpreinte;
    }

    /**
     * Set sprMaxEnregAvant
     *
     * @param integer $sprMaxEnregAvant
     *
     * @return SprSuiviProd
     */
    public function setSprMaxEnregAvant($sprMaxEnregAvant)
    {
        $this->sprMaxEnregAvant = $sprMaxEnregAvant;

        return $this;
    }

    /**
     * Get sprMaxEnregAvant
     *
     * @return integer
     */
    public function getSprMaxEnregAvant()
    {
        return $this->sprMaxEnregAvant;
    }

    /**
     * Set sprNombreEnregsAvant
     *
     * @param integer $sprNombreEnregsAvant
     *
     * @return SprSuiviProd
     */
    public function setSprNombreEnregsAvant($sprNombreEnregsAvant)
    {
        $this->sprNombreEnregsAvant = $sprNombreEnregsAvant;

        return $this;
    }

    /**
     * Get sprNombreEnregsAvant
     *
     * @return integer
     */
    public function getSprNombreEnregsAvant()
    {
        return $this->sprNombreEnregsAvant;
    }

    /**
     * Set sprMaxEnregApres
     *
     * @param integer $sprMaxEnregApres
     *
     * @return SprSuiviProd
     */
    public function setSprMaxEnregApres($sprMaxEnregApres)
    {
        $this->sprMaxEnregApres = $sprMaxEnregApres;

        return $this;
    }

    /**
     * Get sprMaxEnregApres
     *
     * @return integer
     */
    public function getSprMaxEnregApres()
    {
        return $this->sprMaxEnregApres;
    }

    /**
     * Set sprNombreEnregsApres
     *
     * @param integer $sprNombreEnregsApres
     *
     * @return SprSuiviProd
     */
    public function setSprNombreEnregsApres($sprNombreEnregsApres)
    {
        $this->sprNombreEnregsApres = $sprNombreEnregsApres;

        return $this;
    }

    /**
     * Get sprNombreEnregsApres
     *
     * @return integer
     */
    public function getSprNombreEnregsApres()
    {
        return $this->sprNombreEnregsApres;
    }

    /**
     * Set sprNombrePagesHebergeesAvant
     *
     * @param integer $sprNombrePagesHebergeesAvant
     *
     * @return SprSuiviProd
     */
    public function setSprNombrePagesHebergeesAvant($sprNombrePagesHebergeesAvant)
    {
        $this->sprNombrePagesHebergeesAvant = $sprNombrePagesHebergeesAvant;

        return $this;
    }

    /**
     * Get sprNombrePagesHebergeesAvant
     *
     * @return integer
     */
    public function getSprNombrePagesHebergeesAvant()
    {
        return $this->sprNombrePagesHebergeesAvant;
    }

    /**
     * Set sprNombrePagesHebergeesApres
     *
     * @param integer $sprNombrePagesHebergeesApres
     *
     * @return SprSuiviProd
     */
    public function setSprNombrePagesHebergeesApres($sprNombrePagesHebergeesApres)
    {
        $this->sprNombrePagesHebergeesApres = $sprNombrePagesHebergeesApres;

        return $this;
    }

    /**
     * Get sprNombrePagesHebergeesApres
     *
     * @return integer
     */
    public function getSprNombrePagesHebergeesApres()
    {
        return $this->sprNombrePagesHebergeesApres;
    }

    /**
     * Set sprNombreDossiers
     *
     * @param integer $sprNombreDossiers
     *
     * @return SprSuiviProd
     */
    public function setSprNombreDossiers($sprNombreDossiers)
    {
        $this->sprNombreDossiers = $sprNombreDossiers;

        return $this;
    }

    /**
     * Get sprNombreDossiers
     *
     * @return integer
     */
    public function getSprNombreDossiers()
    {
        return $this->sprNombreDossiers;
    }

    /**
     * Set sprNombrePages
     *
     * @param integer $sprNombrePages
     *
     * @return SprSuiviProd
     */
    public function setSprNombrePages($sprNombrePages)
    {
        $this->sprNombrePages = $sprNombrePages;

        return $this;
    }

    /**
     * Get sprNombrePages
     *
     * @return integer
     */
    public function getSprNombrePages()
    {
        return $this->sprNombrePages;
    }

    /**
     * Set sprNombrePagesVides
     *
     * @param integer $sprNombrePagesVides
     *
     * @return SprSuiviProd
     */
    public function setSprNombrePagesVides($sprNombrePagesVides)
    {
        $this->sprNombrePagesVides = $sprNombrePagesVides;

        return $this;
    }

    /**
     * Get sprNombrePagesVides
     *
     * @return integer
     */
    public function getSprNombrePagesVides()
    {
        return $this->sprNombrePagesVides;
    }

    /**
     * Set sprPremierIndex
     *
     * @param string $sprPremierIndex
     *
     * @return SprSuiviProd
     */
    public function setSprPremierIndex($sprPremierIndex)
    {
        $this->sprPremierIndex = $sprPremierIndex;

        return $this;
    }

    /**
     * Get sprPremierIndex
     *
     * @return string
     */
    public function getSprPremierIndex()
    {
        return $this->sprPremierIndex;
    }

    /**
     * Set sprDernierIndex
     *
     * @param string $sprDernierIndex
     *
     * @return SprSuiviProd
     */
    public function setSprDernierIndex($sprDernierIndex)
    {
        $this->sprDernierIndex = $sprDernierIndex;

        return $this;
    }

    /**
     * Get sprDernierIndex
     *
     * @return string
     */
    public function getSprDernierIndex()
    {
        return $this->sprDernierIndex;
    }

    /**
     * Set sprTempsPdf
     *
     * @param integer $sprTempsPdf
     *
     * @return SprSuiviProd
     */
    public function setSprTempsPdf($sprTempsPdf)
    {
        $this->sprTempsPdf = $sprTempsPdf;

        return $this;
    }

    /**
     * Get sprTempsPdf
     *
     * @return integer
     */
    public function getSprTempsPdf()
    {
        return $this->sprTempsPdf;
    }

    /**
     * Set sprTempsIndexation
     *
     * @param integer $sprTempsIndexation
     *
     * @return SprSuiviProd
     */
    public function setSprTempsIndexation($sprTempsIndexation)
    {
        $this->sprTempsIndexation = $sprTempsIndexation;

        return $this;
    }

    /**
     * Get sprTempsIndexation
     *
     * @return integer
     */
    public function getSprTempsIndexation()
    {
        return $this->sprTempsIndexation;
    }

    /**
     * Set sprTempsInjection
     *
     * @param integer $sprTempsInjection
     *
     * @return SprSuiviProd
     */
    public function setSprTempsInjection($sprTempsInjection)
    {
        $this->sprTempsInjection = $sprTempsInjection;

        return $this;
    }

    /**
     * Get sprTempsInjection
     *
     * @return integer
     */
    public function getSprTempsInjection()
    {
        return $this->sprTempsInjection;
    }

    /**
     * Set sprTempsAutre
     *
     * @param integer $sprTempsAutre
     *
     * @return SprSuiviProd
     */
    public function setSprTempsAutre($sprTempsAutre)
    {
        $this->sprTempsAutre = $sprTempsAutre;

        return $this;
    }

    /**
     * Get sprTempsAutre
     *
     * @return integer
     */
    public function getSprTempsAutre()
    {
        return $this->sprTempsAutre;
    }

    /**
     * Set sprCompteurDossiersTheorique
     *
     * @param integer $sprCompteurDossiersTheorique
     *
     * @return SprSuiviProd
     */
    public function setSprCompteurDossiersTheorique($sprCompteurDossiersTheorique)
    {
        $this->sprCompteurDossiersTheorique = $sprCompteurDossiersTheorique;

        return $this;
    }

    /**
     * Get sprCompteurDossiersTheorique
     *
     * @return integer
     */
    public function getSprCompteurDossiersTheorique()
    {
        return $this->sprCompteurDossiersTheorique;
    }

    /**
     * Set sprCompteurPagesTheorique
     *
     * @param integer $sprCompteurPagesTheorique
     *
     * @return SprSuiviProd
     */
    public function setSprCompteurPagesTheorique($sprCompteurPagesTheorique)
    {
        $this->sprCompteurPagesTheorique = $sprCompteurPagesTheorique;

        return $this;
    }

    /**
     * Get sprCompteurPagesTheorique
     *
     * @return integer
     */
    public function getSprCompteurPagesTheorique()
    {
        return $this->sprCompteurPagesTheorique;
    }

    /**
     * Set sprTableIndexfiche
     *
     * @param string $sprTableIndexfiche
     *
     * @return SprSuiviProd
     */
    public function setSprTableIndexfiche($sprTableIndexfiche)
    {
        $this->sprTableIndexfiche = $sprTableIndexfiche;

        return $this;
    }

    /**
     * Get sprTableIndexfiche
     *
     * @return string
     */
    public function getSprTableIndexfiche()
    {
        return $this->sprTableIndexfiche;
    }

    /**
     * Set sprAudit
     *
     * @param string $sprAudit
     *
     * @return SprSuiviProd
     */
    public function setSprAudit($sprAudit)
    {
        $this->sprAudit = $sprAudit;

        return $this;
    }

    /**
     * Get sprAudit
     *
     * @return string
     */
    public function getSprAudit()
    {
        return $this->sprAudit;
    }

    /**
     * Set sprLotIndex
     *
     * @param string $sprLotIndex
     *
     * @return SprSuiviProd
     */
    public function setSprLotIndex($sprLotIndex)
    {
        $this->sprLotIndex = $sprLotIndex;

        return $this;
    }

    /**
     * Get sprLotIndex
     *
     * @return string
     */
    public function getSprLotIndex()
    {
        return $this->sprLotIndex;
    }

    /**
     * Set sprLotProduction
     *
     * @param integer $sprLotProduction
     *
     * @return SprSuiviProd
     */
    public function setSprLotProduction($sprLotProduction)
    {
        $this->sprLotProduction = $sprLotProduction;

        return $this;
    }

    /**
     * Get sprLotProduction
     *
     * @return integer
     */
    public function getSprLotProduction()
    {
        return $this->sprLotProduction;
    }

    /**
     * Set sprCodeClientAppelant
     *
     * @param string $sprCodeClientAppelant
     *
     * @return SprSuiviProd
     */
    public function setSprCodeClientAppelant($sprCodeClientAppelant)
    {
        $this->sprCodeClientAppelant = $sprCodeClientAppelant;

        return $this;
    }

    /**
     * Get sprCodeClientAppelant
     *
     * @return string
     */
    public function getSprCodeClientAppelant()
    {
        return $this->sprCodeClientAppelant;
    }

    /**
     * Set sprCodeApplicationAppelant
     *
     * @param string $sprCodeApplicationAppelant
     *
     * @return SprSuiviProd
     */
    public function setSprCodeApplicationAppelant($sprCodeApplicationAppelant)
    {
        $this->sprCodeApplicationAppelant = $sprCodeApplicationAppelant;

        return $this;
    }

    /**
     * Get sprCodeApplicationAppelant
     *
     * @return string
     */
    public function getSprCodeApplicationAppelant()
    {
        return $this->sprCodeApplicationAppelant;
    }

    /**
     * Set sprNumerobdlAppelant
     *
     * @param string $sprNumerobdlAppelant
     *
     * @return SprSuiviProd
     */
    public function setSprNumerobdlAppelant($sprNumerobdlAppelant)
    {
        $this->sprNumerobdlAppelant = $sprNumerobdlAppelant;

        return $this;
    }

    /**
     * Get sprNumerobdlAppelant
     *
     * @return string
     */
    public function getSprNumerobdlAppelant()
    {
        return $this->sprNumerobdlAppelant;
    }

    /**
     * Set sprInformationClient
     *
     * @param string $sprInformationClient
     *
     * @return SprSuiviProd
     */
    public function setSprInformationClient($sprInformationClient)
    {
        $this->sprInformationClient = $sprInformationClient;

        return $this;
    }

    /**
     * Get sprInformationClient
     *
     * @return string
     */
    public function getSprInformationClient()
    {
        return $this->sprInformationClient;
    }

    /**
     * Set sprCreatedAt
     *
     * @param \DateTime $sprCreatedAt
     *
     * @return SprSuiviProd
     */
    public function setSprCreatedAt($sprCreatedAt)
    {
        $this->sprCreatedAt = $sprCreatedAt;

        return $this;
    }

    /**
     * Get sprCreatedAt
     *
     * @return \DateTime
     */
    public function getSprCreatedAt()
    {
        return $this->sprCreatedAt;
    }

    /**
     * Set sprUpdatedAt
     *
     * @param \DateTime $sprUpdatedAt
     *
     * @return SprSuiviProd
     */
    public function setSprUpdatedAt($sprUpdatedAt)
    {
        $this->sprUpdatedAt = $sprUpdatedAt;

        return $this;
    }

    /**
     * Get sprUpdatedAt
     *
     * @return \DateTime
     */
    public function getSprUpdatedAt()
    {
        return $this->sprUpdatedAt;
    }
}
