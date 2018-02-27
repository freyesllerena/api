<?php
/**
 * Created by PhpStorm.
 * User: mmorel
 * Date: 23/03/2016
 * Time: 15:30
 */

namespace ApiBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use ApiBundle\Entity\UsrUsers;

interface PdaProfilDefAppliRepositoryInterface extends ObjectRepository
{

    /**
     * Retourne les filtres applicatifs d'un utilisateur
     *
     * @param UsrUsers $user
     *
     * @return array
     */
    public function findByUser(UsrUsers $user);

}
