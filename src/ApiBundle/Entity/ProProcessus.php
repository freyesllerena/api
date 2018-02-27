<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProProcessus
 *
 * @ORM\Table(
 *     name="pro_processus", indexes={
 *      @ORM\Index(name="fk_pro_usr", columns={"pro_id_user"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ProProcessusRepository")
 */
class ProProcessus
{
    const ERR_DOES_NOT_EXIST = 'errProProcessusDoesNotExist';
    const ERR_NOT_OWNER = 'errProProcessusNotOwner';
    const ERR_OWNER_PROCESSUS_LABEL_EXISTS = 'errProProcessusLabelExists';

    const DEFAULT_PRO_EDITABLE = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="pro_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $proId;

    /**
     * @var integer
     *
     * @ORM\Column(name="pro_groupe", type="smallint", nullable=false)
     */
    private $proGroupe;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "errProProcessusLabelEmpty")
     * @Assert\Length(
     *     max = "100",
     *     maxMessage = "errProProcessusLabelMaxCharacters"
     * )
     *
     * @ORM\Column(name="pro_libelle", type="string", length=100, nullable=true)
     */
    private $proLibelle;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="pro_created_at", type="datetime", nullable=true)
     */
    private $proCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="pro_updated_at", type="datetime", nullable=true)
     */
    private $proUpdatedAt;

    /**
     * @var UsrUsers
     *
     * @ORM\ManyToOne(targetEntity="UsrUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pro_id_user", referencedColumnName="usr_login")
     * })
     */
    private $proUser;


    /**
     * Get proId
     *
     * @return integer
     */
    public function getProId()
    {
        return $this->proId;
    }

    /**
     * Set proGroupe
     *
     * @param integer $proGroupe
     *
     * @return ProProcessus
     */
    public function setProGroupe($proGroupe)
    {
        $this->proGroupe = $proGroupe;

        return $this;
    }

    /**
     * Get proGroupe
     *
     * @return integer
     */
    public function getProGroupe()
    {
        return $this->proGroupe;
    }

    /**
     * Set proLibelle
     *
     * @param string $proLibelle
     *
     * @return ProProcessus
     */
    public function setProLibelle($proLibelle)
    {
        $this->proLibelle = $proLibelle;

        return $this;
    }

    /**
     * Get proLibelle
     *
     * @return string
     */
    public function getProLibelle()
    {
        return $this->proLibelle;
    }

    /**
     * Set proCreatedAt
     *
     * @param \DateTime $proCreatedAt
     *
     * @return ProProcessus
     */
    public function setProCreatedAt($proCreatedAt)
    {
        $this->proCreatedAt = $proCreatedAt;

        return $this;
    }

    /**
     * Get proCreatedAt
     *
     * @return \DateTime
     */
    public function getProCreatedAt()
    {
        return $this->proCreatedAt;
    }

    /**
     * Set proUpdatedAt
     *
     * @param \DateTime $proUpdatedAt
     *
     * @return ProProcessus
     */
    public function setProUpdatedAt($proUpdatedAt)
    {
        $this->proUpdatedAt = $proUpdatedAt;

        return $this;
    }

    /**
     * Get proUpdatedAt
     *
     * @return \DateTime
     */
    public function getProUpdatedAt()
    {
        return $this->proUpdatedAt;
    }

    /**
     * Set proUser
     *
     * @param \ApiBundle\Entity\UsrUsers $proUser
     *
     * @return ProProcessus
     */
    public function setProUser(UsrUsers $proUser = null)
    {
        $this->proUser = $proUser;

        return $this;
    }

    /**
     * Get proUser
     *
     * @return \ApiBundle\Entity\UsrUsers
     */
    public function getProUser()
    {
        return $this->proUser;
    }
}
