<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class PopulationFilterTest
 * @package ApiBundle\Tests\Controller
 */
class ApplicationFilterControllerTest extends DocapostWebTestCase
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
     * Teste la liste des filtres applicatifs
     */
    public function testGetListApplicationFilters()
    {
        // todo: ATTENTION : Erreur, seul un utilisateur avec un profil chef de file peut accéder aux filtres
        $this->client = static::makeclientWithLogin();
        $this->client->request(
            'GET',
            '/api/appliFilter',
            [],
            [],
            $this->mandatoryHeaders
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data":[{
                        "pdaId":1,
                        "pdaLibelle":"Documents collectifs paie et entreprise",
                        "pdaNbrUsers": "1"
                    },{
                        "pdaId":2,
                        "pdaLibelle":"Documents dossiers salari\u00e9s",
                        "pdaNbrUsers": "1"
                    }]
                }'
            )
        );
    }

    /**
     * Teste le détail d'un filtre applicatif
     *
     * @throws \Exception
     */
    public function testGetApplicationFilter()
    {
        $this->client = static::makeclientWithLogin();
        $this->client->request(
            'GET',
            '/api/appliFilter/1',
            [],
            [],
            $this->mandatoryHeaders
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data": {
                        "pdaId":1,
                        "pdaLibelle":"Documents collectifs paie et entreprise",
                        "pdaTiroirs":["G652660","G652670","G602710","G602720","G651400","G651390","G651380",
                            "G200025","G200010","G200020","G200015","G601410","G601420","G601430","G601440",
                            "G601450","G200030","G400170","G400120","G400175","G400160","G400200","G400165",
                            "G601460","G651530","G601540","G601550","G601560","G601570","G601580","G400185",
                            "G150020","G150021","G150022","G150023","G150025","G150015","G150010","G150030",
                            "G150035","G150040","G150045","G400190","G400145","G400140","G400155","G400150",
                            "G601820","G601830","G601840","G601850","G601860","G250015","G250010","G602320",
                            "G602330","G602340","G602350","G602440","G602360","G602370","G602380","G602390",
                            "G602400","G602410","G602420","G602430","G602100","G400080","G400085","G400075",
                            "G602110","G400065","G400100","G400070","G400125","G100025","G100020","G652030",
                            "G400130","G400115","G400090","G400195","G602130","G602060","G602080","G602120",
                            "G602040","G602070","G602050","G602090","G400095","G400105","G400110","G300010","G300015",
                            "G300020","G601620","G601630","G100015","G100040","G100010","G100035","G601300","G601310",
                            "G601320","G250020","G250025","G250045","G250070","G250030","G250050","G250040","G250035",
                            "G250065","G250055","G250060","G602250","G602260","G602270","G602280","G602290","G651210",
                            "G652930","G652940","G350020","G100050","G150050"],
                        "pdaNbi": 100,
                        "pdaNbc": 100
                    }
                }'
            )
        );
    }

    /**
     * Test création filtre applicatif
     */
    public function testPostAction()
    {
        $this->client = static::makeclientWithLogin();

        // UseCase1 : Notifier les champs manquants s'il y en a
        $this->client->request(
            'POST',
            '/api/appliFilter',
            [],
            [],
            $this->mandatoryHeaders,
            '{}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "msg":{
                        "level":3,
                        "infos":[
                            {
                                "code":"errDocapostControllerParameterIsMissing",
                                "values":{"__parameter__":"pdaLibelle"},
                                "fieldName":""
                            },
                            {
                                "code":"errDocapostControllerParameterIsMissing",
                                "values":{"__parameter__":"pdaTiroirs"},
                                "fieldName":""
                            },
                            {
                                "code":"errDocapostControllerParameterIsMissing",
                                "values":{"__parameter__":"pdaNbi"},
                                "fieldName":""
                            },
                            {
                                "code":"errDocapostControllerParameterIsMissing",
                                "values":{"__parameter__":"pdaNbc"},
                                "fieldName":""
                            }
                        ]
                    }
                }'
            )
        );

        // UseCase2 : Crée l'objet si la requête est valide
        $this->client->request(
            'POST',
            'appliFilter',
            [],
            [],
            $this->mandatoryHeaders,
            '{
                "pdaLibelle": "Nouveau filtre applicatif",
                "pdaTiroirs": ["G652660","G652670","G150050"],
                "pdaNbi": 12,
                "pdaNbc": 13
            }'
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data": {
                        "pdaId":3,
                        "pdaLibelle":"Nouveau filtre applicatif",
                        "pdaTiroirs":["G652660","G652670","G150050"],
                        "pdaNbi": 12,
                        "pdaNbc": 13
                    }
                }'
            )
        );

        $this->client->request(
            'GET',
            '/api/appliFilter/3',
            [],
            [],
            $this->mandatoryHeaders
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data": {
                        "pdaId":3,
                        "pdaLibelle":"Nouveau filtre applicatif",
                        "pdaTiroirs":["G652660","G652670","G150050"],
                        "pdaNbi": 12,
                        "pdaNbc": 13
                    }
                }'
            )
        );
    }

    /**
     * Teste modification filtre applicatif
     *
     * @throws \Exception
     */
    public function testPutAction()
    {
        $this->client = static::makeclientWithLogin();
        $this->client->request(
            'PUT',
            '/api/appliFilter/1',
            [],
            [],
            $this->mandatoryHeaders,
            '{}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "msg":{
                        "level":3,
                        "infos":[
                            {
                                "code":"errDocapostControllerParameterIsMissing",
                                "values":{"__parameter__":"pdaLibelle"},
                                "fieldName":""
                            },
                            {
                                "code":"errDocapostControllerParameterIsMissing",
                                "values":{"__parameter__":"pdaTiroirs"},
                                "fieldName":""
                            },
                            {
                                "code":"errDocapostControllerParameterIsMissing",
                                "values":{"__parameter__":"pdaNbi"},
                                "fieldName":""
                            },
                            {
                                "code":"errDocapostControllerParameterIsMissing",
                                "values":{"__parameter__":"pdaNbc"},
                                "fieldName":""
                            }
                        ]
                    }
                }'
            )
        );

        $this->client = static::makeclientWithLogin();
        $this->client->request(
            'PUT',
            '/api/appliFilter/1',
            [],
            [],
            $this->mandatoryHeaders,
            '{
                "pdaLibelle": "Modification du libelle",
                "pdaTiroirs": ["G652660","G652670","G150050"],
                "pdaNbi": 12,
                "pdaNbc": 13
            }'
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data": {
                        "pdaId":1,
                        "pdaLibelle":"Modification du libelle",
                        "pdaTiroirs":["G652660","G652670","G150050"],
                        "pdaNbi": 12,
                        "pdaNbc": 13
                    }
                }'
            )
        );

        $this->client->request(
            'GET',
            '/api/appliFilter/1',
            [],
            [],
            $this->mandatoryHeaders
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data": {
                        "pdaId":1,
                        "pdaLibelle":"Modification du libelle",
                        "pdaTiroirs":["G652660","G652670","G150050"],
                        "pdaNbi": 12,
                        "pdaNbc": 13
                    }
                }'
            )
        );
    }

    /**
     * Teste la suppression d'un filtre applicatif
     */
    public function testDeleteApplicationFilter()
    {
        $this->client = static::makeclientWithLogin();
        $this->client->request(
            'DELETE',
            '/api/appliFilter/2',
            [],
            [],
            $this->mandatoryHeaders
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        $this->client->request(
            'GET',
            '/api/appliFilter',
            [],
            [],
            $this->mandatoryHeaders
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data":[{
                        "pdaId":1,
                        "pdaLibelle":"Documents collectifs paie et entreprise",
                        "pdaNbrUsers": "1"
                    }]
                }'
            )
        );
    }


    /**
     * Teste le renvoi de la liste d'utilisateurs affectés à un filtre applicatif
     */
    public function testGetUsersAction()
    {
        $this->client = static::makeclientWithLogin();
        $this->client->request(
            'GET', '/api/appliFilter/1/users',
            [],
            [],
            $this->mandatoryHeaders
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                "data": [{
                    "login": "MyUsrLogin01",
                    "nom": "MyUsrNom01",
                    "prenom": "MyUsrPrenom01"
                }]
            }')
        );
    }
}
