<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\UsrUsers;
use Doctrine\Common\Persistence\ObjectRepository;

interface PdhProfilDefHabiRepositoryInterface extends ObjectRepository
{

    /**
     * Retourne les filtres par population d'un utilisateur
     *
     * @param UsrUsers $user
     *
     * @return array
     */
    public function findByUser(UsrUsers $user);

}
