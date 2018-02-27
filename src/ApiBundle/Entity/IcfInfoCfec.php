<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IcfInfoCfec
 *
 * @ORM\Table(name="icf_info_cfec", uniqueConstraints={@ORM\UniqueConstraint(name="icf_id_alias", columns={"icf_id_alias"})})
 * @ORM\Entity
 */
class IcfInfoCfec
{
    /**
     * @var integer
     *
     * @ORM\Column(name="icf_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $icfId;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_id_alias", type="string", length=100, nullable=false)
     */
    private $icfIdAlias;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_cfec_base_principal", type="string", length=255, nullable=false)
     */
    private $icfCfecBasePrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_cfec_cert_principal", type="string", length=255, nullable=false)
     */
    private $icfCfecCertPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_cfec_numero_cfe_principal", type="string", length=10, nullable=false)
     */
    private $icfCfecNumeroCfePrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_cfec_numero_cfec_principal", type="string", length=10, nullable=false)
     */
    private $icfCfecNumeroCfecPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_cfec_base_secondaire", type="string", length=255, nullable=false)
     */
    private $icfCfecBaseSecondaire;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_cfec_cert_secondaire", type="string", length=255, nullable=false)
     */
    private $icfCfecCertSecondaire;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_cfec_numero_cfec_secondaire", type="string", length=10, nullable=false)
     */
    private $icfCfecNumeroCfecSecondaire;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_cfec_numero_cfe_secondaire", type="string", length=10, nullable=false)
     */
    private $icfCfecNumeroCfeSecondaire;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_ci_url_principal", type="string", length=255, nullable=false)
     */
    private $icfCiUrlPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_ci_rep_transfer_principal", type="string", length=255, nullable=false)
     */
    private $icfCiRepTransferPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_ci_rep_cr_principal", type="string", length=255, nullable=false)
     */
    private $icfCiRepCrPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_ci_url_secondaire", type="string", length=255, nullable=false)
     */
    private $icfCiUrlSecondaire;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_ci_rep_transfer_secondaire", type="string", length=255, nullable=false)
     */
    private $icfCiRepTransferSecondaire;

    /**
     * @var string
     *
     * @ORM\Column(name="icf_ci_rep_cr_secondaire", type="string", length=255, nullable=false)
     */
    private $icfCiRepCrSecondaire;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="icf_created_at", type="datetime", nullable=true)
     */
    private $icfCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="icf_updated_at", type="datetime", nullable=true)
     */
    private $icfUpdatedAt;


    /**
     * Get icfId
     *
     * @return integer
     */
    public function getIcfId()
    {
        return $this->icfId;
    }

    /**
     * Set icfIdAlias
     *
     * @param string $icfIdAlias
     *
     * @return IcfInfoCfec
     */
    public function setIcfIdAlias($icfIdAlias)
    {
        $this->icfIdAlias = $icfIdAlias;

        return $this;
    }

    /**
     * Get icfIdAlias
     *
     * @return string
     */
    public function getIcfIdAlias()
    {
        return $this->icfIdAlias;
    }

    /**
     * Set icfCfecBasePrincipal
     *
     * @param string $icfCfecBasePrincipal
     *
     * @return IcfInfoCfec
     */
    public function setIcfCfecBasePrincipal($icfCfecBasePrincipal)
    {
        $this->icfCfecBasePrincipal = $icfCfecBasePrincipal;

        return $this;
    }

    /**
     * Get icfCfecBasePrincipal
     *
     * @return string
     */
    public function getIcfCfecBasePrincipal()
    {
        return $this->icfCfecBasePrincipal;
    }

    /**
     * Set icfCfecCertPrincipal
     *
     * @param string $icfCfecCertPrincipal
     *
     * @return IcfInfoCfec
     */
    public function setIcfCfecCertPrincipal($icfCfecCertPrincipal)
    {
        $this->icfCfecCertPrincipal = $icfCfecCertPrincipal;

        return $this;
    }

    /**
     * Get icfCfecCertPrincipal
     *
     * @return string
     */
    public function getIcfCfecCertPrincipal()
    {
        return $this->icfCfecCertPrincipal;
    }

    /**
     * Set icfCfecNumeroCfePrincipal
     *
     * @param string $icfCfecNumeroCfePrincipal
     *
     * @return IcfInfoCfec
     */
    public function setIcfCfecNumeroCfePrincipal($icfCfecNumeroCfePrincipal)
    {
        $this->icfCfecNumeroCfePrincipal = $icfCfecNumeroCfePrincipal;

        return $this;
    }

    /**
     * Get icfCfecNumeroCfePrincipal
     *
     * @return string
     */
    public function getIcfCfecNumeroCfePrincipal()
    {
        return $this->icfCfecNumeroCfePrincipal;
    }

    /**
     * Set icfCfecNumeroCfecPrincipal
     *
     * @param string $icfCfecNumeroCfecPrincipal
     *
     * @return IcfInfoCfec
     */
    public function setIcfCfecNumeroCfecPrincipal($icfCfecNumeroCfecPrincipal)
    {
        $this->icfCfecNumeroCfecPrincipal = $icfCfecNumeroCfecPrincipal;

        return $this;
    }

    /**
     * Get icfCfecNumeroCfecPrincipal
     *
     * @return string
     */
    public function getIcfCfecNumeroCfecPrincipal()
    {
        return $this->icfCfecNumeroCfecPrincipal;
    }

    /**
     * Set icfCfecBaseSecondaire
     *
     * @param string $icfCfecBaseSecondaire
     *
     * @return IcfInfoCfec
     */
    public function setIcfCfecBaseSecondaire($icfCfecBaseSecondaire)
    {
        $this->icfCfecBaseSecondaire = $icfCfecBaseSecondaire;

        return $this;
    }

    /**
     * Get icfCfecBaseSecondaire
     *
     * @return string
     */
    public function getIcfCfecBaseSecondaire()
    {
        return $this->icfCfecBaseSecondaire;
    }

    /**
     * Set icfCfecCertSecondaire
     *
     * @param string $icfCfecCertSecondaire
     *
     * @return IcfInfoCfec
     */
    public function setIcfCfecCertSecondaire($icfCfecCertSecondaire)
    {
        $this->icfCfecCertSecondaire = $icfCfecCertSecondaire;

        return $this;
    }

    /**
     * Get icfCfecCertSecondaire
     *
     * @return string
     */
    public function getIcfCfecCertSecondaire()
    {
        return $this->icfCfecCertSecondaire;
    }

    /**
     * Set icfCfecNumeroCfecSecondaire
     *
     * @param string $icfCfecNumeroCfecSecondaire
     *
     * @return IcfInfoCfec
     */
    public function setIcfCfecNumeroCfecSecondaire($icfCfecNumeroCfecSecondaire)
    {
        $this->icfCfecNumeroCfecSecondaire = $icfCfecNumeroCfecSecondaire;

        return $this;
    }

    /**
     * Get icfCfecNumeroCfecSecondaire
     *
     * @return string
     */
    public function getIcfCfecNumeroCfecSecondaire()
    {
        return $this->icfCfecNumeroCfecSecondaire;
    }

    /**
     * Set icfCfecNumeroCfeSecondaire
     *
     * @param string $icfCfecNumeroCfeSecondaire
     *
     * @return IcfInfoCfec
     */
    public function setIcfCfecNumeroCfeSecondaire($icfCfecNumeroCfeSecondaire)
    {
        $this->icfCfecNumeroCfeSecondaire = $icfCfecNumeroCfeSecondaire;

        return $this;
    }

    /**
     * Get icfCfecNumeroCfeSecondaire
     *
     * @return string
     */
    public function getIcfCfecNumeroCfeSecondaire()
    {
        return $this->icfCfecNumeroCfeSecondaire;
    }

    /**
     * Set icfCiUrlPrincipal
     *
     * @param string $icfCiUrlPrincipal
     *
     * @return IcfInfoCfec
     */
    public function setIcfCiUrlPrincipal($icfCiUrlPrincipal)
    {
        $this->icfCiUrlPrincipal = $icfCiUrlPrincipal;

        return $this;
    }

    /**
     * Get icfCiUrlPrincipal
     *
     * @return string
     */
    public function getIcfCiUrlPrincipal()
    {
        return $this->icfCiUrlPrincipal;
    }

    /**
     * Set icfCiRepTransferPrincipal
     *
     * @param string $icfCiRepTransferPrincipal
     *
     * @return IcfInfoCfec
     */
    public function setIcfCiRepTransferPrincipal($icfCiRepTransferPrincipal)
    {
        $this->icfCiRepTransferPrincipal = $icfCiRepTransferPrincipal;

        return $this;
    }

    /**
     * Get icfCiRepTransferPrincipal
     *
     * @return string
     */
    public function getIcfCiRepTransferPrincipal()
    {
        return $this->icfCiRepTransferPrincipal;
    }

    /**
     * Set icfCiRepCrPrincipal
     *
     * @param string $icfCiRepCrPrincipal
     *
     * @return IcfInfoCfec
     */
    public function setIcfCiRepCrPrincipal($icfCiRepCrPrincipal)
    {
        $this->icfCiRepCrPrincipal = $icfCiRepCrPrincipal;

        return $this;
    }

    /**
     * Get icfCiRepCrPrincipal
     *
     * @return string
     */
    public function getIcfCiRepCrPrincipal()
    {
        return $this->icfCiRepCrPrincipal;
    }

    /**
     * Set icfCiUrlSecondaire
     *
     * @param string $icfCiUrlSecondaire
     *
     * @return IcfInfoCfec
     */
    public function setIcfCiUrlSecondaire($icfCiUrlSecondaire)
    {
        $this->icfCiUrlSecondaire = $icfCiUrlSecondaire;

        return $this;
    }

    /**
     * Get icfCiUrlSecondaire
     *
     * @return string
     */
    public function getIcfCiUrlSecondaire()
    {
        return $this->icfCiUrlSecondaire;
    }

    /**
     * Set icfCiRepTransferSecondaire
     *
     * @param string $icfCiRepTransferSecondaire
     *
     * @return IcfInfoCfec
     */
    public function setIcfCiRepTransferSecondaire($icfCiRepTransferSecondaire)
    {
        $this->icfCiRepTransferSecondaire = $icfCiRepTransferSecondaire;

        return $this;
    }

    /**
     * Get icfCiRepTransferSecondaire
     *
     * @return string
     */
    public function getIcfCiRepTransferSecondaire()
    {
        return $this->icfCiRepTransferSecondaire;
    }

    /**
     * Set icfCiRepCrSecondaire
     *
     * @param string $icfCiRepCrSecondaire
     *
     * @return IcfInfoCfec
     */
    public function setIcfCiRepCrSecondaire($icfCiRepCrSecondaire)
    {
        $this->icfCiRepCrSecondaire = $icfCiRepCrSecondaire;

        return $this;
    }

    /**
     * Get icfCiRepCrSecondaire
     *
     * @return string
     */
    public function getIcfCiRepCrSecondaire()
    {
        return $this->icfCiRepCrSecondaire;
    }

    /**
     * Set icfCreatedAt
     *
     * @param \DateTime $icfCreatedAt
     *
     * @return IcfInfoCfec
     */
    public function setIcfCreatedAt($icfCreatedAt)
    {
        $this->icfCreatedAt = $icfCreatedAt;

        return $this;
    }

    /**
     * Get icfCreatedAt
     *
     * @return \DateTime
     */
    public function getIcfCreatedAt()
    {
        return $this->icfCreatedAt;
    }

    /**
     * Set icfUpdatedAt
     *
     * @param \DateTime $icfUpdatedAt
     *
     * @return IcfInfoCfec
     */
    public function setIcfUpdatedAt($icfUpdatedAt)
    {
        $this->icfUpdatedAt = $icfUpdatedAt;

        return $this;
    }

    /**
     * Get icfUpdatedAt
     *
     * @return \DateTime
     */
    public function getIcfUpdatedAt()
    {
        return $this->icfUpdatedAt;
    }
}
