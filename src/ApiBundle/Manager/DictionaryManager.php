<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\DicDictionnaire;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Security\UserToken;
use Doctrine\ORM\AbstractQuery;
use Lsw\MemcacheBundle\Cache\MemcacheInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DictionaryManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var MemcacheInterface
     */
    private $memcache;

    /**
     * Détermine si les traductions sont chargées
     *
     * @var bool
     */
    private $isLoaded = false;

    /**
     * Liste des traductions
     *
     * @var array
     */
    private $translations = [];

    /**
     * Le n° d'instance
     *
     * @var string
     */
    private $numInstance;

    /**
     * @var UsrUsers
     */
    private $user;

    /**
     * @var string
     */
    private $device;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->memcache = $container->get('memcache.default');
        /** @var UserToken $userToken */
        $userToken = $container->get('security.token_storage')->getToken();
        $this->user = $userToken->getUser();
        $this->numInstance = $userToken->getNumInstance();
    }

    /**
     * Récupère la liste des traductions
     *
     * @param string $device Le support demandé
     *
     * @return array|void
     */
    public function getDictionnaire($device = DicDictionnaire::DEFAULT_DIC_DEVICE)
    {
        $language = $this->user->getUsrLangue() ?
            $this->user->getUsrLangue() : $this->container->getParameter('locale');

        $device = $device == '' ? DicDictionnaire::DEFAULT_DIC_DEVICE : $device;
        if ($this->isLoaded) {
            return $this->translations;
        }
        $cacheKey = 'API::' . $this->numInstance . '::dictionnaire_' . $language . '_' . $device;
        $this->isLoaded = true;
        $this->device = $device;
        if ($this->translations = $this->memcache->get($cacheKey)) {
            $this->translations = json_decode($this->translations, true);
        } else {
            $dictionaries = $this->container->get('doctrine')->getRepository('ApiBundle:DicDictionnaire')
                ->findAll($language, $device, AbstractQuery::HYDRATE_ARRAY);
            if (count($dictionaries)) {
                $this->translations = array_column(
                    $dictionaries,
                    'dicValeur',
                    'dicCode'
                );
                // Cache le dictionnaire pendant 24 heures
                $this->memcache->set(
                    $cacheKey,
                    json_encode($this->translations),
                    0,
                    86400
                );
            }
        }
        return $this->translations;
    }

    /**
     * Retourne la traduction demandée
     *
     * @param string $parameter Le paramètre pour lequel on souhaite récupérer le libellé
     *
     * @return mixed|string
     */
    public function getParameter($parameter)
    {
        $translations = $this->getDictionnaire($this->device);
        if (isset($translations[$parameter])) {
            return $translations[$parameter];
        }
        return '';
    }

    /**
     * Récupère la liste des langues disponibles pour l'application
     *
     * @param string $device Le support
     *
     * @return array
     */
    public function retrieveAvailableLanguageList($device)
    {
        return preg_filter(
            '/^language_([a-z]{2}_[A-Z]{2})$/',
            '$1',
            array_flip(
                $this->getDictionnaire($device)
            )
        );
    }
}
