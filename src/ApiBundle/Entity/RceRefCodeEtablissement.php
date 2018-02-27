<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * RceRefCodeEtablissement
 *
 * @ORM\Table(
 *     name="rce_ref_code_etablissement",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(
 *       name="rce_code_soc",
 *       columns={"rce_code", "rce_id_societe"}
 *      )
 *     },
 *     indexes={
 *      @ORM\Index(
 *       name="rce_id_societe",
 *       columns={"rce_id_societe"}
 *      ),
 *      @ORM\Index(
 *       name="rce_libelle",
 *       columns={"rce_libelle"}
 *      )
 *     }
 * )
 * @ORM\Entity
 */
class RceRefCodeEtablissement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rce_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rceId;

    /**
     * @var string
     *
     * @ORM\Column(name="rce_code", type="string", length=50, nullable=false)
     */
    private $rceCode;

    /**
     * @var string
     *
     * @ORM\Column(name="rce_libelle", type="string", length=255, nullable=false)
     */
    private $rceLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="rce_nic", type="string", length=5, nullable=false)
     */
    private $rceNic;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="rce_created_at", type="datetime", nullable=true)
     */
    private $rceCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="rce_updated_at", type="datetime", nullable=true)
     */
    private $rceUpdatedAt;

    /**
     * @var RcsRefCodeSociete
     *
     * @ORM\ManyToOne(targetEntity="RcsRefCodeSociete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rce_id_societe", referencedColumnName="rcs_id")
     * })
     */
    private $rceSociete;



    /**
     * Get rceId
     *
     * @return integer
     */
    public function getRceId()
    {
        return $this->rceId;
    }

    /**
     * Set rceCode
     *
     * @param string $rceCode
     *
     * @return RceRefCodeEtablissement
     */
    public function setRceCode($rceCode)
    {
        $this->rceCode = $rceCode;

        return $this;
    }

    /**
     * Get rceCode
     *
     * @return string
     */
    public function getRceCode()
    {
        return $this->rceCode;
    }

    /**
     * Set rceLibelle
     *
     * @param string $rceLibelle
     *
     * @return RceRefCodeEtablissement
     */
    public function setRceLibelle($rceLibelle)
    {
        $this->rceLibelle = $rceLibelle;

        return $this;
    }

    /**
     * Get rceLibelle
     *
     * @return string
     */
    public function getRceLibelle()
    {
        return $this->rceLibelle;
    }

    /**
     * Set rceNic
     *
     * @param string $rceNic
     *
     * @return RceRefCodeEtablissement
     */
    public function setRceNic($rceNic)
    {
        $this->rceNic = $rceNic;

        return $this;
    }

    /**
     * Get rceNic
     *
     * @return string
     */
    public function getRceNic()
    {
        return $this->rceNic;
    }

    /**
     * Set rceCreatedAt
     *
     * @param \DateTime $rceCreatedAt
     *
     * @return RceRefCodeEtablissement
     */
    public function setRceCreatedAt($rceCreatedAt)
    {
        $this->rceCreatedAt = $rceCreatedAt;

        return $this;
    }

    /**
     * Get rceCreatedAt
     *
     * @return \DateTime
     */
    public function getRceCreatedAt()
    {
        return $this->rceCreatedAt;
    }

    /**
     * Set rceUpdatedAt
     *
     * @param \DateTime $rceUpdatedAt
     *
     * @return RceRefCodeEtablissement
     */
    public function setRceUpdatedAt($rceUpdatedAt)
    {
        $this->rceUpdatedAt = $rceUpdatedAt;

        return $this;
    }

    /**
     * Get rceUpdatedAt
     *
     * @return \DateTime
     */
    public function getRceUpdatedAt()
    {
        return $this->rceUpdatedAt;
    }

    /**
     * Set rceSociete
     *
     * @param \ApiBundle\Entity\RcsRefCodeSociete $rceSociete
     *
     * @return RceRefCodeEtablissement
     */
    public function setRceSociete(RcsRefCodeSociete $rceSociete = null)
    {
        $this->rceSociete = $rceSociete;

        return $this;
    }

    /**
     * Get rceSociete
     *
     * @return \ApiBundle\Entity\RcsRefCodeSociete
     */
    public function getRceSociete()
    {
        return $this->rceSociete;
    }
}
