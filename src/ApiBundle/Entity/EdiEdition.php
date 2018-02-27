<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * EdiEdition
 *
 * @ORM\Table(name="edi_edition", indexes={@ORM\Index(name="edi_user_login", columns={"edi_user_login"})})
 * @ORM\Entity
 */
class EdiEdition
{
    /**
     * @var integer
     *
     * @ORM\Column(name="edi_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ediId;

    /**
     * @var integer
     *
     * @ORM\Column(name="edi_fiche", type="integer", nullable=false)
     */
    private $ediFiche;

    /**
     * @var string
     *
     * @ORM\Column(name="edi_user_login", type="string", length=50, nullable=false)
     */
    private $ediUserLogin;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="edi_created_at", type="datetime", nullable=true)
     */
    private $ediCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="edi_updated_at", type="datetime", nullable=true)
     */
    private $ediUpdatedAt;


    /**
     * Get ediId
     *
     * @return integer
     */
    public function getEdiId()
    {
        return $this->ediId;
    }

    /**
     * Set ediFiche
     *
     * @param integer $ediFiche
     *
     * @return EdiEdition
     */
    public function setEdiFiche($ediFiche)
    {
        $this->ediFiche = $ediFiche;

        return $this;
    }

    /**
     * Get ediFiche
     *
     * @return integer
     */
    public function getEdiFiche()
    {
        return $this->ediFiche;
    }

    /**
     * Set ediUserLogin
     *
     * @param string $ediUserLogin
     *
     * @return EdiEdition
     */
    public function setEdiUserLogin($ediUserLogin)
    {
        $this->ediUserLogin = $ediUserLogin;

        return $this;
    }

    /**
     * Get ediUserLogin
     *
     * @return string
     */
    public function getEdiUserLogin()
    {
        return $this->ediUserLogin;
    }

    /**
     * Set ediCreatedAt
     *
     * @param \DateTime $ediCreatedAt
     *
     * @return EdiEdition
     */
    public function setEdiCreatedAt($ediCreatedAt)
    {
        $this->ediCreatedAt = $ediCreatedAt;

        return $this;
    }

    /**
     * Get ediCreatedAt
     *
     * @return \DateTime
     */
    public function getEdiCreatedAt()
    {
        return $this->ediCreatedAt;
    }

    /**
     * Set ediUpdatedAt
     *
     * @param \DateTime $ediUpdatedAt
     *
     * @return EdiEdition
     */
    public function setEdiUpdatedAt($ediUpdatedAt)
    {
        $this->ediUpdatedAt = $ediUpdatedAt;

        return $this;
    }

    /**
     * Get ediUpdatedAt
     *
     * @return \DateTime
     */
    public function getEdiUpdatedAt()
    {
        return $this->ediUpdatedAt;
    }
}
