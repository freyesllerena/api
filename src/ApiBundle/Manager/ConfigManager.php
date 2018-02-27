<?php

namespace ApiBundle\Manager;

use ApiBundle\Repository\ConConfigRepository;
use ApiBundle\Security\UserToken;
use Lsw\MemcacheBundle\Cache\MemcacheInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigManager
{
    /**
     * @var ConConfigRepository
     */
    private $configRepository;

    /**
     * @var MemcacheInterface
     */
    private $memcache;

    /**
     * Détermine si la config est chargée
     *
     * @var bool
     */
    private $isLoaded = false;

    /**
     * Liste la config de l'instance
     *
     * @var array
     */
    private $config = [];

    /**
     * Le n° d'instance
     *
     * @var string
     */
    private $numInstance;

    public function __construct(ContainerInterface $container)
    {
        $this->configRepository = $container->get('api.repository.config');
        $this->memcache = $container->get('memcache.default');
        $userToken = $container->get('security.token_storage')->getToken();
        /** @var UserToken $userToken */
        $this->numInstance = $userToken->getNumInstance();
    }

    /**
     * Récupère la config de l'instance
     *
     * @return mixed
     */
    public function getConfigInstance()
    {
        if ($this->isLoaded) {
            return $this->config;
        }
        $this->isLoaded = true;
        if ($this->config = $this->configInCache()) {
            return $this->config;
        } else {
            $this->config = array_column(
                $this->configRepository->retrieveAll(),
                'conValeur',
                'conVariable'
            );
            // Cache pendant 24 heures
            $this->memcache->set(
                'API::' . $this->numInstance . '::config',
                json_encode($this->config),
                0,
                86400
            );
            return $this->config;
        }
    }

    /**
     * Récupère la config de l'instance si cachée
     *
     * @return string
     */
    private function configInCache()
    {
        if ($configCached = $this->memcache->get('API::' . $this->numInstance . '::config')) {
            return json_decode($configCached, true);
        }
        return '';
    }

    /**
     * Récupère un paramètre de la config de l'instance
     *
     * @param string $parameter Le paramètre à récupérer
     *
     * @return string
     */
    public function getParameter($parameter)
    {
        $config = $this->getConfigInstance();
        if (isset($config[$parameter])) {
            return $config[$parameter];
        }
        return '';
    }
}
