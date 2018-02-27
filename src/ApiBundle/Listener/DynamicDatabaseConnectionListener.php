<?php

namespace ApiBundle\Listener;

use ApiBundle\Security\UserToken;
use Doctrine\DBAL\Connection;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use IkpBundle\Service\IkpClientService;
use ApiBundle\Exception\InvalidPacNumberException;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

/**
 * Classe Listener permettant d'effectuer de connexions dynamiques
 * aux bases de données
 *
 * Class DynamicDatabaseConnectionListener
 * @package ApiBundle\Listener
 */
class DynamicDatabaseConnectionListener
{
    protected $request;
    protected $connection;
    protected $container;
    protected $mem;
    protected $ikpResponse;

    public function __construct(ContainerInterface $container, Connection $connection)
    {
        $this->container = $container;
        $this->request = $container->get('request');
        $this->connection = $connection;
        $this->mem = $this->container->get('memcache.default');
    }

    /**
     * @throws Exception
     */
    public function onKernelRequest()
    {
        $userToken = $this->container->get('security.token_storage')->getToken();

        if ($userToken instanceof AnonymousToken) {
            $clientInstance = $this->request->headers->get('numinstance');
        } elseif ($userToken instanceof UserToken) {
            $clientInstance = $userToken->getNumInstance();
        } elseif (empty($clientInstance)) {
            $clientInstance = $this->request->headers->get('numinstance');
        } else {
            throw new Exception("Instance number must be set !");
        }

        $this->ikpCaller($clientInstance);
    }

    public function ikpCaller($clientInstance = null)
    {
        /**
         * On by-pass IKP si la BDD est paramétrée dans parameters.yml
         * sauf pour l'environnement de test où nous avons besoin de tester le stockage des infos IKP
         * dans memcached
         */
        if ($this->container->getParameter('database_host')
            && $this->container->get('kernel')->getEnvironment() != 'test') {
            return;
        }

        $basePath = $this->container->getParameter('base_path_config');
        $nameFile = $this->container->getParameter('name_file');
        // Test si la connexion du ClientInstance est cachée
        if (false === $this->isCachingConnection($clientInstance)) {
            $ikpClient = new IkpClientService($this->container, $basePath . '/' . $clientInstance, $nameFile);
            $this->ikpResponse = $ikpClient->getResponseIkp();
            // Test si non vide
            if (!count($this->ikpResponse)) {
                throw new Exception("Invalid IkpClient Response");
            }
            // On met en cache la connexion du client
            $this->addCachingConnection($clientInstance, $this->ikpResponse);
        } else {
            // Lecture du cache
            $this->ikpResponse = (array)json_decode($this->mem->get('API::' . $clientInstance . '::ikp'));
        }

        /**
         * Pour l'environnement de test on ne modifie pas la connection à la BDD qui
         * doit rester locale avec ses propres fixtures
         */
        if ($this->container->get('kernel')->getEnvironment() == 'test') {
            return;
        }

        $dbParams = [
            'host' => $this->ikpResponse['mysql_server'],
            'dbname' => $this->ikpResponse['mysql_database'],
            'user' => $this->ikpResponse['mysql_login'],
            'password' => $this->ikpResponse['mysql_password'],
            'driver' => 'pdo_mysql',
            'charset' => 'UTF8'
        ];

        try {
            $this->connection->__construct(
                $dbParams,
                $this->connection->getDriver(),
                $this->connection->getConfiguration(),
                $this->connection->getEventManager()
            );
        } catch (Exception $e) {
            throw new InvalidPacNumberException($e->getMessage());
        }
    }

    /**
     * On verifie si les paramètres de connexion du client sont en cache
     *
     * @param $clientInstance
     *
     * @return bool
     */
    private function isCachingConnection($clientInstance)
    {
        if ($this->mem->get('API::' . $clientInstance . '::ikp')) {
            return true;
        }

        return false;
    }

    /**
     * On ajoute les paramètres de connexion en cache
     *
     * @param $clientInstance
     * @param $ikpResponse
     */
    private function addCachingConnection($clientInstance, $ikpResponse)
    {
        $this->mem
            ->set(
                'API::' . $clientInstance . '::ikp',
                json_encode($ikpResponse),
                0,
                86400
            );
    }
}
