<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * La1Langue1
 *
 * @ORM\Table(name="la1_langue1", uniqueConstraints={@ORM\UniqueConstraint(name="la1_variable", columns={"la1_variable"})}, indexes={@ORM\Index(name="la1_date", columns={"la1_created_at"})})
 * @ORM\Entity
 */
class La1Langue1
{
    /**
     * @var integer
     *
     * @ORM\Column(name="la1_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $la1Id;

    /**
     * @var string
     *
     * @ORM\Column(name="la1_variable", type="string", length=255, nullable=false)
     */
    private $la1Variable;

    /**
     * @var string
     *
     * @ORM\Column(name="la1_valeur", type="text", length=65535, nullable=false)
     */
    private $la1Valeur;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="la1_created_at", type="datetime", nullable=true)
     */
    private $la1CreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="la1_updated_at", type="datetime", nullable=true)
     */
    private $la1UpdatedAt;


    /**
     * Get la1Id
     *
     * @return integer
     */
    public function getLa1Id()
    {
        return $this->la1Id;
    }

    /**
     * Set la1Variable
     *
     * @param string $la1Variable
     *
     * @return La1Langue1
     */
    public function setLa1Variable($la1Variable)
    {
        $this->la1Variable = $la1Variable;

        return $this;
    }

    /**
     * Get la1Variable
     *
     * @return string
     */
    public function getLa1Variable()
    {
        return $this->la1Variable;
    }

    /**
     * Set la1Valeur
     *
     * @param string $la1Valeur
     *
     * @return La1Langue1
     */
    public function setLa1Valeur($la1Valeur)
    {
        $this->la1Valeur = $la1Valeur;

        return $this;
    }

    /**
     * Get la1Valeur
     *
     * @return string
     */
    public function getLa1Valeur()
    {
        return $this->la1Valeur;
    }

    /**
     * Set la1CreatedAt
     *
     * @param \DateTime $la1CreatedAt
     *
     * @return La1Langue1
     */
    public function setLa1CreatedAt($la1CreatedAt)
    {
        $this->la1CreatedAt = $la1CreatedAt;

        return $this;
    }

    /**
     * Get la1CreatedAt
     *
     * @return \DateTime
     */
    public function getLa1CreatedAt()
    {
        return $this->la1CreatedAt;
    }

    /**
     * Set la1UpdatedAt
     *
     * @param \DateTime $la1UpdatedAt
     *
     * @return La1Langue1
     */
    public function setLa1UpdatedAt($la1UpdatedAt)
    {
        $this->la1UpdatedAt = $la1UpdatedAt;

        return $this;
    }

    /**
     * Get la1UpdatedAt
     *
     * @return \DateTime
     */
    public function getLa1UpdatedAt()
    {
        return $this->la1UpdatedAt;
    }
}
