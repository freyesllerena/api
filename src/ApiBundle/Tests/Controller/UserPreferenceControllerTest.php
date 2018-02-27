<?php
namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;

class UserPreferenceControllerTest extends DocapostWebTestCase
{
    /**
     * @var Client instance
     */
    private $client;

    /**
     * Initialisationgi
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
     * Teste la sécurité sur les web services utilisateurs
     */
    public function testPutUserPreference()
    {
        $this->client = static::makeclientWithLogin();

        # Cas 1 : paramètres manquants
        $route = $this->getUrl('api_apiv1_userpreference_putuserpreference');
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            $this->mandatoryHeaders,
            '{}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 400);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "msg":{
                        "level":3,
                        "infos":[{
                            "code":"errDocapostControllerParameterIsMissing",
                            "values":{"__parameter__":"uprDevice"},
                            "fieldName":""
                        },{
                            "code":"errDocapostControllerParameterIsMissing",
                            "values":{"__parameter__":"uprType"},
                            "fieldName":""
                        },{
                            "code":"errDocapostControllerParameterIsMissing",
                            "values":{"__parameter__":"uprData"},"fieldName":""
                        }]
                    }
                }'
            )
        );

        # Cas 2 : le paramètre "device" est invalide
        $route = $this->getUrl('api_apiv1_userpreference_putuserpreference');
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            $this->mandatoryHeaders,
            '{"uprDevice":"MMMM","uprType":"1234", "uprData":"MPQLQSW02=2àL!so)ze+"}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 400);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "msg":{
                        "level":3,
                        "infos":[{
                            "code":"errDocapostControllerParameterTypeIsIncorrect",
                            "values":{
                                "__parameter__":"uprDevice",
                                "__value__":"MMMM"
                            },
                            "fieldName":""}
                        ]}
                }'
            )
        );

        # Cas 3 :  tous les paramètres sont valides
        $route = $this->getUrl('api_apiv1_userpreference_putuserpreference');
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            $this->mandatoryHeaders,
            '{"uprDevice":"DES", "uprType":"1234", "uprData":"serialized_object"}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data":{
                        "uprUser": "MyUsrLogin01",
                        "uprDevice": "DES",
                        "uprType": "1234",
                        "uprData": "serialized_object"
                    }
                }'
            )
        );

        $route = $this->getUrl('api_apiv1_userpreference_putuserpreference');
        $this->client->request(
            'PUT',
            $route,
            array(),
            array(),
            $this->mandatoryHeaders,
            '{"uprDevice":"DES", "uprType":"1234", "uprData":"another_serialized_object"}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify(
                '{
                    "data":{
                        "uprUser": "MyUsrLogin01",
                        "uprDevice": "DES",
                        "uprType": "1234",
                        "uprData": "another_serialized_object"
                    }
                }'
            )
        );

    }
}
