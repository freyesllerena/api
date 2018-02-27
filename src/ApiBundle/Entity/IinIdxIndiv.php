<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IinIdxIndiv
 *
 * @ORM\Table(
 *     name="iin_idx_indiv", uniqueConstraints={
 *      @ORM\UniqueConstraint(
 *          name="iin_id_num_matricule", columns={
 *              "iin_id_num_matricule", "iin_id_code_client"
 *          }
 *      )
 *     },
 *     indexes={
 *      @ORM\Index(name="iin_id_nom_salarie", columns={"iin_id_nom_salarie"}),
 *      @ORM\Index(name="iin_id_prenom_salarie", columns={"iin_id_prenom_salarie"}),
 *      @ORM\Index(name="iin_id_code_categ_socio_prof", columns={"iin_id_code_categ_socio_prof"}),
 *      @ORM\Index(name="iin_id_code_categ_professionnelle", columns={"iin_id_code_categ_professionnelle"}),
 *      @ORM\Index(name="iin_id_code_etablissement", columns={"iin_id_code_etablissement"}),
 *      @ORM\Index(name="iin_id_nom_jeune_fille_salarie", columns={"iin_id_nom_jeune_fille_salarie"}),
 *      @ORM\Index(name="iin_id_date_entree", columns={"iin_id_date_entree"}),
 *      @ORM\Index(name="iin_id_date_sortie", columns={"iin_id_date_sortie"}),
 *      @ORM\Index(name="iin_id_num_nir", columns={"iin_id_num_nir"}),
 *      @ORM\Index(name="iin_id_lib_etablissement", columns={"iin_id_lib_etablissement"}),
 *      @ORM\Index(name="iin_id_nom_societe", columns={"iin_id_nom_societe"}),
 *      @ORM\Index(name="iin_id_date_naissance", columns={"iin_id_date_naissance"}),
 *      @ORM\Index(name="iin_id_num_siret", columns={"iin_id_num_siret"}),
 *      @ORM\Index(name="iin_id_num_matricule_rh", columns={"iin_id_num_matricule_rh"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\IinIdxIndivRepository")
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class IinIdxIndiv
{
    const IIN_PREFIX = 'iin';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="iin_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iinId;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_code_client", type="string", length=20, nullable=false)
     */
    private $iinCodeClient;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_code_societe", type="string", length=255, nullable=false)
     */
    private $iinIdCodeSociete;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_code_jalon", type="string", length=5, nullable=false)
     */
    private $iinIdCodeJalon;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_code_etablissement", type="string", length=10, nullable=false)
     */
    private $iinIdCodeEtablissement;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_lib_etablissement", type="string", length=255, nullable=false)
     */
    private $iinIdLibEtablissement;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_nom_societe", type="string", length=255, nullable=false)
     */
    private $iinIdNomSociete;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_nom_client", type="string", length=255, nullable=false)
     */
    private $iinIdNomClient;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_type_paie", type="string", length=5, nullable=true)
     */
    private $iinIdTypePaie;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_periode_paie", type="string", length=10, nullable=false)
     */
    private $iinIdPeriodePaie;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_nom_salarie", type="string", length=255, nullable=false)
     */
    private $iinIdNomSalarie;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_prenom_salarie", type="string", length=255, nullable=false)
     */
    private $iinIdPrenomSalarie;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_nom_jeune_fille_salarie", type="string", length=255, nullable=true)
     */
    private $iinIdNomJeuneFilleSalarie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="iin_id_date_entree", type="datetime", nullable=true)
     */
    private $iinIdDateEntree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="iin_id_date_sortie", type="datetime", nullable=true)
     */
    private $iinIdDateSortie;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_num_nir", type="string", length=30, nullable=false)
     */
    private $iinIdNumNir;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_num_matricule", type="string", length=10, nullable=false)
     */
    private $iinNumMatricule;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_fichier_index", type="string", length=50, nullable=false)
     */
    private $iinFichierIndex;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_code_categ_professionnelle", type="string", length=255, nullable=false)
     */
    private $iinIdCodeCategProfessionnelle;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_code_categ_socio_prof", type="string", length=255, nullable=false)
     */
    private $iinIdCodeCategSocioProf;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_type_contrat", type="string", length=255, nullable=false)
     */
    private $iinIdTypeContrat;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_affectation1", type="string", length=255, nullable=false)
     */
    private $iinIdAffectation1;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_affectation2", type="string", length=255, nullable=false)
     */
    private $iinIdAffectation2;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_affectation3", type="string", length=255, nullable=false)
     */
    private $iinIdAffectation3;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_num_siren", type="string", length=255, nullable=false)
     */
    private $iinIdNumSiren;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_num_siret", type="string", length=255, nullable=false)
     */
    private $iinIdNumSiret;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="iin_id_date_naissance", type="datetime", nullable=false)
     */
    private $iinIdDateNaissanceSalarie;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_libre1", type="string", length=255, nullable=true)
     */
    private $iinIdLibre1;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_libre2", type="string", length=255, nullable=true)
     */
    private $iinIdLibre2;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_num_matricule_groupe", type="string", length=255, nullable=true)
     */
    private $iinIdNumMatriculeGroupe;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_num_matricule_rh", type="string", length=255, nullable=false)
     */
    private $iinIdNumMatriculeRh;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_code_activite", type="string", length=255, nullable=false)
     */
    private $iinIdCodeActivite;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_code_chrono", type="string", length=20, nullable=true)
     */
    private $iinIdCodeChrono;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_1", type="string", length=8, nullable=true)
     */
    private $iinIdDate1;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_2", type="string", length=8, nullable=true)
     */
    private $iinIdDate2;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_3", type="string", length=8, nullable=true)
     */
    private $iinIdDate3;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_4", type="string", length=8, nullable=true)
     */
    private $iinIdDate4;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_5", type="string", length=8, nullable=true)
     */
    private $iinIdDate5;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_6", type="string", length=8, nullable=true)
     */
    private $iinIdDate6;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_7", type="string", length=8, nullable=true)
     */
    private $iinIdDate7;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_8", type="string", length=8, nullable=true)
     */
    private $iinIdDate8;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_adp_1", type="string", length=8, nullable=true)
     */
    private $iinIdDateAdp1;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_date_adp_2", type="string", length=8, nullable=true)
     */
    private $iinIdDateAdp2;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_1", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum1;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_2", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum2;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_3", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum3;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_4", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum4;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_5", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum5;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_6", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum6;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_7", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum7;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_8", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum8;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_9", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum9;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_10", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum10;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_11", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum11;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_12", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum12;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_13", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum13;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_14", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum14;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_15", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum15;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_16", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum16;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_17", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum17;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_18", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanum18;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_adp_1", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanumAdp1;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_alphanum_adp_2", type="string", length=50, nullable=true)
     */
    private $iinIdAlphanumAdp2;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_1", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum1;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_2", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum2;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_3", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum3;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_4", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum4;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_5", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum5;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_6", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum6;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_7", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum7;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_8", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum8;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_9", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum9;

    /**
     * @var float
     *
     * @ORM\Column(name="iin_id_num_10", type="float", precision=10, scale=0, nullable=true)
     */
    private $iinIdNum10;

    /**
     * @var string
     *
     * @ORM\Column(name="iin_id_num_ordre", type="string", length=255, nullable=true)
     */
    private $iinIdNumOrdre;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="iin_created_at", type="datetime", nullable=true)
     */
    private $iinCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="iin_updated_at", type="datetime", nullable=true)
     */
    private $iinUpdatedAt;


    /**
     * Get iinId
     *
     * @return integer
     */
    public function getIinId()
    {
        return $this->iinId;
    }

    /**
     * Set iinCodeClient
     *
     * @param string $iinCodeClient
     *
     * @return IinIdxIndiv
     */
    public function setIinCodeClient($iinCodeClient)
    {
        $this->iinCodeClient = $iinCodeClient;

        return $this;
    }

    /**
     * Get iinCodeClient
     *
     * @return string
     */
    public function getIinCodeClient()
    {
        return $this->iinCodeClient;
    }

    /**
     * Set iinIdCodeSociete
     *
     * @param string $iinIdCodeSociete
     *
     * @return IinIdxIndiv
     */
    public function setIinIdCodeSociete($iinIdCodeSociete)
    {
        $this->iinIdCodeSociete = $iinIdCodeSociete;

        return $this;
    }

    /**
     * Get iinIdCodeSociete
     *
     * @return string
     */
    public function getIinIdCodeSociete()
    {
        return $this->iinIdCodeSociete;
    }

    /**
     * Set iinIdCodeJalon
     *
     * @param string $iinIdCodeJalon
     *
     * @return IinIdxIndiv
     */
    public function setIinIdCodeJalon($iinIdCodeJalon)
    {
        $this->iinIdCodeJalon = $iinIdCodeJalon;

        return $this;
    }

    /**
     * Get iinIdCodeJalon
     *
     * @return string
     */
    public function getIinIdCodeJalon()
    {
        return $this->iinIdCodeJalon;
    }

    /**
     * Set iinIdCodeEtablissement
     *
     * @param string $iinIdCodeEtablissement
     *
     * @return IinIdxIndiv
     */
    public function setIinIdCodeEtablissement($iinIdCodeEtablissement)
    {
        $this->iinIdCodeEtablissement = $iinIdCodeEtablissement;

        return $this;
    }

    /**
     * Get iinIdCodeEtablissement
     *
     * @return string
     */
    public function getIinIdCodeEtablissement()
    {
        return $this->iinIdCodeEtablissement;
    }

    /**
     * Set iinIdLibEtablissement
     *
     * @param string $iinIdLibEtablissement
     *
     * @return IinIdxIndiv
     */
    public function setIinIdLibEtablissement($iinIdLibEtablissement)
    {
        $this->iinIdLibEtablissement = $iinIdLibEtablissement;

        return $this;
    }

    /**
     * Get iinIdLibEtablissement
     *
     * @return string
     */
    public function getIinIdLibEtablissement()
    {
        return $this->iinIdLibEtablissement;
    }

    /**
     * Set iinIdNomSociete
     *
     * @param string $iinIdNomSociete
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNomSociete($iinIdNomSociete)
    {
        $this->iinIdNomSociete = $iinIdNomSociete;

        return $this;
    }

    /**
     * Get iinIdNomSociete
     *
     * @return string
     */
    public function getIinIdNomSociete()
    {
        return $this->iinIdNomSociete;
    }

    /**
     * Set iinIdNomClient
     *
     * @param string $iinIdNomClient
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNomClient($iinIdNomClient)
    {
        $this->iinIdNomClient = $iinIdNomClient;

        return $this;
    }

    /**
     * Get iinIdNomClient
     *
     * @return string
     */
    public function getIinIdNomClient()
    {
        return $this->iinIdNomClient;
    }

    /**
     * Set iinIdTypePaie
     *
     * @param string $iinIdTypePaie
     *
     * @return IinIdxIndiv
     */
    public function setIinIdTypePaie($iinIdTypePaie)
    {
        $this->iinIdTypePaie = $iinIdTypePaie;

        return $this;
    }

    /**
     * Get iinIdTypePaie
     *
     * @return string
     */
    public function getIinIdTypePaie()
    {
        return $this->iinIdTypePaie;
    }

    /**
     * Set iinIdPeriodePaie
     *
     * @param string $iinIdPeriodePaie
     *
     * @return IinIdxIndiv
     */
    public function setIinIdPeriodePaie($iinIdPeriodePaie)
    {
        $this->iinIdPeriodePaie = $iinIdPeriodePaie;

        return $this;
    }

    /**
     * Get iinIdPeriodePaie
     *
     * @return string
     */
    public function getIinIdPeriodePaie()
    {
        return $this->iinIdPeriodePaie;
    }

    /**
     * Set iinIdNomSalarie
     *
     * @param string $iinIdNomSalarie
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNomSalarie($iinIdNomSalarie)
    {
        $this->iinIdNomSalarie = $iinIdNomSalarie;

        return $this;
    }

    /**
     * Get iinIdNomSalarie
     *
     * @return string
     */
    public function getIinIdNomSalarie()
    {
        return $this->iinIdNomSalarie;
    }

    /**
     * Set iinIdPrenomSalarie
     *
     * @param string $iinIdPrenomSalarie
     *
     * @return IinIdxIndiv
     */
    public function setIinIdPrenomSalarie($iinIdPrenomSalarie)
    {
        $this->iinIdPrenomSalarie = $iinIdPrenomSalarie;

        return $this;
    }

    /**
     * Get iinIdPrenomSalarie
     *
     * @return string
     */
    public function getIinIdPrenomSalarie()
    {
        return $this->iinIdPrenomSalarie;
    }

    /**
     * Set iinIdNomJeuneFilleSalarie
     *
     * @param string $iinIdNomJeuneFilleSalarie
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNomJeuneFilleSalarie($iinIdNomJeuneFilleSalarie)
    {
        $this->iinIdNomJeuneFilleSalarie = $iinIdNomJeuneFilleSalarie;

        return $this;
    }

    /**
     * Get iinIdNomJeuneFilleSalarie
     *
     * @return string
     */
    public function getIinIdNomJeuneFilleSalarie()
    {
        return $this->iinIdNomJeuneFilleSalarie;
    }

    /**
     * Set iinIdDateEntree
     *
     * @param \DateTime $iinIdDateEntree
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDateEntree($iinIdDateEntree)
    {
        $this->iinIdDateEntree = is_string($iinIdDateEntree) ? new \DateTime($iinIdDateEntree) : $iinIdDateEntree;

        return $this;
    }

    /**
     * Get iinIdDateEntree
     *
     * @return \DateTime
     */
    public function getIinIdDateEntree()
    {
        return $this->iinIdDateEntree;
    }

    /**
     * Set iinIdDateSortie
     *
     * @param \DateTime $iinIdDateSortie
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDateSortie($iinIdDateSortie)
    {
        $this->iinIdDateSortie = is_string($iinIdDateSortie) ? new \DateTime($iinIdDateSortie) : $iinIdDateSortie;

        return $this;
    }

    /**
     * Get iinIdDateSortie
     *
     * @return \DateTime
     */
    public function getIinIdDateSortie()
    {
        return $this->iinIdDateSortie;
    }

    /**
     * Set iinIdNumNir
     *
     * @param string $iinIdNumNir
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNumNir($iinIdNumNir)
    {
        $this->iinIdNumNir = $iinIdNumNir;

        return $this;
    }

    /**
     * Get iinIdNumNir
     *
     * @return string
     */
    public function getIinIdNumNir()
    {
        return $this->iinIdNumNir;
    }

    /**
     * Set iinNumMatricule
     *
     * @param string $iinNumMatricule
     *
     * @return IinIdxIndiv
     */
    public function setIinNumMatricule($iinNumMatricule)
    {
        $this->iinNumMatricule = $iinNumMatricule;

        return $this;
    }

    /**
     * Get iinNumMatricule
     *
     * @return string
     */
    public function getIinNumMatricule()
    {
        return $this->iinNumMatricule;
    }

    /**
     * Set iinFichierIndex
     *
     * @param string $iinFichierIndex
     *
     * @return IinIdxIndiv
     */
    public function setIinFichierIndex($iinFichierIndex)
    {
        $this->iinFichierIndex = $iinFichierIndex;

        return $this;
    }

    /**
     * Get iinFichierIndex
     *
     * @return string
     */
    public function getIinFichierIndex()
    {
        return $this->iinFichierIndex;
    }

    /**
     * Set iinIdCodeCategProfessionnelle
     *
     * @param string $iinIdCodeCategProfessionnelle
     *
     * @return IinIdxIndiv
     */
    public function setIinIdCodeCategProfessionnelle($iinIdCodeCategProfessionnelle)
    {
        $this->iinIdCodeCategProfessionnelle = $iinIdCodeCategProfessionnelle;

        return $this;
    }

    /**
     * Get iinIdCodeCategProfessionnelle
     *
     * @return string
     */
    public function getIinIdCodeCategProfessionnelle()
    {
        return $this->iinIdCodeCategProfessionnelle;
    }

    /**
     * Set iinIdCodeCategSocioProf
     *
     * @param string $iinIdCodeCategSocioProf
     *
     * @return IinIdxIndiv
     */
    public function setIinIdCodeCategSocioProf($iinIdCodeCategSocioProf)
    {
        $this->iinIdCodeCategSocioProf = $iinIdCodeCategSocioProf;

        return $this;
    }

    /**
     * Get iinIdCodeCategSocioProf
     *
     * @return string
     */
    public function getIinIdCodeCategSocioProf()
    {
        return $this->iinIdCodeCategSocioProf;
    }

    /**
     * Set iinIdTypeContrat
     *
     * @param string $iinIdTypeContrat
     *
     * @return IinIdxIndiv
     */
    public function setIinIdTypeContrat($iinIdTypeContrat)
    {
        $this->iinIdTypeContrat = $iinIdTypeContrat;

        return $this;
    }

    /**
     * Get iinIdTypeContrat
     *
     * @return string
     */
    public function getIinIdTypeContrat()
    {
        return $this->iinIdTypeContrat;
    }

    /**
     * Set iinIdAffectation1
     *
     * @param string $iinIdAffectation1
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAffectation1($iinIdAffectation1)
    {
        $this->iinIdAffectation1 = $iinIdAffectation1;

        return $this;
    }

    /**
     * Get iinIdAffectation1
     *
     * @return string
     */
    public function getIinIdAffectation1()
    {
        return $this->iinIdAffectation1;
    }

    /**
     * Set iinIdAffectation2
     *
     * @param string $iinIdAffectation2
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAffectation2($iinIdAffectation2)
    {
        $this->iinIdAffectation2 = $iinIdAffectation2;

        return $this;
    }

    /**
     * Get iinIdAffectation2
     *
     * @return string
     */
    public function getIinIdAffectation2()
    {
        return $this->iinIdAffectation2;
    }

    /**
     * Set iinIdAffectation3
     *
     * @param string $iinIdAffectation3
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAffectation3($iinIdAffectation3)
    {
        $this->iinIdAffectation3 = $iinIdAffectation3;

        return $this;
    }

    /**
     * Get iinIdAffectation3
     *
     * @return string
     */
    public function getIinIdAffectation3()
    {
        return $this->iinIdAffectation3;
    }

    /**
     * Set iinIdNumSiren
     *
     * @param string $iinIdNumSiren
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNumSiren($iinIdNumSiren)
    {
        $this->iinIdNumSiren = $iinIdNumSiren;

        return $this;
    }

    /**
     * Get iinIdNumSiren
     *
     * @return string
     */
    public function getIinIdNumSiren()
    {
        return $this->iinIdNumSiren;
    }

    /**
     * Set iinIdNumSiret
     *
     * @param string $iinIdNumSiret
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNumSiret($iinIdNumSiret)
    {
        $this->iinIdNumSiret = $iinIdNumSiret;

        return $this;
    }

    /**
     * Get iinIdNumSiret
     *
     * @return string
     */
    public function getIinIdNumSiret()
    {
        return $this->iinIdNumSiret;
    }

    /**
     * Set iinIdDateNaissanceSalarie
     *
     * @param \DateTime $iinIdDateNaissanceSalarie
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDateNaissanceSalarie($iinIdDateNaissanceSalarie)
    {
        $this->iinIdDateNaissanceSalarie = is_string($iinIdDateNaissanceSalarie) ?
            new \DateTime($iinIdDateNaissanceSalarie) :
            $iinIdDateNaissanceSalarie
        ;

        return $this;
    }

    /**
     * Get iinIdDateNaissanceSalarie
     *
     * @return \DateTime
     */
    public function getIinIdDateNaissanceSalarie()
    {
        return $this->iinIdDateNaissanceSalarie;
    }

    /**
     * Set iinIdLibre1
     *
     * @param string $iinIdLibre1
     *
     * @return IinIdxIndiv
     */
    public function setIinIdLibre1($iinIdLibre1)
    {
        $this->iinIdLibre1 = $iinIdLibre1;

        return $this;
    }

    /**
     * Get iinIdLibre1
     *
     * @return string
     */
    public function getIinIdLibre1()
    {
        return $this->iinIdLibre1;
    }

    /**
     * Set iinIdLibre2
     *
     * @param string $iinIdLibre2
     *
     * @return IinIdxIndiv
     */
    public function setIinIdLibre2($iinIdLibre2)
    {
        $this->iinIdLibre2 = $iinIdLibre2;

        return $this;
    }

    /**
     * Get iinIdLibre2
     *
     * @return string
     */
    public function getIinIdLibre2()
    {
        return $this->iinIdLibre2;
    }

    /**
     * Set iinIdNumMatriculeGroupe
     *
     * @param string $iinIdNumMatriculeGroupe
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNumMatriculeGroupe($iinIdNumMatriculeGroupe)
    {
        $this->iinIdNumMatriculeGroupe = $iinIdNumMatriculeGroupe;

        return $this;
    }

    /**
     * Get iinIdNumMatriculeGroupe
     *
     * @return string
     */
    public function getIinIdNumMatriculeGroupe()
    {
        return $this->iinIdNumMatriculeGroupe;
    }

    /**
     * Set iinIdNumMatriculeRh
     *
     * @param string $iinIdNumMatriculeRh
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNumMatriculeRh($iinIdNumMatriculeRh)
    {
        $this->iinIdNumMatriculeRh = $iinIdNumMatriculeRh;

        return $this;
    }

    /**
     * Get iinIdNumMatriculeRh
     *
     * @return string
     */
    public function getIinIdNumMatriculeRh()
    {
        return $this->iinIdNumMatriculeRh;
    }

    /**
     * Set iinIdCodeActivite
     *
     * @param string $iinIdCodeActivite
     *
     * @return IinIdxIndiv
     */
    public function setIinIdCodeActivite($iinIdCodeActivite)
    {
        $this->iinIdCodeActivite = $iinIdCodeActivite;

        return $this;
    }

    /**
     * Get iinIdCodeActivite
     *
     * @return string
     */
    public function getIinIdCodeActivite()
    {
        return $this->iinIdCodeActivite;
    }

    /**
     * Set iinIdCodeChrono
     *
     * @param string $iinIdCodeChrono
     *
     * @return IinIdxIndiv
     */
    public function setIinIdCodeChrono($iinIdCodeChrono)
    {
        $this->iinIdCodeChrono = $iinIdCodeChrono;

        return $this;
    }

    /**
     * Get iinIdCodeChrono
     *
     * @return string
     */
    public function getIinIdCodeChrono()
    {
        return $this->iinIdCodeChrono;
    }

    /**
     * Set iinIdDate1
     *
     * @param string $iinIdDate1
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDate1($iinIdDate1)
    {
        $this->iinIdDate1 = $iinIdDate1;

        return $this;
    }

    /**
     * Get iinIdDate1
     *
     * @return string
     */
    public function getIinIdDate1()
    {
        return $this->iinIdDate1;
    }

    /**
     * Set iinIdDate2
     *
     * @param string $iinIdDate2
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDate2($iinIdDate2)
    {
        $this->iinIdDate2 = $iinIdDate2;

        return $this;
    }

    /**
     * Get iinIdDate2
     *
     * @return string
     */
    public function getIinIdDate2()
    {
        return $this->iinIdDate2;
    }

    /**
     * Set iinIdDate3
     *
     * @param string $iinIdDate3
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDate3($iinIdDate3)
    {
        $this->iinIdDate3 = $iinIdDate3;

        return $this;
    }

    /**
     * Get iinIdDate3
     *
     * @return string
     */
    public function getIinIdDate3()
    {
        return $this->iinIdDate3;
    }

    /**
     * Set iinIdDate4
     *
     * @param string $iinIdDate4
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDate4($iinIdDate4)
    {
        $this->iinIdDate4 = $iinIdDate4;

        return $this;
    }

    /**
     * Get iinIdDate4
     *
     * @return string
     */
    public function getIinIdDate4()
    {
        return $this->iinIdDate4;
    }

    /**
     * Set iinIdDate5
     *
     * @param string $iinIdDate5
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDate5($iinIdDate5)
    {
        $this->iinIdDate5 = $iinIdDate5;

        return $this;
    }

    /**
     * Get iinIdDate5
     *
     * @return string
     */
    public function getIinIdDate5()
    {
        return $this->iinIdDate5;
    }

    /**
     * Set iinIdDate6
     *
     * @param string $iinIdDate6
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDate6($iinIdDate6)
    {
        $this->iinIdDate6 = $iinIdDate6;

        return $this;
    }

    /**
     * Get iinIdDate6
     *
     * @return string
     */
    public function getIinIdDate6()
    {
        return $this->iinIdDate6;
    }

    /**
     * Set iinIdDate7
     *
     * @param string $iinIdDate7
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDate7($iinIdDate7)
    {
        $this->iinIdDate7 = $iinIdDate7;

        return $this;
    }

    /**
     * Get iinIdDate7
     *
     * @return string
     */
    public function getIinIdDate7()
    {
        return $this->iinIdDate7;
    }

    /**
     * Set iinIdDate8
     *
     * @param string $iinIdDate8
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDate8($iinIdDate8)
    {
        $this->iinIdDate8 = $iinIdDate8;

        return $this;
    }

    /**
     * Get iinIdDate8
     *
     * @return string
     */
    public function getIinIdDate8()
    {
        return $this->iinIdDate8;
    }

    /**
     * Set iinIdDateAdp1
     *
     * @param string $iinIdDateAdp1
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDateAdp1($iinIdDateAdp1)
    {
        $this->iinIdDateAdp1 = $iinIdDateAdp1;

        return $this;
    }

    /**
     * Get iinIdDateAdp1
     *
     * @return string
     */
    public function getIinIdDateAdp1()
    {
        return $this->iinIdDateAdp1;
    }

    /**
     * Set iinIdDateAdp2
     *
     * @param string $iinIdDateAdp2
     *
     * @return IinIdxIndiv
     */
    public function setIinIdDateAdp2($iinIdDateAdp2)
    {
        $this->iinIdDateAdp2 = $iinIdDateAdp2;

        return $this;
    }

    /**
     * Get iinIdDateAdp2
     *
     * @return string
     */
    public function getIinIdDateAdp2()
    {
        return $this->iinIdDateAdp2;
    }

    /**
     * Set iinIdAlphanum1
     *
     * @param string $iinIdAlphanum1
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum1($iinIdAlphanum1)
    {
        $this->iinIdAlphanum1 = $iinIdAlphanum1;

        return $this;
    }

    /**
     * Get iinIdAlphanum1
     *
     * @return string
     */
    public function getIinIdAlphanum1()
    {
        return $this->iinIdAlphanum1;
    }

    /**
     * Set iinIdAlphanum2
     *
     * @param string $iinIdAlphanum2
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum2($iinIdAlphanum2)
    {
        $this->iinIdAlphanum2 = $iinIdAlphanum2;

        return $this;
    }

    /**
     * Get iinIdAlphanum2
     *
     * @return string
     */
    public function getIinIdAlphanum2()
    {
        return $this->iinIdAlphanum2;
    }

    /**
     * Set iinIdAlphanum3
     *
     * @param string $iinIdAlphanum3
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum3($iinIdAlphanum3)
    {
        $this->iinIdAlphanum3 = $iinIdAlphanum3;

        return $this;
    }

    /**
     * Get iinIdAlphanum3
     *
     * @return string
     */
    public function getIinIdAlphanum3()
    {
        return $this->iinIdAlphanum3;
    }

    /**
     * Set iinIdAlphanum4
     *
     * @param string $iinIdAlphanum4
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum4($iinIdAlphanum4)
    {
        $this->iinIdAlphanum4 = $iinIdAlphanum4;

        return $this;
    }

    /**
     * Get iinIdAlphanum4
     *
     * @return string
     */
    public function getIinIdAlphanum4()
    {
        return $this->iinIdAlphanum4;
    }

    /**
     * Set iinIdAlphanum5
     *
     * @param string $iinIdAlphanum5
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum5($iinIdAlphanum5)
    {
        $this->iinIdAlphanum5 = $iinIdAlphanum5;

        return $this;
    }

    /**
     * Get iinIdAlphanum5
     *
     * @return string
     */
    public function getIinIdAlphanum5()
    {
        return $this->iinIdAlphanum5;
    }

    /**
     * Set iinIdAlphanum6
     *
     * @param string $iinIdAlphanum6
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum6($iinIdAlphanum6)
    {
        $this->iinIdAlphanum6 = $iinIdAlphanum6;

        return $this;
    }

    /**
     * Get iinIdAlphanum6
     *
     * @return string
     */
    public function getIinIdAlphanum6()
    {
        return $this->iinIdAlphanum6;
    }

    /**
     * Set iinIdAlphanum7
     *
     * @param string $iinIdAlphanum7
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum7($iinIdAlphanum7)
    {
        $this->iinIdAlphanum7 = $iinIdAlphanum7;

        return $this;
    }

    /**
     * Get iinIdAlphanum7
     *
     * @return string
     */
    public function getIinIdAlphanum7()
    {
        return $this->iinIdAlphanum7;
    }

    /**
     * Set iinIdAlphanum8
     *
     * @param string $iinIdAlphanum8
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum8($iinIdAlphanum8)
    {
        $this->iinIdAlphanum8 = $iinIdAlphanum8;

        return $this;
    }

    /**
     * Get iinIdAlphanum8
     *
     * @return string
     */
    public function getIinIdAlphanum8()
    {
        return $this->iinIdAlphanum8;
    }

    /**
     * Set iinIdAlphanum9
     *
     * @param string $iinIdAlphanum9
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum9($iinIdAlphanum9)
    {
        $this->iinIdAlphanum9 = $iinIdAlphanum9;

        return $this;
    }

    /**
     * Get iinIdAlphanum9
     *
     * @return string
     */
    public function getIinIdAlphanum9()
    {
        return $this->iinIdAlphanum9;
    }

    /**
     * Set iinIdAlphanum10
     *
     * @param string $iinIdAlphanum10
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum10($iinIdAlphanum10)
    {
        $this->iinIdAlphanum10 = $iinIdAlphanum10;

        return $this;
    }

    /**
     * Get iinIdAlphanum10
     *
     * @return string
     */
    public function getIinIdAlphanum10()
    {
        return $this->iinIdAlphanum10;
    }

    /**
     * Set iinIdAlphanum11
     *
     * @param string $iinIdAlphanum11
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum11($iinIdAlphanum11)
    {
        $this->iinIdAlphanum11 = $iinIdAlphanum11;

        return $this;
    }

    /**
     * Get iinIdAlphanum11
     *
     * @return string
     */
    public function getIinIdAlphanum11()
    {
        return $this->iinIdAlphanum11;
    }

    /**
     * Set iinIdAlphanum12
     *
     * @param string $iinIdAlphanum12
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum12($iinIdAlphanum12)
    {
        $this->iinIdAlphanum12 = $iinIdAlphanum12;

        return $this;
    }

    /**
     * Get iinIdAlphanum12
     *
     * @return string
     */
    public function getIinIdAlphanum12()
    {
        return $this->iinIdAlphanum12;
    }

    /**
     * Set iinIdAlphanum13
     *
     * @param string $iinIdAlphanum13
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum13($iinIdAlphanum13)
    {
        $this->iinIdAlphanum13 = $iinIdAlphanum13;

        return $this;
    }

    /**
     * Get iinIdAlphanum13
     *
     * @return string
     */
    public function getIinIdAlphanum13()
    {
        return $this->iinIdAlphanum13;
    }

    /**
     * Set iinIdAlphanum14
     *
     * @param string $iinIdAlphanum14
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum14($iinIdAlphanum14)
    {
        $this->iinIdAlphanum14 = $iinIdAlphanum14;

        return $this;
    }

    /**
     * Get iinIdAlphanum14
     *
     * @return string
     */
    public function getIinIdAlphanum14()
    {
        return $this->iinIdAlphanum14;
    }

    /**
     * Set iinIdAlphanum15
     *
     * @param string $iinIdAlphanum15
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum15($iinIdAlphanum15)
    {
        $this->iinIdAlphanum15 = $iinIdAlphanum15;

        return $this;
    }

    /**
     * Get iinIdAlphanum15
     *
     * @return string
     */
    public function getIinIdAlphanum15()
    {
        return $this->iinIdAlphanum15;
    }

    /**
     * Set iinIdAlphanum16
     *
     * @param string $iinIdAlphanum16
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum16($iinIdAlphanum16)
    {
        $this->iinIdAlphanum16 = $iinIdAlphanum16;

        return $this;
    }

    /**
     * Get iinIdAlphanum16
     *
     * @return string
     */
    public function getIinIdAlphanum16()
    {
        return $this->iinIdAlphanum16;
    }

    /**
     * Set iinIdAlphanum17
     *
     * @param string $iinIdAlphanum17
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum17($iinIdAlphanum17)
    {
        $this->iinIdAlphanum17 = $iinIdAlphanum17;

        return $this;
    }

    /**
     * Get iinIdAlphanum17
     *
     * @return string
     */
    public function getIinIdAlphanum17()
    {
        return $this->iinIdAlphanum17;
    }

    /**
     * Set iinIdAlphanum18
     *
     * @param string $iinIdAlphanum18
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanum18($iinIdAlphanum18)
    {
        $this->iinIdAlphanum18 = $iinIdAlphanum18;

        return $this;
    }

    /**
     * Get iinIdAlphanum18
     *
     * @return string
     */
    public function getIinIdAlphanum18()
    {
        return $this->iinIdAlphanum18;
    }

    /**
     * Set iinIdAlphanumAdp1
     *
     * @param string $iinIdAlphanumAdp1
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanumAdp1($iinIdAlphanumAdp1)
    {
        $this->iinIdAlphanumAdp1 = $iinIdAlphanumAdp1;

        return $this;
    }

    /**
     * Get iinIdAlphanumAdp1
     *
     * @return string
     */
    public function getIinIdAlphanumAdp1()
    {
        return $this->iinIdAlphanumAdp1;
    }

    /**
     * Set iinIdAlphanumAdp2
     *
     * @param string $iinIdAlphanumAdp2
     *
     * @return IinIdxIndiv
     */
    public function setIinIdAlphanumAdp2($iinIdAlphanumAdp2)
    {
        $this->iinIdAlphanumAdp2 = $iinIdAlphanumAdp2;

        return $this;
    }

    /**
     * Get iinIdAlphanumAdp2
     *
     * @return string
     */
    public function getIinIdAlphanumAdp2()
    {
        return $this->iinIdAlphanumAdp2;
    }

    /**
     * Set iinIdNum1
     *
     * @param float $iinIdNum1
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum1($iinIdNum1)
    {
        $this->iinIdNum1 = $iinIdNum1;

        return $this;
    }

    /**
     * Get iinIdNum1
     *
     * @return float
     */
    public function getIinIdNum1()
    {
        return $this->iinIdNum1;
    }

    /**
     * Set iinIdNum2
     *
     * @param float $iinIdNum2
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum2($iinIdNum2)
    {
        $this->iinIdNum2 = $iinIdNum2;

        return $this;
    }

    /**
     * Get iinIdNum2
     *
     * @return float
     */
    public function getIinIdNum2()
    {
        return $this->iinIdNum2;
    }

    /**
     * Set iinIdNum3
     *
     * @param float $iinIdNum3
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum3($iinIdNum3)
    {
        $this->iinIdNum3 = $iinIdNum3;

        return $this;
    }

    /**
     * Get iinIdNum3
     *
     * @return float
     */
    public function getIinIdNum3()
    {
        return $this->iinIdNum3;
    }

    /**
     * Set iinIdNum4
     *
     * @param float $iinIdNum4
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum4($iinIdNum4)
    {
        $this->iinIdNum4 = $iinIdNum4;

        return $this;
    }

    /**
     * Get iinIdNum4
     *
     * @return float
     */
    public function getIinIdNum4()
    {
        return $this->iinIdNum4;
    }

    /**
     * Set iinIdNum5
     *
     * @param float $iinIdNum5
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum5($iinIdNum5)
    {
        $this->iinIdNum5 = $iinIdNum5;

        return $this;
    }

    /**
     * Get iinIdNum5
     *
     * @return float
     */
    public function getIinIdNum5()
    {
        return $this->iinIdNum5;
    }

    /**
     * Set iinIdNum6
     *
     * @param float $iinIdNum6
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum6($iinIdNum6)
    {
        $this->iinIdNum6 = $iinIdNum6;

        return $this;
    }

    /**
     * Get iinIdNum6
     *
     * @return float
     */
    public function getIinIdNum6()
    {
        return $this->iinIdNum6;
    }

    /**
     * Set iinIdNum7
     *
     * @param float $iinIdNum7
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum7($iinIdNum7)
    {
        $this->iinIdNum7 = $iinIdNum7;

        return $this;
    }

    /**
     * Get iinIdNum7
     *
     * @return float
     */
    public function getIinIdNum7()
    {
        return $this->iinIdNum7;
    }

    /**
     * Set iinIdNum8
     *
     * @param float $iinIdNum8
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum8($iinIdNum8)
    {
        $this->iinIdNum8 = $iinIdNum8;

        return $this;
    }

    /**
     * Get iinIdNum8
     *
     * @return float
     */
    public function getIinIdNum8()
    {
        return $this->iinIdNum8;
    }

    /**
     * Set iinIdNum9
     *
     * @param float $iinIdNum9
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum9($iinIdNum9)
    {
        $this->iinIdNum9 = $iinIdNum9;

        return $this;
    }

    /**
     * Get iinIdNum9
     *
     * @return float
     */
    public function getIinIdNum9()
    {
        return $this->iinIdNum9;
    }

    /**
     * Set iinIdNum10
     *
     * @param float $iinIdNum10
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNum10($iinIdNum10)
    {
        $this->iinIdNum10 = $iinIdNum10;

        return $this;
    }

    /**
     * Get iinIdNum10
     *
     * @return float
     */
    public function getIinIdNum10()
    {
        return $this->iinIdNum10;
    }

    /**
     * Set iinIdNumOrdre
     *
     * @param string $iinIdNumOrdre
     *
     * @return IinIdxIndiv
     */
    public function setIinIdNumOrdre($iinIdNumOrdre)
    {
        $this->iinIdNumOrdre = $iinIdNumOrdre;

        return $this;
    }

    /**
     * Get iinIdNumOrdre
     *
     * @return string
     */
    public function getIinIdNumOrdre()
    {
        return $this->iinIdNumOrdre;
    }

    /**
     * Set iinCreatedAt
     *
     * @param \DateTime $iinCreatedAt
     *
     * @return IinIdxIndiv
     */
    public function setIinCreatedAt($iinCreatedAt)
    {
        $this->iinCreatedAt = $iinCreatedAt;

        return $this;
    }

    /**
     * Get iinCreatedAt
     *
     * @return \DateTime
     */
    public function getIinCreatedAt()
    {
        return $this->iinCreatedAt;
    }

    /**
     * Set iinUpdatedAt
     *
     * @param \DateTime $iinUpdatedAt
     *
     * @return IinIdxIndiv
     */
    public function setIinUpdatedAt($iinUpdatedAt)
    {
        $this->iinUpdatedAt = $iinUpdatedAt;

        return $this;
    }

    /**
     * Get iinUpdatedAt
     *
     * @return \DateTime
     */
    public function getIinUpdatedAt()
    {
        return $this->iinUpdatedAt;
    }
}
