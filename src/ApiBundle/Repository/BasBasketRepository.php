<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\BasBasket;
use ApiBundle\Entity\UsrUsers;
use Doctrine\ORM\EntityRepository;

class BasBasketRepository extends EntityRepository
{

    /**
     * Renvoit le panier de l'utilisateur et le crée s'il n'existe pas
     *
     * @param $user
     * @return BasBasket
     */
    public function getOrCreateBasketForUser($user)
    {
        $username = $user instanceof UsrUsers ? $user->getUsrLogin() : (string) $user;

        $basket = $this->findOneByFolIdOwner($username);

        if (null === $basket) {
            $basket = new BasBasket();
            $basket->setFolIdOwner($username);
            $basket->setLibelle('panier de '.$username);
            $this->_em->persist($basket);
            $this->_em->flush($basket);
        }

        return $basket;
    }
}
