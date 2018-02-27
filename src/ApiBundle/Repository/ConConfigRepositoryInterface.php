<?php
/**
 * Created by PhpStorm.
 * User: mmorel
 * Date: 24/03/2016
 * Time: 11:38
 */

namespace ApiBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface ConConfigRepositoryInterface extends ObjectRepository, \ArrayAccess
{
    /**
     * Renvoit les droits définis au niveau de l'instance
     *
     * @return mixed
     */
    public function getInstanceAuthorizations();
}
