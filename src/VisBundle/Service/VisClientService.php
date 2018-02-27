<?php

namespace VisBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use VisBundle\Exception\VisException;

class VisClientService
{

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * VisClientService constructor.
     *
     * @param string|ClientInterface $urlOrClient L'url du serveur ou un client http Guzzle
     * @throws \InvalidArgumentException
     */
    public function __construct($urlOrClient)
    {
        if ($urlOrClient instanceof ClientInterface) {
            $this->client = $urlOrClient;
        } elseif (is_string($urlOrClient)) {
            $this->client = new Client(['base_uri' => $urlOrClient]);
        } else {
            throw new \InvalidArgumentException(
                'Le paramètre du constructeur de VisClientService doit être une chaine '.
                'ou une instance de GuzzleHttp\ClientInterface'
            );
        }
    }

    /**
     * Execute un script sur le serveur
     *
     * @param string $script     code source du script
     * @param string $remotePath chemin distant sur le serveur VIS
     * @param array  $query      paramétres du query string
     *
     * @throws VisException
     *
     * @return ResponseInterface
     */
    public function executeScript($script, $remotePath = '', $query = array())
    {
        $nomFichier    = $this->generateUniqueFilename();
        $output   = $remotePath . $nomFichier.'.tmp';  // le chemin	complet	côté vis du	fichier	de sortie
        $filename = $remotePath . $nomFichier .'.vis'; // le fichier .vis vu coté windows

        $response = $this->client->request('POST', '/', [
            'form_params' => [
                'fichier_sortie' => $output,
                'fichier_script_vis' => $filename,
                'fichier_script_vis_raw' => $script,
            ],
            'query' => $query
        ]);

        return $response;
    }

    /**
     * Génère un nom de fichier unique
     */
    protected function generateUniqueFilename()
    {
        list($usec, $sec) = explode(' ', microtime());
        $time = (float) $sec + ((float) $usec * 1000000);
        mt_srand(); //init	gestionnaire de	nombre aléatoire
        return mt_rand().'_'.mt_rand().'_'.$time;
    }
}
