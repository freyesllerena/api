<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ConConfig
 *
 * @ORM\Table(name="con_config", uniqueConstraints={@ORM\UniqueConstraint(name="con_variable", columns={"con_variable"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ConConfigRepository")
 */
class ConConfig
{
    /**
     * @var integer
     *
     * @ORM\Column(name="con_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $conId;

    /**
     * @var string
     *
     * @ORM\Column(name="con_variable", type="string", length=100, nullable=false)
     */
    private $conVariable;

    /**
     * @var string
     *
     * @ORM\Column(name="con_valeur", type="string", length=100, nullable=false)
     */
    private $conValeur;

    /**
     * @var string
     *
     * @ORM\Column(name="con_label", type="string", length=100, nullable=false)
     */
    private $conLabel;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="con_created_at", type="datetime", nullable=true)
     */
    private $conCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="con_updated_at", type="datetime", nullable=true)
     */
    private $conUpdatedAt;


    /**
     * Get conId
     *
     * @return integer
     */
    public function getConId()
    {
        return $this->conId;
    }

    /**
     * Set conVariable
     *
     * @param string $conVariable
     *
     * @return ConConfig
     */
    public function setConVariable($conVariable)
    {
        $this->conVariable = $conVariable;

        return $this;
    }

    /**
     * Get conVariable
     *
     * @return string
     */
    public function getConVariable()
    {
        return $this->conVariable;
    }

    /**
     * Set conValeur
     *
     * @param string $conValeur
     *
     * @return ConConfig
     */
    public function setConValeur($conValeur)
    {
        $this->conValeur = $conValeur;

        return $this;
    }

    /**
     * Get conValeur
     *
     * @return string
     */
    public function getConValeur()
    {
        return $this->conValeur;
    }

    /**
     * Set conLabel
     *
     * @param string $conLabel
     *
     * @return ConConfig
     */
    public function setConLabel($conLabel)
    {
        $this->conLabel = $conLabel;

        return $this;
    }

    /**
     * Get conLabel
     *
     * @return string
     */
    public function getConLabel()
    {
        return $this->conLabel;
    }

    /**
     * Set conCreatedAt
     *
     * @param \DateTime $conCreatedAt
     *
     * @return ConConfig
     */
    public function setConCreatedAt($conCreatedAt)
    {
        $this->conCreatedAt = $conCreatedAt;

        return $this;
    }

    /**
     * Get conCreatedAt
     *
     * @return \DateTime
     */
    public function getConCreatedAt()
    {
        return $this->conCreatedAt;
    }

    /**
     * Set conUpdatedAt
     *
     * @param \DateTime $conUpdatedAt
     *
     * @return ConConfig
     */
    public function setConUpdatedAt($conUpdatedAt)
    {
        $this->conUpdatedAt = $conUpdatedAt;

        return $this;
    }

    /**
     * Get conUpdatedAt
     *
     * @return \DateTime
     */
    public function getConUpdatedAt()
    {
        return $this->conUpdatedAt;
    }
}
