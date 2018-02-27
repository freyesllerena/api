<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class AdminControllerTest
 * @package ApiBundle\Tests\Controller
 */
class AdminControllerTest extends DocapostWebTestCase
{
    /**
     * @var Client instance
     */
    private $client;

    /**
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp()
    {
        $this->initTests(
            [
                'ApiBundle\DataFixtures\ORM\LoadRapRapportData',
                'ApiBundle\DataFixtures\ORM\LoadIhaImportHabilitationData',
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadProProfilData',
                'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
                'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
                'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
                'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData',
                'ApiBundle\DataFixtures\ORM\LoadDicDictionnaireData',
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
     * Test : GET /habilitation
     * UseCase : Request pour récupérer la liste des utilisateurs avec leurs habilitations
     */
    public function testGetUserHabilitation()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [
                    {
                        "ihaTraite": 5,
                        "ihaSucces": 5,
                        "ihaErreur": 0,
                        "rapEtat": "OK",
                        "rapId": 1
                    },
                    {
                        "ihaTraite": 3,
                        "ihaSucces": 2,
                        "ihaErreur": 1,
                        "rapEtat": "KO",
                        "rapId": 2
                    }
                ]
            }'
        );

        $route = $this->getUrl('api_apiv1_admin_getuserhabilitation');
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);
        unset($result['data'][0]['ihaCreatedAt']);
        unset($result['data'][1]['ihaCreatedAt']);
        $actualResponse = json_encode($result);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * Test : GET /habilitation/model
     * UseCase : Request pour récupérer les utilisateurs qui n'ont pas ou ont des habilitations incomplètes
     */
    public function testGetUserHabilitationModel()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_admin_getuserhabilitationmodel');
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $this->client->getResponse()->sendContent();
        $content = ob_get_contents();
        ob_clean();
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->assertStringStartsWith(
            'attachment; filename="export_habilitation.csv"',
            $this->client->getResponse()->headers->get('Content-Disposition')
        );
        $this->assertNotEmpty($content);
        $this->assertNotNull($content);
    }
}
