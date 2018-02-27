<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CdvCycleDeVie
 *
 * @ORM\Table(name="cdv_cycle_de_vie", uniqueConstraints={@ORM\UniqueConstraint(name="cdv_yyss", columns={"cdv_yyss"})})
 * @ORM\Entity
 */
class CdvCycleDeVie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cdv_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cdvId;

    /**
     * @var string
     *
     * @ORM\Column(name="cdv_yyss", type="string", length=4, nullable=true)
     */
    private $cdvYyss;

    /**
     * @var integer
     *
     * @ORM\Column(name="cdv_nb_doc_indiv", type="smallint", nullable=true)
     */
    private $cdvNbDocIndiv;

    /**
     * @var integer
     *
     * @ORM\Column(name="cdv_nb_doc_collect", type="smallint", nullable=true)
     */
    private $cdvNbDocCollect;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="cdv_created_at", type="datetime", nullable=true)
     */
    private $cdvCreatedAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="cdv_updated_at", type="datetime", nullable=true)
     */
    private $cdvUpdatedAt;



    /**
     * Get cdvId
     *
     * @return integer
     */
    public function getCdvId()
    {
        return $this->cdvId;
    }

    /**
     * Set cdvYyss
     *
     * @param string $cdvYyss
     *
     * @return CdvCycleDeVie
     */
    public function setCdvYyss($cdvYyss)
    {
        $this->cdvYyss = $cdvYyss;

        return $this;
    }

    /**
     * Get cdvYyss
     *
     * @return string
     */
    public function getCdvYyss()
    {
        return $this->cdvYyss;
    }

    /**
     * Set cdvNbDocIndiv
     *
     * @param integer $cdvNbDocIndiv
     *
     * @return CdvCycleDeVie
     */
    public function setCdvNbDocIndiv($cdvNbDocIndiv)
    {
        $this->cdvNbDocIndiv = $cdvNbDocIndiv;

        return $this;
    }

    /**
     * Get cdvNbDocIndiv
     *
     * @return integer
     */
    public function getCdvNbDocIndiv()
    {
        return $this->cdvNbDocIndiv;
    }

    /**
     * Set cdvNbDocCollect
     *
     * @param integer $cdvNbDocCollect
     *
     * @return CdvCycleDeVie
     */
    public function setCdvNbDocCollect($cdvNbDocCollect)
    {
        $this->cdvNbDocCollect = $cdvNbDocCollect;

        return $this;
    }

    /**
     * Get cdvNbDocCollect
     *
     * @return integer
     */
    public function getCdvNbDocCollect()
    {
        return $this->cdvNbDocCollect;
    }

    /**
     * Set cdvCreatedAt
     *
     * @param \DateTime $cdvCreatedAt
     *
     * @return CdvCycleDeVie
     */
    public function setCdvCreatedAt($cdvCreatedAt)
    {
        $this->cdvCreatedAt = $cdvCreatedAt;

        return $this;
    }

    /**
     * Get cdvCreatedAt
     *
     * @return \DateTime
     */
    public function getCdvCreatedAt()
    {
        return $this->cdvCreatedAt;
    }

    /**
     * Set cdvUpdatedAt
     *
     * @param \DateTime $cdvUpdatedAt
     *
     * @return CdvCycleDeVie
     */
    public function setCdvUpdatedAt($cdvUpdatedAt)
    {
        $this->cdvUpdatedAt = $cdvUpdatedAt;

        return $this;
    }

    /**
     * Get cdvUpdatedAt
     *
     * @return \DateTime
     */
    public function getCdvUpdatedAt()
    {
        return $this->cdvUpdatedAt;
    }
}
