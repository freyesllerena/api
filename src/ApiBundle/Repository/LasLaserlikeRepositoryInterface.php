<?php

namespace ApiBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface LasLaserlikeRepositoryInterface extends ObjectRepository
{

    /**
     * Sélectionne à partir d'un numéro de PDF
     *
     * @param $numeroPdf
     */
    public function findOneByNumeroPdf($numeroPdf);

}