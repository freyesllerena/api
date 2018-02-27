<?php

namespace ApiBundle\Entity;

use ApiBundle\Enum\EnumLabelModeHabilitationType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PdhProfilDefHabi
 *
 * @ORM\Table(name="pdh_profil_def_habi", uniqueConstraints={@ORM\UniqueConstraint(name="pdh_libelle", columns={"pdh_libelle"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\PdhProfilDefHabiRepository")
 */
class PdhProfilDefHabi
{
    const DEFAULT_PDH_ADP = false;

    const COLLECTIF = 'collectif';
    const INDIVIDUEL = 'individuel';

    /**
     * @var integer
     *
     * @ORM\Column(name="pdh_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $pdhId;

    /**
     * @var string
     *
     * @ORM\Column(name="pdh_libelle", type="string", length=100, nullable=false)
     */
    private $pdhLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="pdh_habilitation_i", type="text", nullable=false)
     */
    private $pdhHabilitationI;

    /**
     * @var string
     *
     * @ORM\Column(name="pdh_habilitation_c", type="text", nullable=false)
     */
    private $pdhHabilitationC;

    /**
     * @var string
     *
     * @ORM\Column(name="pdh_mode", type="string", length=20, nullable=false)
     */
    private $pdhMode = EnumLabelModeHabilitationType::REFERENCE_MODE;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pdh_adp", type="boolean", nullable=false)
     */
    private $pdhAdp = self::DEFAULT_PDH_ADP;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="pdh_created_at", type="datetime", nullable=true)
     */
    private $pdhCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="pdh_updated_at", type="datetime", nullable=true)
     */
    private $pdhUpdatedAt;


    /**
     * Get pdhId
     *
     * @return integer
     */
    public function getPdhId()
    {
        return $this->pdhId;
    }

    /**
     * Set pdhLibelle
     *
     * @param string $pdhLibelle
     *
     * @return PdhProfilDefHabi
     */
    public function setPdhLibelle($pdhLibelle)
    {
        $this->pdhLibelle = $pdhLibelle;

        return $this;
    }

    /**
     * Get pdhLibelle
     *
     * @return string
     */
    public function getPdhLibelle()
    {
        return $this->pdhLibelle;
    }

    /**
     * Set pdhHabilitationI
     *
     * @param string $pdhHabilitationI
     *
     * @return PdhProfilDefHabi
     */
    public function setPdhHabilitationI($pdhHabilitationI)
    {
        $this->pdhHabilitationI = $pdhHabilitationI;

        return $this;
    }

    /**
     * Get pdhHabilitationI
     *
     * @return string
     */
    public function getPdhHabilitationI()
    {
        return $this->pdhHabilitationI;
    }

    /**
     * Set pdhHabilitationC
     *
     * @param string $pdhHabilitationC
     *
     * @return PdhProfilDefHabi
     */
    public function setPdhHabilitationC($pdhHabilitationC)
    {
        $this->pdhHabilitationC = $pdhHabilitationC;

        return $this;
    }

    /**
     * Get pdhHabilitationC
     *
     * @return string
     */
    public function getPdhHabilitationC()
    {
        return $this->pdhHabilitationC;
    }

    /**
     * Set pdhMode
     *
     * @param string $pdhMode
     *
     * @return PdhProfilDefHabi
     */
    public function setPdhMode($pdhMode)
    {
        $this->pdhMode = $pdhMode;

        return $this;
    }

    /**
     * Get pdhMode
     *
     * @return string
     */
    public function getPdhMode()
    {
        return $this->pdhMode;
    }

    /**
     * Set pdhAdp
     *
     * @param boolean $pdhAdp
     *
     * @return PdhProfilDefHabi
     */
    public function setPdhAdp($pdhAdp)
    {
        $this->pdhAdp = $pdhAdp;

        return $this;
    }

    /**
     * Get pdhAdp
     *
     * @return boolean
     */
    public function isPdhAdp()
    {
        return $this->pdhAdp;
    }

    /**
     * Set pdhCreatedAt
     *
     * @param \DateTime $pdhCreatedAt
     *
     * @return PdhProfilDefHabi
     */
    public function setPdhCreatedAt($pdhCreatedAt)
    {
        $this->pdhCreatedAt = $pdhCreatedAt;

        return $this;
    }

    /**
     * Get pdhCreatedAt
     *
     * @return \DateTime
     */
    public function getPdhCreatedAt()
    {
        return $this->pdhCreatedAt;
    }

    /**
     * Set pdhUpdatedAt
     *
     * @param \DateTime $pdhUpdatedAt
     *
     * @return PdhProfilDefHabi
     */
    public function setPdhUpdatedAt($pdhUpdatedAt)
    {
        $this->pdhUpdatedAt = $pdhUpdatedAt;

        return $this;
    }

    /**
     * Get pdhUpdatedAt
     *
     * @return \DateTime
     */
    public function getPdhUpdatedAt()
    {
        return $this->pdhUpdatedAt;
    }

    /**
     * Renvoit les habilitations
     *
     * @return array
     */
    public function getPdhHabilitations() {
        return array(
            SELF::COLLECTIF => $this->pdhHabilitationC,
            SELF::INDIVIDUEL => $this->pdhHabilitationI,
        );
    }

    /**
     * Renvoit les types d'habilitations
     *
     * @return array
     */
    public static function getTypesHabilitations() {
        return array(
            SELF::COLLECTIF,
            SELF::INDIVIDUEL
        );
    }

    /**
     * Met à jour les habilitations
     *
     * @param array $habilitations
     */
    public function setPdhHabilitations(array $habilitations)
    {
        $habilitations += array(
            self::COLLECTIF => null,
            self::INDIVIDUEL => null
        );

        $this->pdhHabilitationC = $habilitations[self::COLLECTIF];
        $this->pdhHabilitationI = $habilitations[self::INDIVIDUEL];
    }

    /**
     * Determine si le filtre s'applique sur les métadatas actuelles
     *
     * @return bool
     */
    public function isPdhModeReference()
    {
        if ($this->pdhMode == EnumLabelModeHabilitationType::MIXED_MODE ||
            $this->pdhMode == EnumLabelModeHabilitationType::REFERENCE_MODE) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Détermine si le filtre s'applique sur les métadatas archivés
     *
     * @return bool
     */
    public function isPdhModeArchive()
    {
        if ($this->pdhMode == EnumLabelModeHabilitationType::MIXED_MODE ||
            $this->pdhMode == EnumLabelModeHabilitationType::ARCHIVE_MODE) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Active le mode reference
     *
     * @param $value
     */
    public function setPdhModeReference($value)
    {
        $this->updatePdhModeByReferenceAndArchive(
            $value,
            $this->isPdhModeArchive()
        );
    }

    /**
     * Active le mode archive
     *
     * @param $value
     */
    public function setPdhModeArchive($value)
    {
        $this->updatePdhModeByReferenceAndArchive(
            $this->isPdhModeReference(),
            $value
        );
    }

    /**
     * Met à jour le mode par reference et archive
     *
     * @param $reference
     * @param $archive
     */
    protected function updatePdhModeByReferenceAndArchive($reference, $archive)
    {
        if ($reference && $archive) {
            $this->pdhMode = EnumLabelModeHabilitationType::MIXED_MODE;
        } elseif ($reference) {
            $this->pdhMode = EnumLabelModeHabilitationType::REFERENCE_MODE;
        } elseif ($archive) {
            $this->pdhMode = EnumLabelModeHabilitationType::ARCHIVE_MODE;
        } else {
            $this->pdhMode = '';
        }
    }
}
