<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * IucInteruploadCfg
 *
 * @ORM\Table(
 *     name="iuc_interupload_cfg",
 *     uniqueConstraints={@ORM\UniqueConstraint(
 *     name="iuc_id_interupload",
 *     columns={"iuc_id_interupload"
 * })})
 * @ORM\Entity
 */
class IucInteruploadCfg
{
    const DEFAULT_IUC_VERSIONAPPLET = '0.27';
    const DEFAULT_IUC_ID_UPLOAD = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="iuc_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iucId;

    /**
     * @var string
     *
     * @ORM\Column(name="iuc_id_interupload", type="string", length=100, nullable=false)
     */
    private $iucIdInterupload;

    /**
     * @var string
     *
     * @ORM\Column(name="iuc_codeClient", type="string", length=100, nullable=false)
     */
    private $iucCodeclient;

    /**
     * @var string
     *
     * @ORM\Column(name="iuc_codeAppli", type="string", length=100, nullable=false)
     */
    private $iucCodeappli;

    /**
     * @var string
     *
     * @ORM\Column(name="iuc_param", type="string", length=100, nullable=false)
     */
    private $iucParam;

    /**
     * @var string
     *
     * @ORM\Column(name="iuc_config", type="text", length=65535, nullable=false)
     */
    private $iucConfig;

    /**
     * @var string
     *
     * @ORM\Column(name="iuc_script_archivage_specifique", type="string", length=100, nullable=false)
     */
    private $iucScriptArchivageSpecifique;

    /**
     * @var string
     *
     * @ORM\Column(name="iuc_interuploadWeb", type="string", length=255, nullable=false)
     */
    private $iucInteruploadweb;

    /**
     * @var string
     *
     * @ORM\Column(name="iuc_versionApplet", type="string", length=10, nullable=false)
     */
    private $iucVersionapplet = self::DEFAULT_IUC_VERSIONAPPLET;

    /**
     * @var integer
     *
     * @ORM\Column(name="iuc_id_upload", type="integer", nullable=false)
     */
    private $iucIdUpload = self::DEFAULT_IUC_ID_UPLOAD;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="iuc_created_at", type="datetime", nullable=true)
     */
    private $iucCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="iuc_updated_at", type="datetime", nullable=true)
     */
    private $iucUpdatedAt;


    /**
     * Get iucId
     *
     * @return integer
     */
    public function getIucId()
    {
        return $this->iucId;
    }

    /**
     * Set iucIdInterupload
     *
     * @param string $iucIdInterupload
     *
     * @return IucInteruploadCfg
     */
    public function setIucIdInterupload($iucIdInterupload)
    {
        $this->iucIdInterupload = $iucIdInterupload;

        return $this;
    }

    /**
     * Get iucIdInterupload
     *
     * @return string
     */
    public function getIucIdInterupload()
    {
        return $this->iucIdInterupload;
    }

    /**
     * Set iucCodeclient
     *
     * @param string $iucCodeclient
     *
     * @return IucInteruploadCfg
     */
    public function setIucCodeclient($iucCodeclient)
    {
        $this->iucCodeclient = $iucCodeclient;

        return $this;
    }

    /**
     * Get iucCodeclient
     *
     * @return string
     */
    public function getIucCodeclient()
    {
        return $this->iucCodeclient;
    }

    /**
     * Set iucCodeappli
     *
     * @param string $iucCodeappli
     *
     * @return IucInteruploadCfg
     */
    public function setIucCodeappli($iucCodeappli)
    {
        $this->iucCodeappli = $iucCodeappli;

        return $this;
    }

    /**
     * Get iucCodeappli
     *
     * @return string
     */
    public function getIucCodeappli()
    {
        return $this->iucCodeappli;
    }

    /**
     * Set iucParam
     *
     * @param string $iucParam
     *
     * @return IucInteruploadCfg
     */
    public function setIucParam($iucParam)
    {
        $this->iucParam = $iucParam;

        return $this;
    }

    /**
     * Get iucParam
     *
     * @return string
     */
    public function getIucParam()
    {
        return $this->iucParam;
    }

    /**
     * Set iucConfig
     *
     * @param string $iucConfig
     *
     * @return IucInteruploadCfg
     */
    public function setIucConfig($iucConfig)
    {
        $this->iucConfig = $iucConfig;

        return $this;
    }

    /**
     * Get iucConfig
     *
     * @return string
     */
    public function getIucConfig()
    {
        return $this->iucConfig;
    }

    /**
     * Set iucScriptArchivageSpecifique
     *
     * @param string $iucScriptArchivageSpecifique
     *
     * @return IucInteruploadCfg
     */
    public function setIucScriptArchivageSpecifique($iucScriptArchivageSpecifique)
    {
        $this->iucScriptArchivageSpecifique = $iucScriptArchivageSpecifique;

        return $this;
    }

    /**
     * Get iucScriptArchivageSpecifique
     *
     * @return string
     */
    public function getIucScriptArchivageSpecifique()
    {
        return $this->iucScriptArchivageSpecifique;
    }

    /**
     * Set iucInteruploadweb
     *
     * @param string $iucInteruploadweb
     *
     * @return IucInteruploadCfg
     */
    public function setIucInteruploadweb($iucInteruploadweb)
    {
        $this->iucInteruploadweb = $iucInteruploadweb;

        return $this;
    }

    /**
     * Get iucInteruploadweb
     *
     * @return string
     */
    public function getIucInteruploadweb()
    {
        return $this->iucInteruploadweb;
    }

    /**
     * Set iucVersionapplet
     *
     * @param string $iucVersionapplet
     *
     * @return IucInteruploadCfg
     */
    public function setIucVersionapplet($iucVersionapplet)
    {
        $this->iucVersionapplet = $iucVersionapplet;

        return $this;
    }

    /**
     * Get iucVersionapplet
     *
     * @return string
     */
    public function getIucVersionapplet()
    {
        return $this->iucVersionapplet;
    }

    /**
     * Set iucIdUpload
     *
     * @param integer $iucIdUpload
     *
     * @return IucInteruploadCfg
     */
    public function setIucIdUpload($iucIdUpload)
    {
        $this->iucIdUpload = $iucIdUpload;

        return $this;
    }

    /**
     * Get iucIdUpload
     *
     * @return integer
     */
    public function getIucIdUpload()
    {
        return $this->iucIdUpload;
    }

    /**
     * Set iucCreatedAt
     *
     * @param \DateTime $iucCreatedAt
     *
     * @return IucInteruploadCfg
     */
    public function setIucCreatedAt($iucCreatedAt)
    {
        $this->iucCreatedAt = $iucCreatedAt;

        return $this;
    }

    /**
     * Get iucCreatedAt
     *
     * @return \DateTime
     */
    public function getIucCreatedAt()
    {
        return $this->iucCreatedAt;
    }

    /**
     * Set iucUpdatedAt
     *
     * @param \DateTime $iucUpdatedAt
     *
     * @return IucInteruploadCfg
     */
    public function setIucUpdatedAt($iucUpdatedAt)
    {
        $this->iucUpdatedAt = $iucUpdatedAt;

        return $this;
    }

    /**
     * Get iucUpdatedAt
     *
     * @return \DateTime
     */
    public function getIucUpdatedAt()
    {
        return $this->iucUpdatedAt;
    }
}
