<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FdoFolderDoc
 *
 * @ORM\Table(
 *     name="fdo_folder_doc", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="fdo_id_fol_doc", columns={"fdo_id_folder", "fdo_id_doc"})
 *     }, indexes={
 *      @ORM\Index(name="fdo_id_doc", columns={"fdo_id_doc"}),
 *      @ORM\Index(name="IDX_4CB5268F2D9903C3", columns={"fdo_id_folder"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\FdoFolderDocRepository")
 */
class FdoFolderDoc
{
    /**
     * @var integer
     *
     * @ORM\Column(name="fdo_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fdoId;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fdo_created_at", type="datetime", nullable=true)
     */
    private $fdoCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fdo_updated_at", type="datetime", nullable=true)
     */
    private $fdoUpdatedAt;

    /**
     * @var FolFolder
     *
     * @ORM\ManyToOne(targetEntity="BfoBaseFolder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fdo_id_folder", referencedColumnName="fol_id", onDelete="CASCADE")
     * })
     */
    private $fdoFolder;

    /**
     * @var IfpIndexfichePaperless
     *
     * @ORM\ManyToOne(targetEntity="IfpIndexfichePaperless")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fdo_id_doc", referencedColumnName="ifp_id", onDelete="CASCADE")
     * })
     */
    private $fdoDoc;



    /**
     * Get fdoId
     *
     * @return integer
     */
    public function getFdoId()
    {
        return $this->fdoId;
    }

    /**
     * Set fdoCreatedAt
     *
     * @param \DateTime $fdoCreatedAt
     *
     * @return FdoFolderDoc
     */
    public function setFdoCreatedAt($fdoCreatedAt)
    {
        $this->fdoCreatedAt = $fdoCreatedAt;

        return $this;
    }

    /**
     * Get fdoCreatedAt
     *
     * @return \DateTime
     */
    public function getFdoCreatedAt()
    {
        return $this->fdoCreatedAt;
    }

    /**
     * Set fdoUpdatedAt
     *
     * @param \DateTime $fdoUpdatedAt
     *
     * @return FdoFolderDoc
     */
    public function setFdoUpdatedAt($fdoUpdatedAt)
    {
        $this->fdoUpdatedAt = $fdoUpdatedAt;

        return $this;
    }

    /**
     * Get fdoUpdatedAt
     *
     * @return \DateTime
     */
    public function getFdoUpdatedAt()
    {
        return $this->fdoUpdatedAt;
    }

    /**
     * Set fdoFolder
     *
     * @param \ApiBundle\Entity\FolFolder $fdoFolder
     *
     * @return FdoFolderDoc
     */
    public function setFdoFolder(BfoBaseFolder $fdoFolder = null)
    {
        $this->fdoFolder = $fdoFolder;

        return $this;
    }

    /**
     * Get fdoFolder
     *
     * @return \ApiBundle\Entity\FolFolder
     */
    public function getFdoFolder()
    {
        return $this->fdoFolder;
    }

    /**
     * Set fdoDoc
     *
     * @param \ApiBundle\Entity\IfpIndexfichePaperless $fdoDoc
     *
     * @return FdoFolderDoc
     */
    public function setFdoDoc(IfpIndexfichePaperless $fdoDoc = null)
    {
        $this->fdoDoc = $fdoDoc;

        return $this;
    }

    /**
     * Get fdoDoc
     *
     * @return \ApiBundle\Entity\IfpIndexfichePaperless
     */
    public function getFdoDoc()
    {
        return $this->fdoDoc;
    }
}
