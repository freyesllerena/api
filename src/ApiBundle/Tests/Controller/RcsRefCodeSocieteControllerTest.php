<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class RcsRefCodeSocieteControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadRcsRefCodeSocieteData',
                'ApiBundle\DataFixtures\ORM\LoadRidRefIdData'
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
     * Test : GET /referential/pac/{pacId}/company
     * UseCase1 : retourne la liste des codes société pour le code PAC indiqué
     * UseCase2 : retourne une erreur si le code PAC indiqué n'existe pas
     *
     * @throws \Exception
     */
    public function testGetReferentialCompany()
    {
        // UseCase1
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "900000001": "Soci\u00e9t\u00e9 Test 01",
                    "900000002": "Soci\u00e9t\u00e9 Test 02",
                    "900000009": "Soci\u00e9t\u00e9 Test 09"
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_rcsrefcodesociete_getreferentialcompany', ['pacId'=>'TSI504']);
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders, '{}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase2
        $expectedResponse = '{"msg":{"level":3,"infos":[{"code":"errRcsRefCodeSocietePacDoesNotExist","values":[],"fieldName":""}]}}';
        $route = $this->getUrl('api_apiv1_rcsrefcodesociete_getreferentialcompany', ['pacId'=>'TSI999']);
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders, '{}');
        $response = $this->client->getResponse();

        $this->assertJsonResponse($response, 404);
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}
