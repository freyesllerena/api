<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PdaProfilDefAppli
 *
 * @ORM\Table(name="pda_profil_def_appli", uniqueConstraints={@ORM\UniqueConstraint(name="pda_libelle", columns={"pda_libelle"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\PdaProfilDefAppliRepository")
 */
class PdaProfilDefAppli
{
    const DEFAULT_PDA_ADP = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="pda_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $pdaId;

    /**
     * @var string
     *
     * @ORM\Column(name="pda_libelle", type="string", length=100, nullable=false)
     */
    private $pdaLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="pda_ref_bve", type="text", length=65535, nullable=false)
     */
    private $pdaRefBve;

    /**
     * @var integer
     *
     * @ORM\Column(name="pda_nbi", type="integer", nullable=false)
     */
    private $pdaNbi;

    /**
     * @var integer
     *
     * @ORM\Column(name="pda_nbc", type="integer", nullable=false)
     */
    private $pdaNbc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pda_adp", type="boolean", nullable=false)
     */
    private $pdaAdp = self::DEFAULT_PDA_ADP;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="pda_created_at", type="datetime", nullable=true)
     */
    private $pdaCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="pda_updated_at", type="datetime", nullable=true)
     */
    private $pdaUpdatedAt;


    /**
     * Get pdaId
     *
     * @return integer
     */
    public function getPdaId()
    {
        return $this->pdaId;
    }

    /**
     * Set pdaLibelle
     *
     * @param string $pdaLibelle
     *
     * @return PdaProfilDefAppli
     */
    public function setPdaLibelle($pdaLibelle)
    {
        $this->pdaLibelle = $pdaLibelle;

        return $this;
    }

    /**
     * Get pdaLibelle
     *
     * @return string
     */
    public function getPdaLibelle()
    {
        return $this->pdaLibelle;
    }

    /**
     * Set pdaRefBve
     *
     * @param string $pdaRefBve
     *
     * @return PdaProfilDefAppli
     */
    public function setPdaRefBve($pdaRefBve)
    {
        $this->pdaRefBve = $pdaRefBve;

        return $this;
    }

    /**
     * Get pdaRefBve
     *
     * @return string
     */
    public function getPdaRefBve()
    {
        return $this->pdaRefBve;
    }

    /**
     * Set pdaNbi
     *
     * @param integer $pdaNbi
     *
     * @return PdaProfilDefAppli
     */
    public function setPdaNbi($pdaNbi)
    {
        $this->pdaNbi = $pdaNbi;

        return $this;
    }

    /**
     * Get pdaNbi
     *
     * @return integer
     */
    public function getPdaNbi()
    {
        return $this->pdaNbi;
    }

    /**
     * Set pdaNbc
     *
     * @param integer $pdaNbc
     *
     * @return PdaProfilDefAppli
     */
    public function setPdaNbc($pdaNbc)
    {
        $this->pdaNbc = $pdaNbc;

        return $this;
    }

    /**
     * Get pdaNbc
     *
     * @return integer
     */
    public function getPdaNbc()
    {
        return $this->pdaNbc;
    }

    /**
     * Set pdaAdp
     *
     * @param boolean $pdaAdp
     *
     * @return PdaProfilDefAppli
     */
    public function setPdaAdp($pdaAdp)
    {
        $this->pdaAdp = $pdaAdp;

        return $this;
    }

    /**
     * Get pdaAdp
     *
     * @return boolean
     */
    public function isPdaAdp()
    {
        return $this->pdaAdp;
    }

    /**
     * Set pdaCreatedAt
     *
     * @param \DateTime $pdaCreatedAt
     *
     * @return PdaProfilDefAppli
     */
    public function setPdaCreatedAt($pdaCreatedAt)
    {
        $this->pdaCreatedAt = $pdaCreatedAt;

        return $this;
    }

    /**
     * Get pdaCreatedAt
     *
     * @return \DateTime
     */
    public function getPdaCreatedAt()
    {
        return $this->pdaCreatedAt;
    }

    /**
     * Set pdaUpdatedAt
     *
     * @param \DateTime $pdaUpdatedAt
     *
     * @return PdaProfilDefAppli
     */
    public function setPdaUpdatedAt($pdaUpdatedAt)
    {
        $this->pdaUpdatedAt = $pdaUpdatedAt;

        return $this;
    }

    /**
     * Get pdaUpdatedAt
     *
     * @return \DateTime
     */
    public function getPdaUpdatedAt()
    {
        return $this->pdaUpdatedAt;
    }

    /**
     * Met à jour les tiroirs
     *
     * @param array $tiroirs
     */
    public function setPdaTiroirs(array $tiroirs)
    {
        $this->pdaRefBve = implode('|', $tiroirs);
    }

    /**
     * Renvoit les tiroirs
     *
     * @return array
     */
    public function getPdaTiroirs()
    {
        return explode('|', $this->pdaRefBve);
    }
}
