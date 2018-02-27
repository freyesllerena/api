<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiBundle\Enum\EnumLabelStockageType;

/**
 * LasLaserlike
 *
 * @ORM\Table(name="las_laserlike", indexes={@ORM\Index(name="las_debut", columns={"las_debut"}), @ORM\Index(name="las_fin", columns={"las_fin"}), @ORM\Index(name="las_id_application", columns={"las_id_application"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\LasLaserlikeRepository")
 */
class LasLaserlike
{
    /**
     * @var integer
     *
     * @ORM\Column(name="las_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $lasId;

    /**
     * @var string
     *
     * @ORM\Column(name="las_debut", type="string", length=20, nullable=true)
     */
    private $lasDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="las_fin", type="string", length=20, nullable=true)
     */
    private $lasFin;

    /**
     * @var string
     *
     * @ORM\Column(name="las_chemin", type="string", length=255, nullable=true)
     */
    private $lasChemin;

    /**
     * @var string
     *
     * @ORM\Column(name="las_libelle", type="string", length=255, nullable=true)
     */
    private $lasLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="las_id_application", type="string", length=100, nullable=false)
     */
    private $lasIdApplication;

    /**
     * @var string
     *
     * @ORM\Column(name="las_type", type="string", length=10, nullable=false)
     */
    private $lasType = EnumLabelStockageType::STANDARD_STOCKAGE;

    /**
     * @var string
     *
     * @ORM\Column(name="las_cfec_base", type="text", length=65535, nullable=false)
     */
    private $lasCfecBase;

    /**
     * @var string
     *
     * @ORM\Column(name="las_cfec_cert", type="text", length=65535, nullable=false)
     */
    private $lasCfecCert;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="las_created_at", type="datetime", nullable=true)
     */
    private $lasCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="las_updated_at", type="datetime", nullable=true)
     */
    private $lasUpdatedAt;


    /**
     * Get lasId
     *
     * @return integer
     */
    public function getLasId()
    {
        return $this->lasId;
    }

    /**
     * Set lasDebut
     *
     * @param string $lasDebut
     *
     * @return LasLaserlike
     */
    public function setLasDebut($lasDebut)
    {
        $this->lasDebut = $lasDebut;

        return $this;
    }

    /**
     * Get lasDebut
     *
     * @return string
     */
    public function getLasDebut()
    {
        return $this->lasDebut;
    }

    /**
     * Set lasFin
     *
     * @param string $lasFin
     *
     * @return LasLaserlike
     */
    public function setLasFin($lasFin)
    {
        $this->lasFin = $lasFin;

        return $this;
    }

    /**
     * Get lasFin
     *
     * @return string
     */
    public function getLasFin()
    {
        return $this->lasFin;
    }

    /**
     * Set lasChemin
     *
     * @param string $lasChemin
     *
     * @return LasLaserlike
     */
    public function setLasChemin($lasChemin)
    {
        $this->lasChemin = $lasChemin;

        return $this;
    }

    /**
     * Get lasChemin
     *
     * @return string
     */
    public function getLasChemin()
    {
        return $this->lasChemin;
    }

    /**
     * Set lasLibelle
     *
     * @param string $lasLibelle
     *
     * @return LasLaserlike
     */
    public function setLasLibelle($lasLibelle)
    {
        $this->lasLibelle = $lasLibelle;

        return $this;
    }

    /**
     * Get lasLibelle
     *
     * @return string
     */
    public function getLasLibelle()
    {
        return $this->lasLibelle;
    }

    /**
     * Set lasIdApplication
     *
     * @param string $lasIdApplication
     *
     * @return LasLaserlike
     */
    public function setLasIdApplication($lasIdApplication)
    {
        $this->lasIdApplication = $lasIdApplication;

        return $this;
    }

    /**
     * Get lasIdApplication
     *
     * @return string
     */
    public function getLasIdApplication()
    {
        return $this->lasIdApplication;
    }

    /**
     * Set lasType
     *
     * @param string $lasType
     *
     * @return LasLaserlike
     */
    public function setLasType($lasType)
    {
        $this->lasType = $lasType;

        return $this;
    }

    /**
     * Get lasType
     *
     * @return string
     */
    public function getLasType()
    {
        return $this->lasType;
    }

    /**
     * Set lasCfecBase
     *
     * @param string $lasCfecBase
     *
     * @return LasLaserlike
     */
    public function setLasCfecBase($lasCfecBase)
    {
        $this->lasCfecBase = $lasCfecBase;

        return $this;
    }

    /**
     * Get lasCfecBase
     *
     * @return string
     */
    public function getLasCfecBase()
    {
        return $this->lasCfecBase;
    }

    /**
     * Set lasCfecCert
     *
     * @param string $lasCfecCert
     *
     * @return LasLaserlike
     */
    public function setLasCfecCert($lasCfecCert)
    {
        $this->lasCfecCert = $lasCfecCert;

        return $this;
    }

    /**
     * Get lasCfecCert
     *
     * @return string
     */
    public function getLasCfecCert()
    {
        return $this->lasCfecCert;
    }

    /**
     * Set lasCreatedAt
     *
     * @param \DateTime $lasCreatedAt
     *
     * @return LasLaserlike
     */
    public function setLasCreatedAt($lasCreatedAt)
    {
        $this->lasCreatedAt = $lasCreatedAt;

        return $this;
    }

    /**
     * Get lasCreatedAt
     *
     * @return \DateTime
     */
    public function getLasCreatedAt()
    {
        return $this->lasCreatedAt;
    }

    /**
     * Set lasUpdatedAt
     *
     * @param \DateTime $lasUpdatedAt
     *
     * @return LasLaserlike
     */
    public function setLasUpdatedAt($lasUpdatedAt)
    {
        $this->lasUpdatedAt = $lasUpdatedAt;

        return $this;
    }

    /**
     * Get lasUpdatedAt
     *
     * @return \DateTime
     */
    public function getLasUpdatedAt()
    {
        return $this->lasUpdatedAt;
    }
}
