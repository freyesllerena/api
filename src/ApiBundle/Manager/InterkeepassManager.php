<?php

namespace ApiBundle\Manager;

use ApiBundle\Security\UserToken;
use IkpBundle\Service\IkpClientService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InterkeepassManager
{

    /**
     * InterkeepassManager constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->request = $container->get('request');
        $this->mem = $this->container->get('memcache.default');
    }

    /**
     * Renvoit la réponse Interkeepass pour un num instance donné
     *
     * @return array
     * @throws \Exception
     */
    public function getConfiguration()
    {
        $clientInstance = $this->request->headers->get('numinstance');
        $userToken = $this->container->get('security.token_storage')->getToken();

        if ($userToken instanceof UserToken) {
            $clientInstance = $userToken->getNumInstance();
        } elseif (!empty($clientInstance)) {
            $clientInstance = $this->request->headers->get('numinstance');
        } else {
            throw new \Exception("Instance number must be set !");
        }
        
        $basePath = $this->container->getParameter('base_path_config');
        $nameFile = $this->container->getParameter('name_file');
        // Test si la connexion du ClientInstance est cachée
        if (false === $this->isCachingConnection($clientInstance)) {
            $ikpClient = new IkpClientService($this->container, $basePath . '/' . $clientInstance, $nameFile);
            $ikpResponse = $ikpClient->getResponseIkp();
            // Test si non vide
            if (!count($ikpResponse)) {
                throw new \Exception("Invalid IkpClient Response");
            }
            // On met en cache la connexion du client
            $this->addCachingConnection($clientInstance, $ikpResponse);
        } else {
            // Lecture du cache
            $ikpResponse = (array)json_decode($this->mem->get('API::' . $clientInstance . '::ikp'));
        }
        return $ikpResponse;
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

    /**
     * Remplace les valeurs interpass dans une chaine
     *
     * @param $string
     * @return mixed
     * @throws \Exception
     */
    public function convert($string)
    {

        $ikpConfiguration = $this->getConfiguration();

        $ikpMappings = array(
            'INTERKEEPASS WEBVIS PARAM1' => 'vis_root',
            'INTERKEEPASS WEBVIS PARAM2' => 'vis_url_http',
            'INTERKEEPASS WEBVIS PARAM3' => 'vis_url_ftp',
            'INTERKEEPASS CFECVIS PARAM1' => 'cfec_cert',
            'INTERKEEPASS CFECVIS PARAM2' => 'cfec_coffre',
            'INTERKEEPASS ESCLASERLIKE PARAM1' => 'esc_cert',
            'INTERKEEPASS ESCLASERLIKE PARAM2' => 'esc_coffre',
            'INTERKEEPASS ESCLASERLIKE PARAM3' => 'esc_salle',
        );
        foreach ($ikpMappings as $name => $value) {
            $string = str_replace($name, $ikpConfiguration[$value], $string);
        }

        return $string;
    }
}
