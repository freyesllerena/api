<?php
namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BfoBaseFolder
 *
 * @ORM\Table(name="fol_folder")
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="fol_type", type="string")
 * @ORM\DiscriminatorMap({"FOL" = "FolFolder", "BAS" = "BasBasket"})
 */
abstract class BfoBaseFolder
{
    const DEFAULT_FOL_NBDOC = 0;

    const ERR_DOES_NOT_EXIST = 'errFolFolderDoNotExist';
    const ERR_NOT_OWNER = 'errFolFolderNotOwner';
    const ERR_OWNER_FOLDER_LABEL_EXISTS = 'errFolFolderOwnerFolderLabelExists';

    /**
     * @var integer
     *
     * @ORM\Column(name="fol_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $folId;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "errFolFolderLabelEmpty")
     * @Assert\Length(max = "100", maxMessage = "errFolFolderLabelMaxCharacters")
     *
     * @ORM\Column(name="fol_libelle", type="string", length=100, nullable=false)
     */
    private $folLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="fol_id_owner", type="string", length=50, nullable=false)
     */
    private $folIdOwner;

    /**
     * @var integer
     *
     * @ORM\Column(name="fol_nb_doc", type="integer", nullable=false)
     */
    private $folNbDoc = self::DEFAULT_FOL_NBDOC;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fol_created_at", type="datetime", nullable=true)
     */
    private $folCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fol_updated_at", type="datetime", nullable=true)
     */
    private $folUpdatedAt;



    /**
     * Get folId
     *
     * @return integer
     */
    public function getFolId()
    {
        return $this->folId;
    }

    /**
     * Set folLibelle
     *
     * @param string $folLibelle
     *
     * @return FolFolder
     */
    public function setFolLibelle($folLibelle)
    {
        $this->folLibelle = $folLibelle;

        return $this;
    }

    /**
     * Get folLibelle
     *
     * @return string
     */
    public function getFolLibelle()
    {
        return $this->folLibelle;
    }

    /**
     * Set folIdOwner
     *
     * @param string $folIdOwner
     *
     * @return FolFolder
     */
    public function setFolIdOwner($folIdOwner)
    {
        $this->folIdOwner = $folIdOwner;

        return $this;
    }

    /**
     * Get folIdOwner
     *
     * @return string
     */
    public function getFolIdOwner()
    {
        return $this->folIdOwner;
    }

    /**
     * Set folNbDoc
     *
     * @param integer $folNbDoc
     *
     * @return FolFolder
     */
    public function setFolNbDoc($folNbDoc)
    {
        $this->folNbDoc = $folNbDoc;

        return $this;
    }

    /**
     * Get folNbDoc
     *
     * @return integer
     */
    public function getFolNbDoc()
    {
        return $this->folNbDoc;
    }

    /**
     * Set folCreatedAt
     *
     * @param \DateTime $folCreatedAt
     *
     * @return FolFolder
     */
    public function setFolCreatedAt($folCreatedAt)
    {
        $this->folCreatedAt = $folCreatedAt;

        return $this;
    }

    /**
     * Get folCreatedAt
     *
     * @return \DateTime
     */
    public function getFolCreatedAt()
    {
        return $this->folCreatedAt;
    }

    /**
     * Set folUpdatedAt
     *
     * @param \DateTime $folUpdatedAt
     *
     * @return FolFolder
     */
    public function setFolUpdatedAt($folUpdatedAt)
    {
        $this->folUpdatedAt = $folUpdatedAt;

        return $this;
    }

    /**
     * Get folUpdatedAt
     *
     * @return \DateTime
     */
    public function getFolUpdatedAt()
    {
        return $this->folUpdatedAt;
    }

    /**
     * Get folType
     *
     * @return string
     */
    abstract public function getFolType();
}
