<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PrtPrint
 *
 * @ORM\Table(name="prt_print", indexes={@ORM\Index(name="prt_fiche", columns={"prt_fiche"})})
 * @ORM\Entity
 */
class PrtPrint
{
    /**
     * @var integer
     *
     * @ORM\Column(name="prt_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $prtId;

    /**
     * @var string
     *
     * @ORM\Column(name="prt_user_login", type="string", length=50, nullable=false)
     */
    private $prtUserLogin;

    /**
     * @var integer
     *
     * @ORM\Column(name="prt_fiche", type="integer", nullable=false)
     */
    private $prtFiche;

    /**
     * @var string
     *
     * @ORM\Column(name="prt_id_session", type="string", length=100, nullable=true)
     */
    private $prtIdSession;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="prt_created_at", type="datetime", nullable=true)
     */
    private $prtCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="prt_updated_at", type="datetime", nullable=true)
     */
    private $prtUpdatedAt;


    /**
     * Get prtId
     *
     * @return integer
     */
    public function getPrtId()
    {
        return $this->prtId;
    }

    /**
     * Set prtUserLogin
     *
     * @param string $prtUserLogin
     *
     * @return PrtPrint
     */
    public function setPrtUserLogin($prtUserLogin)
    {
        $this->prtUserLogin = $prtUserLogin;

        return $this;
    }

    /**
     * Get prtUserLogin
     *
     * @return string
     */
    public function getPrtUserLogin()
    {
        return $this->prtUserLogin;
    }

    /**
     * Set prtFiche
     *
     * @param integer $prtFiche
     *
     * @return PrtPrint
     */
    public function setPrtFiche($prtFiche)
    {
        $this->prtFiche = $prtFiche;

        return $this;
    }

    /**
     * Get prtFiche
     *
     * @return integer
     */
    public function getPrtFiche()
    {
        return $this->prtFiche;
    }

    /**
     * Set prtIdSession
     *
     * @param string $prtIdSession
     *
     * @return PrtPrint
     */
    public function setPrtIdSession($prtIdSession)
    {
        $this->prtIdSession = $prtIdSession;

        return $this;
    }

    /**
     * Get prtIdSession
     *
     * @return string
     */
    public function getPrtIdSession()
    {
        return $this->prtIdSession;
    }

    /**
     * Set prtCreatedAt
     *
     * @param \DateTime $prtCreatedAt
     *
     * @return PrtPrint
     */
    public function setPrtCreatedAt($prtCreatedAt)
    {
        $this->prtCreatedAt = $prtCreatedAt;

        return $this;
    }

    /**
     * Get prtCreatedAt
     *
     * @return \DateTime
     */
    public function getPrtCreatedAt()
    {
        return $this->prtCreatedAt;
    }

    /**
     * Set prtUpdatedAt
     *
     * @param \DateTime $prtUpdatedAt
     *
     * @return PrtPrint
     */
    public function setPrtUpdatedAt($prtUpdatedAt)
    {
        $this->prtUpdatedAt = $prtUpdatedAt;

        return $this;
    }

    /**
     * Get prtUpdatedAt
     *
     * @return \DateTime
     */
    public function getPrtUpdatedAt()
    {
        return $this->prtUpdatedAt;
    }
}
