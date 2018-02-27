<?php
namespace ApiBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use ApiBundle\Tests\Lib\DocapostToolsTest;

class DocapostWebTestCase extends WebTestCase
{
    /**
     * Headers minimum OBLIGATOIRE pour accéder à l'API
     * @var array
     */
    protected $mandatoryHeaders;

    /**
     * @var DocapostToolsTest instance
     */
    protected $tools;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->mandatoryHeaders = ['HTTP_numinstance' => '000001', 'HTTP_Accept' => 'application/json;version=1.0'];
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Surcharge temporairement la méthode de création du client web
     * @param bool $authentication
     * @param array $params
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function makeClient($authentication = false, array $params = array())
    {
        $authentication = false;
        $params = array('HTTP_codesource' => 'na');
        $client = parent::makeClient($authentication, $params);

        // Déconnecte l'utilisateur avant de le reconnecter
        $this->logoffClient($client);

        return $client;
    }

    protected function tearDown()
    {
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }

        // Force la fermeture des connections
        foreach ($this->getContainer()->get('doctrine')->getConnections() as $connection) {
            $connection->close();
        }
//        gc_collect_cycles();

        parent::tearDown();
    }

    /**
     * @param bool $authentication
     * @param array $params
     * @param string $userId
     * @return \Symfony\Bundle\FrameworkBundle\Client
     * @throws \Exception
     */
    protected function makeclientWithLogin($authentication = true, array $params = array(), $userId = 'MyUsrLogin01')
    {
        $client  = self::makeClient(false, $params);
        if ($authentication) {
            $headers = array_merge(
                $this->mandatoryHeaders,
                [
                    'HTTP_Accept'      => 'application/json;version=1.0',
                    'HTTP_codesource'  => 'ADP_PORTAIL',
                    'HTTP_pac'         => 'TSI504',
                    'HTTP_logoffurl'   => 'http://acme.com/logoff',
                    'HTTP_bveurl'      => 'http://acme.com/bveurl',
                    'HTTP_userid'      => $userId,
                    'HTTP_numinstance' => '000001'
                ]
            );

            // Déconnecte l'utilisateur avant de le reconnecter
            $this->logoffClient($client);

            // Connecte l'utilisateur
            $client->request('GET', $this->getUrl('api_apiv1_session_logon'), [], [], $headers);
            $response = $client->getResponse();

            if ($response->getStatusCode() != 302) {
                throw new \Exception('Le logon a échoué !');
            }
        }
        return $client;
    }

    /**
     * @param Client $client
     * @return mixed
     */
    private function logoffClient($client)
    {
        $client->request('GET', $this->getUrl('api_apiv1_session_logoff'), [], [], $this->mandatoryHeaders);
        $client->getResponse();
        return $client;
    }

    /**
     * @param Response $response
     * @param int $statusCode
     */
    protected function assertJsonResponse($response, $statusCode = 200)
    {
        if ($response->getStatusCode() != $statusCode) {
            print $response->getContent().PHP_EOL;
        }
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json; charset=utf-8'),
            $response->headers
        );
    }

    /**
     * Prépare le chargement des fixtures
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function preFixtureSetup()
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $entityManager->getConnection()->close();
    }

    protected function initTests($fixtures)
    {
        // Flush memcache content
        $this->getContainer()->get('memcache.default')->flush();

        // Prepare fixtures
        $this->preFixtureSetup();
        $this->postFixtureSetup();
        $fixtures[] = 'ApiBundle\DataFixtures\ORM\LoadConConfigData';
        $this->loadFixtures($fixtures);

        $this->tools = new DocapostToolsTest();
    }

    /**
     * @after
     */
    public function closeConnectionAfterTest()
    {
        foreach ($this->getContainer()->get('doctrine')->getConnections() as $connection) {
            $connection->close();
        }
    }
}
