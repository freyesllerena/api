<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * La0Langue0
 *
 * @ORM\Table(name="la0_langue0", uniqueConstraints={@ORM\UniqueConstraint(name="la0_variable", columns={"la0_variable"})}, indexes={@ORM\Index(name="la0_date", columns={"la0_created_at"})})
 * @ORM\Entity
 */
class La0Langue0
{
    /**
     * @var integer
     *
     * @ORM\Column(name="la0_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $la0Id;

    /**
     * @var string
     *
     * @ORM\Column(name="la0_variable", type="string", length=255, nullable=false)
     */
    private $la0Variable;

    /**
     * @var string
     *
     * @ORM\Column(name="la0_valeur", type="text", length=65535, nullable=false)
     */
    private $la0Valeur;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="la0_created_at", type="datetime", nullable=true)
     */
    private $la0CreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="la0_updated_at", type="datetime", nullable=true)
     */
    private $la0UpdatedAt;


    /**
     * Get la0Id
     *
     * @return integer
     */
    public function getLa0Id()
    {
        return $this->la0Id;
    }

    /**
     * Set la0Variable
     *
     * @param string $la0Variable
     *
     * @return La0Langue0
     */
    public function setLa0Variable($la0Variable)
    {
        $this->la0Variable = $la0Variable;

        return $this;
    }

    /**
     * Get la0Variable
     *
     * @return string
     */
    public function getLa0Variable()
    {
        return $this->la0Variable;
    }

    /**
     * Set la0Valeur
     *
     * @param string $la0Valeur
     *
     * @return La0Langue0
     */
    public function setLa0Valeur($la0Valeur)
    {
        $this->la0Valeur = $la0Valeur;

        return $this;
    }

    /**
     * Get la0Valeur
     *
     * @return string
     */
    public function getLa0Valeur()
    {
        return $this->la0Valeur;
    }

    /**
     * Set la0CreatedAt
     *
     * @param \DateTime $la0CreatedAt
     *
     * @return La0Langue0
     */
    public function setLa0CreatedAt($la0CreatedAt)
    {
        $this->la0CreatedAt = $la0CreatedAt;

        return $this;
    }

    /**
     * Get la0CreatedAt
     *
     * @return \DateTime
     */
    public function getLa0CreatedAt()
    {
        return $this->la0CreatedAt;
    }

    /**
     * Set la0UpdatedAt
     *
     * @param \DateTime $la0UpdatedAt
     *
     * @return La0Langue0
     */
    public function setLa0UpdatedAt($la0UpdatedAt)
    {
        $this->la0UpdatedAt = $la0UpdatedAt;

        return $this;
    }

    /**
     * Get la0UpdatedAt
     *
     * @return \DateTime
     */
    public function getLa0UpdatedAt()
    {
        return $this->la0UpdatedAt;
    }
}
