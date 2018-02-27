<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * Class PopulationFilterTest
 * @package ApiBundle\Tests\Controller
 */
class PopulationFilterTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadProProfilData',
                'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
                'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
                'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
                'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData'
            ]
        );

        $this->client = static::makeClient();
    }

    /**
     * Teste le renvoi de la liste des filtres par population
     */
    public function testGetFilterListAction()
    {
        // todo: ATTENTION : Erreur, seul un utilisateur avec un profil chef de file peut accéder aux filtres
        $this->client = static::makeclientWithLogin();
        $this->client->request('GET', 'populationFilter', [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data": [{
                        "pdhId": 1,
                        "pdhLibelle": "Saisie predictive par nom",
                        "pdhNbrUsers":"2",
                        "pdhIndividuel":true,
                        "pdhCollectif":true
                    }, {
                        "pdhId": 2,
                        "pdhLibelle": "Saisie predictive SIRET",
                        "pdhNbrUsers":"0",
                        "pdhIndividuel":true,
                        "pdhCollectif":false
                    }]
                }'
            )
        );
    }

    /**
     * Teste le renvoi du détail d'un filtre par population
     */
    public function testGetFilterAction()
    {
        $this->client = static::makeclientWithLogin();
        $this->client->request('GET', 'populationFilter/1', [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);

        $expected = $this->tools->jsonMinify(
            '{"data":{
                "pdhId": 1,
                "pdhLibelle":"Saisie predictive par nom",
                "pdhModeReference":true,
                "pdhModeArchive":true,
                "pdhHabilitations":{
                    "leaf":false,
                    "codeMetadata":"ROOT",
                    "typeRegle":null,
                    "values":[],
                    "operator":null,
                    "children":[{
                        "leaf":false,
                        "codeMetadata":"Collectif",
                        "typeRegle":"and",
                        "values":[],
                        "operator":null,
                        "children":[{
                            "leaf":true,
                            "codeMetadata":"ifpCodeClient",
                            "typeRegle":null,
                            "values":[{
                                "code":"TSI504",
                                "value":"TSI504"
                            }],
                            "operator":"eq",
                            "children":[]
                        }]
                    },{
                        "leaf":false,
                        "codeMetadata":"Individuel",
                        "typeRegle":"and",
                        "values":[],
                        "operator":null,
                        "children":[{
                            "leaf":true,
                            "codeMetadata":"ifpCodeClient",
                            "typeRegle":null,
                            "values":[{
                                "code":"TSI504",
                                "value":"TSI504"
                            }],
                            "operator":"eq",
                            "children":[]
                        }]
                    }]
                },
                "users":[{
                    "login":"MyUsrLogin01",
                    "nom":"MyUsrNom01",
                    "prenom":"MyUsrPrenom01"
                },{
                    "login":"MyUsrLogin02",
                    "nom":"MyUsrNom02",
                    "prenom":"MyUsrPrenom02"
                }]
            }}');

        $this->assertEquals($expected, $response->getContent());
    }

    /**
     * Teste la création d'un filtre par population
     *
     * @throws \Exception
     */
    public function testPostAction()
    {
        $postData = $this->tools->jsonMinify('
            {
                "pdhId": null,
                "pdhLibelle": "nouveau filtre",
                "pdhModeReference": true,
                "pdhModeArchive": false,
                "pdhHabilitations": {
                  "leaf": false,
                  "codeMetadata": "ROOT",
                  "typeRegle": null,
                  "values": [],
                  "operator": null,
                  "children": [
                    {
                      "leaf": true,
                      "codeMetadata": "Collectif",
                      "typeRegle": "and",
                      "values": [],
                      "operator": null,
                      "children": []
                    },
                    {
                      "leaf": false,
                      "codeMetadata": "Individuel",
                      "typeRegle": "and",
                      "values": [],
                      "operator": null,
                      "children": [
                        {
                          "leaf": true,
                          "codeMetadata": "ifpCodeClient",
                          "typeRegle": null,
                          "values": [{"code":"TSI504","value":"TSI504"}],
                          "operator": "eq",
                          "children": []
                        }
                      ]
                    }
                  ]
               }
            }
        ');

        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_populationfilter_post', array());
        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            $this->mandatoryHeaders,
            $postData
        );
        $response = $this->client->getResponse();

        $expected = '{"data":'.str_replace('"pdhId":null,', '"pdhId":3,', $postData).'}';

        $this->assertJsonResponse($response, 200);
        $this->assertEquals(
            $expected,
            $response->getContent()
        );
    }

    /**
     * Teste la modification d'un filtre par population
     */
    public function testPutAction()
    {
        $postData = $this->tools->jsonMinify('
            {
                "pdhId": 1,
                "pdhLibelle": "autre filtre",
                "pdhModeReference": true,
                "pdhModeArchive": false,
                "pdhHabilitations": {
                  "leaf": false,
                  "codeMetadata": "ROOT",
                  "typeRegle": null,
                  "values": [],
                  "operator": null,
                  "children": [
                    {
                      "leaf": true,
                      "codeMetadata": "Collectif",
                      "typeRegle": "and",
                      "values": [],
                      "operator": null,
                      "children": []
                    },
                    {
                      "leaf": false,
                      "codeMetadata": "Individuel",
                      "typeRegle": "and",
                      "values": [],
                      "operator": null,
                      "children": [
                        {
                          "leaf": true,
                          "codeMetadata": "ifpCodeClient",
                          "typeRegle": null,
                          "values": [{"code":"TSI504","value":"TSI504"}],
                          "operator": "eq",
                          "children": []
                        }
                      ]
                    }
                  ]
               }
            }
        ');

        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_populationfilter_put', array('id' => 1));
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            $this->mandatoryHeaders,
            $postData
        );
        $response = $this->client->getResponse();

        $this->assertJsonResponse($response, 200);
        $this->assertEquals(
            '{"data":'.$postData.'}',
            $response->getContent()
        );
    }

    /**
     * Teste la suppression d'un filtre par population
     */
    public function testDeleteAction()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_populationfilter_delete', array('id' => 999));
        $this->client->request('DELETE', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 404);

        $route = $this->getUrl('api_apiv1_populationfilter_delete', array('id' => 1));
        $this->client->request('DELETE', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);

    }

    /**
     * Teste le renvoi de la liste d'utilisateurs affectés à un filtre par population
     */
    public function testGetUsersAction()
    {
        $this->client = static::makeclientWithLogin();
        $expected = $this->tools->jsonMinify(
            '{
                "data": [{
                    "login": "MyUsrLogin01",
                    "nom": "MyUsrNom01",
                    "prenom": "MyUsrPrenom01"
                }, {
                    "login": "MyUsrLogin02",
                    "nom": "MyUsrNom02",
                    "prenom": "MyUsrPrenom02"
                }]
            }'
        );
        $this->client->request('GET', 'populationFilter/1/users', [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals($expected, $response->getContent());
    }
}
