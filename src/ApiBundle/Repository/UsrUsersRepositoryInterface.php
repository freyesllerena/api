<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\PdaProfilDefAppli;
use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Entity\UsrUsers;
use Doctrine\Common\Persistence\ObjectRepository;

interface UsrUsersRepositoryInterface extends ObjectRepository
{
    public function findOneByUsrLogin($login);
}
