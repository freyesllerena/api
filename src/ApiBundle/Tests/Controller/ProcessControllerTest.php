<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class ProcessControllerTest
 * @package ApiBundle\Tests\Controller
 */
class ProcessControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadProProcessusData',
                'ApiBundle\DataFixtures\ORM\LoadPtyProcessusTypeData',
                'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
                'ApiBundle\DataFixtures\ORM\LoadProProfilData',
                'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData',
                'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
                'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
                'ApiBundle\DataFixtures\ORM\LoadDicDictionnaireData'
            ]
        );

        $this->client = static::makeClient();
    }

    /**
     * @param $response Response
     * @param int $statusCode
     */
    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json; charset=utf-8'),
            $response->headers
        );
    }

    /**
     * Test : GET /process/list
     */
    public function testGetProcessList()
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [
                    {
                        "id": "group2",
                        "text": "Gestion des arr\u00eats de travail",
                        "expanded": false,
                        "leaf": false,
                        "children": []
                    },
                    {
                        "id": "group5",
                        "text": "B\u00e9n\u00e9fices",
                        "expanded": false,
                        "leaf": false,
                        "children": [
                            {
                                "id": 2,
                                "text": "Processus perso. de MyUsrLogin02",
                                "expanded": false,
                                "leaf": true,
                                "drawers": [
                                    "D325800",
                                    "D465200"
                                ]
                            }
                        ]
                    },
                    {
                        "id": "group8",
                        "text": "GTA, feuille de pr\u00e9sence",
                        "expanded": false,
                        "leaf": false,
                        "children": []
                    },
                    {
                        "id": "group12",
                        "text": "Accords entreprise, OTT",
                        "expanded": false,
                        "leaf": false,
                        "children": []
                    }
                ]
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_process_getprocesslist', ['q' => base64_encode('device=DES')]);
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();

        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Tests : POST /process
     */
    public function testPostProcess()
    {
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_process_postprocess');
        $this->postProcessUseCase01($route);
        $this->postProcessUseCase02($route);
    }

    /**
     * UseCase1 : Request pour ajouter un processus sans tiroir(s)
     * @param $route
     */
    private function postProcessUseCase01($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errTypTypeListEmpty",
                        "values": [],
                        "fieldName": "ptyType"
                    }]
                }
            }'
        );
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "proGroupe": 8,
                    "proLibelle": "Mon processus perso.",
                    "ptyType": [
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request pour ajouter un processus avec deux tiroirs
     * @param $route
     */
    private function postProcessUseCase02($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "proId": 3,
                    "proGroupe": 5,
                    "proLibelle": "Mon processus perso."
                }
            }'
        );
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "proGroupe": 5,
                    "proLibelle": "Mon processus perso.",
                    "ptyType": [
                        "D150010",
                        "D325800"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Tests : PUT /process
     */
    public function testPutProcess()
    {
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_process_putprocess');
        $this->putProcessUseCase01($route);
        $this->putProcessUseCase02($route);
        $this->putProcessUseCase03($route);
    }

    /**
     * UseCase1 : Request pour modifier un processus dont le libellé est vide
     * @param $route
     */
    private function putProcessUseCase01($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errProProcessusLabelEmpty",
                        "values": {
                            "__value__": ""
                        },
                        "fieldName": "proLibelle"
                    }]
                }
            }'
        );
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "proId": 2,
                    "proGroupe": 9,
                    "proLibelle": "",
                    "ptyType": [
                        "D256900",
                        "D325800"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request pour modifier un processus pour lequel l'utilisateur n'est pas le propriétaire
     * @param $route
     */
    private function putProcessUseCase02($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errProProcessusNotOwner",
                        "values": {
                            "__label__": "Processus perso. de MyUsrLogin01"
                        },
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "proId": 1,
                    "proGroupe": 3,
                    "proLibelle": "Processus perso. de MyUsrLogin01 à modifier",
                    "ptyType": [
                        "D150010",
                        "D325800"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 403);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase3 : Request pour modifier un processus, ajouter le tiroir D256900 et supprimer le tiroir D150010
     * @param $route
     */
    private function putProcessUseCase03($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "proId": 2,
                    "proGroupe": 12,
                    "proLibelle": "Mon processus perso. modifi\u00e9"
                }
            }'
        );
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "proId": 2,
                    "proGroupe": 12,
                    "proLibelle": "Mon processus perso. modifié",
                    "ptyType": [
                        "D256900",
                        "D325800"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Tests : DELETE /process
     */
    public function testDeleteProcess()
    {
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_process_deleteprocess');
        $this->deleteProcessUseCase01($route);
        $this->deleteProcessUseCase02($route);
        $this->deleteProcessUseCase03($route);
    }

    /**
     * UseCase1 : Request pour supprimer un processus qui n'existe pas
     * @param $route
     */
    private function deleteProcessUseCase01($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errProProcessusDoesNotExist",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client->request(
            'DELETE',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            '{"proId": 10}'
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request pour supprimer un processus pour lequel l'utilisateur n'est pas le propriétaire
     * @param $route
     */
    private function deleteProcessUseCase02($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errProProcessusNotOwner",
                        "values": {
                            "__label__": "Processus perso. de MyUsrLogin01"
                        },
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client->request(
            'DELETE',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            '{"proId": 1}'
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 403);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase3 : Request pour supprimer un processus et ses tiroirs associés
     * @param $route
     */
    private function deleteProcessUseCase03($route)
    {
        $this->client->request(
            'DELETE',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            '{"proId": 2}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
