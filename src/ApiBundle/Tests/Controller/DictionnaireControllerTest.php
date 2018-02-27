<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Controller\DocapostController;
use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class DictionnaireControllerTest
 * @package ApiBundle\Tests\Controller
 */
class DictionnaireControllerTest extends DocapostWebTestCase
{
    /**
     * @var Client instance
     */
    private $client;

    public function setUp()
    {
        $this->initTests(
            [
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadDicDictionnaireData'
            ]
        );

        $this->client = static::makeClient();
    }

    /**
     * @param Response $response
     * @param int $statusCode
     */
    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json; charset=utf-8'),
            $response->headers
        );
    }

    /**
     * Test : GET /dictionary?lang=fr_FR&device=MOB
     * UseCase : Request pour la langue fr_FR et le support mobile
     */
    public function testGetDictionaryAction()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = '{"data":{"pdcNiv1-2":"Test niveau 1: 2","pdcNiv2-210":"Test niveau 2: 210"}}';
        $queryString = $this->encodeQueryString('lang=fr_FR&device=MOB');

        $route = $this->getUrl('api_apiv1_dictionary_getdictionary', array('q' => $queryString));

        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();

        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : GET /dictionary?lang=fr_FR
     * UseCase : Request pour la langue fr_FR
     */
    public function testGetDictionaryWithoutDeviceAction()
    {
        $this->client = static::makeclientWithLogin();
        $queryString = $this->encodeQueryString('lang=fr_FR');

        $route = $this->getUrl('api_apiv1_dictionary_getdictionary', array('q' => $queryString));

        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $jsonContent = $response->getContent();
        $content = json_decode($jsonContent, true);

        $this->assertJsonResponse($response, 400);
        $this->assertEquals($content['msg']['level'], DocapostController::MSG_LEVEL_ERROR);
        $this->assertNotEmpty($jsonContent);
    }

    /**
     * Test : GET /dictionary?device=TEST
     * UseCase : Request avec un device non reconnu
     */
    public function testGetDictionaryWithBadtDeviceAction()
    {
        $this->client = static::makeclientWithLogin();
        $queryString = $this->encodeQueryString('lang=fr_FR&device=TEST');

        $route = $this->getUrl('api_apiv1_dictionary_getdictionary', array('q' => $queryString));

        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $jsonContent = $response->getContent();
        $content = json_decode($jsonContent, true);

        $this->assertJsonResponse($response, 400);
        $this->assertEquals($content['msg']['level'], DocapostController::MSG_LEVEL_ERROR);
        $this->assertNotEmpty($jsonContent);
    }

    protected function encodeQueryString($query)
    {
        return base64_encode($query);
    }
}
