<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelTypeFilterType;

/**
 * RidRefId
 *
 * @ORM\Table(
 *     name="rid_ref_id",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(
 *       name="rid_code_cli_typ",
 *       columns={"rid_code", "rid_id_code_client", "rid_type"}
 *      )
 *     }
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\RidRefIdRepository")
 */
class RidRefId
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rid_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ridId;

    /**
     * @var string
     *
     * @ORM\Column(name="rid_code", type="string", length=50, nullable=false)
     */
    private $ridCode;

    /**
     * @var string
     *
     * @ORM\Column(name="rid_libelle", type="string", length=255, nullable=false)
     */
    private $ridLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="rid_id_code_client", type="string", length=20, nullable=false)
     */
    private $ridIdCodeClient;

    /**
     * @var string
     *
     * @ORM\Column(name="rid_type", type="string", length=30, nullable=false)
     */
    private $ridType = EnumLabelTypeFilterType::ACTIVITY_CODE_TYPE_FILTER;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="rid_created_at", type="datetime", nullable=true)
     */
    private $ridCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="rid_updated_at", type="datetime", nullable=true)
     */
    private $ridUpdatedAt;



    /**
     * Get ridId
     *
     * @return integer
     */
    public function getRidId()
    {
        return $this->ridId;
    }

    /**
     * Set ridCode
     *
     * @param string $ridCode
     *
     * @return RidRefId
     */
    public function setRidCode($ridCode)
    {
        $this->ridCode = $ridCode;

        return $this;
    }

    /**
     * Get ridCode
     *
     * @return string
     */
    public function getRidCode()
    {
        return $this->ridCode;
    }

    /**
     * Set ridLibelle
     *
     * @param string $ridLibelle
     *
     * @return RidRefId
     */
    public function setRidLibelle($ridLibelle)
    {
        $this->ridLibelle = $ridLibelle;

        return $this;
    }

    /**
     * Get ridLibelle
     *
     * @return string
     */
    public function getRidLibelle()
    {
        return $this->ridLibelle;
    }

    /**
     * Set ridIdCodeClient
     *
     * @param string $ridIdCodeClient
     *
     * @return RidRefId
     */
    public function setRidIdCodeClient($ridIdCodeClient)
    {
        $this->ridIdCodeClient = $ridIdCodeClient;

        return $this;
    }

    /**
     * Get ridIdCodeClient
     *
     * @return string
     */
    public function getRidIdCodeClient()
    {
        return $this->ridIdCodeClient;
    }

    /**
     * Set ridType
     *
     * @param string $ridType
     *
     * @return RidRefId
     */
    public function setRidType($ridType)
    {
        $this->ridType = $ridType;

        return $this;
    }

    /**
     * Get ridType
     *
     * @return string
     */
    public function getRidType()
    {
        return $this->ridType;
    }

    /**
     * Set ridCreatedAt
     *
     * @param \DateTime $ridCreatedAt
     *
     * @return RidRefId
     */
    public function setRidCreatedAt($ridCreatedAt)
    {
        $this->ridCreatedAt = $ridCreatedAt;

        return $this;
    }

    /**
     * Get ridCreatedAt
     *
     * @return \DateTime
     */
    public function getRidCreatedAt()
    {
        return $this->ridCreatedAt;
    }

    /**
     * Set ridUpdatedAt
     *
     * @param \DateTime $ridUpdatedAt
     *
     * @return RidRefId
     */
    public function setRidUpdatedAt($ridUpdatedAt)
    {
        $this->ridUpdatedAt = $ridUpdatedAt;

        return $this;
    }

    /**
     * Get ridUpdatedAt
     *
     * @return \DateTime
     */
    public function getRidUpdatedAt()
    {
        return $this->ridUpdatedAt;
    }
}
