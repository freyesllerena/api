<?php

namespace ApiBundle\Manager;

use ApiBundle\Repository\RidRefIdRepository;
use ApiBundle\Security\UserToken;
use Doctrine\ORM\EntityRepository;
use Lsw\MemcacheBundle\Cache\MemcacheInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ReferentialManager
{
    /**
     * @var RidRefIdRepository
     */
    private $refIdRepository;

    /**
     * @var MemcacheInterface
     */
    private $memcache;

    /**
     * Le n° d'instance
     *
     * @var string
     */
    private $numInstance;

    public function __construct(ContainerInterface $container)
    {
        // RidRefIdRepository
        $this->refIdRepository = $container->get('api.repository.referential');
        // Memcache
        $this->memcache = $container->get('memcache.default');
        // UserToken
        $userToken = $container->get('security.token_storage')->getToken();
        /** @var UserToken $userToken */
        $this->numInstance = $userToken->getNumInstance();
    }

    /**
     * Récupère la liste des codes PAC d'une instance
     *
     * @return array
     */
    public function retrievePacList()
    {
        return array_column(
            $this->refIdRepository->findByRidType('CodeClient'),
            'ridLibelle',
            'ridCode'
        );
    }

    /**
     * Retourne toute la liste des valeurs de la table
     *
     * @return array
     */
    public function retrieveAllList()
    {
        if ($list = $this->memcache->get('API::' . $this->numInstance . '::referentiel')) {
            return json_decode($list, true);
        } else {
            $list = $this->refIdRepository->findAllAsArray();
            $this->memcache->set(
                'API::' . $this->numInstance . '::referentiel',
                json_encode($list),
                0,
                86400
            );
            return $list;
        }
    }
}
