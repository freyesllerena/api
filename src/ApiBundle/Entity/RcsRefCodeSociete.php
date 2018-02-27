<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * RcsRefCodeSociete
 *
 * @ORM\Table(
 *     name="rcs_ref_code_societe",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(
 *       name="rcs_code_cli",
 *       columns={"rcs_code", "rcs_id_code_client"}
 *      ),
 *      @ORM\UniqueConstraint(
 *       name="rcs_siren",
 *       columns={"rcs_siren"}
 *      )
 *     },
 *     indexes={
 *      @ORM\Index(
 *       name="rcs_id_code_client",
 *       columns={"rcs_id_code_client"}
 *      ),
 *      @ORM\Index(
 *       name="rcs_libelle",
 *       columns={"rcs_libelle"}
 *      )
 *     }
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\RcsRefCodeSocieteRepository")
 */
class RcsRefCodeSociete
{
    const ERR_PAC_DOES_NOT_EXIST = 'errRcsRefCodeSocietePacDoesNotExist';

    /**
     * @var integer
     *
     * @ORM\Column(name="rcs_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rcsId;

    /**
     * @var string
     *
     * @ORM\Column(name="rcs_code", type="string", length=50, nullable=false)
     */
    private $rcsCode;

    /**
     * @var string
     *
     * @ORM\Column(name="rcs_libelle", type="string", length=255, nullable=false)
     */
    private $rcsLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="rcs_siren", type="string", length=9, nullable=false)
     */
    private $rcsSiren;

    /**
     * @var string
     *
     * @ORM\Column(name="rcs_id_code_client", type="string", length=20, nullable=false)
     */
    private $rcsIdCodeClient;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="rcs_created_at", type="datetime", nullable=true)
     */
    private $rcsCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="rcs_updated_at", type="datetime", nullable=true)
     */
    private $rcsUpdatedAt;



    /**
     * Get rcsId
     *
     * @return integer
     */
    public function getRcsId()
    {
        return $this->rcsId;
    }

    /**
     * Set rcsCode
     *
     * @param string $rcsCode
     *
     * @return RcsRefCodeSociete
     */
    public function setRcsCode($rcsCode)
    {
        $this->rcsCode = $rcsCode;

        return $this;
    }

    /**
     * Get rcsCode
     *
     * @return string
     */
    public function getRcsCode()
    {
        return $this->rcsCode;
    }

    /**
     * Set rcsLibelle
     *
     * @param string $rcsLibelle
     *
     * @return RcsRefCodeSociete
     */
    public function setRcsLibelle($rcsLibelle)
    {
        $this->rcsLibelle = $rcsLibelle;

        return $this;
    }

    /**
     * Get rcsLibelle
     *
     * @return string
     */
    public function getRcsLibelle()
    {
        return $this->rcsLibelle;
    }

    /**
     * Set rcsSiren
     *
     * @param string $rcsSiren
     *
     * @return RcsRefCodeSociete
     */
    public function setRcsSiren($rcsSiren)
    {
        $this->rcsSiren = $rcsSiren;

        return $this;
    }

    /**
     * Get rcsSiren
     *
     * @return string
     */
    public function getRcsSiren()
    {
        return $this->rcsSiren;
    }

    /**
     * Set rcsIdCodeClient
     *
     * @param string $rcsIdCodeClient
     *
     * @return RcsRefCodeSociete
     */
    public function setRcsIdCodeClient($rcsIdCodeClient)
    {
        $this->rcsIdCodeClient = $rcsIdCodeClient;

        return $this;
    }

    /**
     * Get rcsIdCodeClient
     *
     * @return string
     */
    public function getRcsIdCodeClient()
    {
        return $this->rcsIdCodeClient;
    }

    /**
     * Set rcsCreatedAt
     *
     * @param \DateTime $rcsCreatedAt
     *
     * @return RcsRefCodeSociete
     */
    public function setRcsCreatedAt($rcsCreatedAt)
    {
        $this->rcsCreatedAt = $rcsCreatedAt;

        return $this;
    }

    /**
     * Get rcsCreatedAt
     *
     * @return \DateTime
     */
    public function getRcsCreatedAt()
    {
        return $this->rcsCreatedAt;
    }

    /**
     * Set rcsUpdatedAt
     *
     * @param \DateTime $rcsUpdatedAt
     *
     * @return RcsRefCodeSociete
     */
    public function setRcsUpdatedAt($rcsUpdatedAt)
    {
        $this->rcsUpdatedAt = $rcsUpdatedAt;

        return $this;
    }

    /**
     * Get rcsUpdatedAt
     *
     * @return \DateTime
     */
    public function getRcsUpdatedAt()
    {
        return $this->rcsUpdatedAt;
    }
}
