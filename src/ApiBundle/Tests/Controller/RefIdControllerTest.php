<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class RefIdControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadRidRefIdData',
                'ApiBundle\DataFixtures\ORM\LoadRceRefCodeEtablissementData',
                'ApiBundle\DataFixtures\ORM\LoadRcsRefCodeSocieteData',
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
     * Test : GET /referentiel/pac
     * UseCase1 : retourne la liste des codes PAC
     *
     * @throws \Exception
     */
    public function testGetReferentialPac()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "TSI071": "Libell\u00e9 PAC TSI071",
                    "TSI501": "Libell\u00e9 PAC TSI501",
                    "TSI504": "Libell\u00e9 PAC TSI504"
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_refid_getreferentialpac');
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders, '{}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Teste une liste d'objets referential
     */
    public function testGetReferentialType()
    {
        $this->client = static::makeclientWithLogin('usrAutomate');

        $this->assertGetWillReturn(
            '/api/refIdCodeClient',
            '{
                "data": [{
                    "id": 1,
                    "code":"TSI504",
                    "libelle":"Libell\u00e9 PAC TSI504",
                    "idCodeClient":"TSI504"
                },{
                    "id": 2,
                    "code":"TSI071",
                    "libelle":"Libell\u00e9 PAC TSI071",
                    "idCodeClient":"TSI071"
                },{
                    "id": 3,
                    "code":"TSI501",
                    "libelle":"Libell\u00e9 PAC TSI501",
                    "idCodeClient":"TSI501"
                }]
            }'
        );

        $this->assertGetWillReturn(
            '/api/refIdCodeSociete',
            '{
                "data": [{
                    "id": 1,
                    "code":"000001",
                    "libelle":"Soci\u00e9t\u00e9 Test 01",
                    "siren":"900000001",
                    "idCodeClient":"TSI504"
                },{
                    "id": 2,
                    "code":"000009",
                    "libelle":"Soci\u00e9t\u00e9 Test 09",
                    "siren":"900000009",
                    "idCodeClient":"TSI504"
                },{
                    "id": 3,
                    "code":"000002",
                    "libelle":"Soci\u00e9t\u00e9 Test 02",
                    "siren":"900000002",
                    "idCodeClient":"TSI504"
                }]
            }'
        );
		
		$this->assertGetWillReturn(
            '/api/refIdCodeEtablissement',
            '{
				"data":[{
					"id":1,
					"code":"01",
					"libelle":"Etablissement 01",
					"idSociete":1
				},{
					"id":2,
					"code":"02",
					"libelle":"Etablissement 02",
					"idSociete":2
				}]}'
        );

        $this->assertGetWillReturn(
            '/api/refIdCodeJalon',
            '{
                "data": [{
                    "id": 5,
                    "code":"JA",
                    "libelle":"DESTINATAIRE N0 1",
                    "idCodeClient":"TSI504"
                }]
            }'
        );

        $this->assertGetWillReturn(
            '/api/refIdCodeCategSocioProf',
            '{
                    "data":[{
                        "id": 6,
                        "code":"05",
                        "libelle":"PDG",
                        "idCodeClient":"TSI504"
                    }]
                }'
        );

        $this->assertGetWillReturn(
            '/api/refIdTypeContrat',
            '{
                "data":[{
                    "id": 4,
                    "code":"06",
                    "libelle":"CONTRAT EMPLOI JEUNES",
                    "idCodeClient":"TSI504"
                }]
            }'
        );

        $this->assertGetWillReturn(
            '/api/refIdAffectation1',
            '{
                "data":[{
                    "id": 7,
                    "code":"01",
                    "libelle":"DIRECTION GENERALE",
                    "idCodeClient":"TSI504"
                }]
            }'
        );

        $this->assertGetWillReturn(
            '/api/refIdAffectation2',
            '{
                "data":[{
                    "id": 8,
                    "code":"0001",
                    "libelle":"DIRECTION",
                    "idCodeClient":"TSI504"
                }]
            }'
        );

        $this->assertGetWillReturn(
            '/api/refIdAffectation3',
            '{
                "data":[{
                    "id": 9,
                    "code":"01",
                    "libelle":"EUROPE PARIS",
                    "idCodeClient":"TSI504"
                }]
            }'
        );

        $this->assertGetWillReturn(
            '/api/refIdLibre1',
            '{
                "data":[{
                    "id": 10,
                    "code":"01",
                    "libelle":"LOREM IPSUM",
                    "idCodeClient":"TSI504"
                }]
            }'
        );

        $this->assertGetWillReturn(
            '/api/refIdLibre2',
            '{
                "data":[{
                    "id": 11,
                    "code":"01",
                    "libelle":"DOLOR SIT AMET",
                    "idCodeClient":"TSI504"
                }]
            }'
        );

        $this->assertGetWillReturn(
            '/api/refIdCodeActivite',
            '{
                "data":[{
                    "id": 12,
                    "code":"01",
                    "libelle":"CONSECTETUR ADSICIPING",
                    "idCodeClient":"TSI504"
                }]
            }'
        );
    }

    /**
     * Teste la création d'un objet referential
     *
     * @throws \Exception
     */
    public function testPostReferential()
    {
        $this->client = static::makeclientWithLogin();

        $this->assertPostWillReturn(
            '/api/refIdCodeClient',
            '{
                "code":"TSI505",
                "libelle":"nouveau id code client",
                "idCodeClient": "TSI505"
            }',
            '{
                "data": {
                    "id": 13,
                    "code":"TSI505",
                    "libelle":"nouveau id code client",
                    "idCodeClient":"TSI505"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdCodeSociete',
            '{
                "code":"999999",
                "libelle":"Nouveau code societe",
                "siren":"900999999",
                "idCodeClient":"TSI504"
             }',
            '{
                "data": {
                    "id": 4,
                    "code":"999999",
                    "libelle":"Nouveau code societe",
                    "siren":"900999999",
                    "idCodeClient":"TSI504"
                }
            }'
        );
		
		$this->assertPostWillReturn(
            '/api/refIdCodeEtablissement',
            '{
                "code":"999999",
                "libelle":"Nouveau code societe",
                "idSociete":1,
				"nic": 2
             }',
            '{
				"data":{
					"id":3,
					"code":"999999",
					"libelle":"Nouveau code societe",
					"idSociete":1,
					"nic":2
				}
			}'
        );

        $this->assertPostWillReturn(
            '/api/refIdCodeJalon',
            '{
                "code":"99",
                "libelle":"nouveau code jalon",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 14,
                    "code":"99",
                    "libelle":"nouveau code jalon",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdCodeCategSocioProf',
            '{
                "code":"99",
                "libelle":"nouveau code categ socio prof",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 15,
                    "code":"99",
                    "libelle":"nouveau code categ socio prof",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdTypeContrat',
            '{
                "code":"99",
                "libelle":"nouveau type contrat",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 16,
                    "code":"99",
                    "libelle":"nouveau type contrat",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdAffectation1',
            '{
                "code":"99",
                "libelle":"nouvel affectation 1",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 17,
                    "code":"99",
                    "libelle":"nouvel affectation 1",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdAffectation2',
            '{
                "code":"99",
                "libelle":"nouvel affectation 2",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 18,
                    "code":"99",
                    "libelle":"nouvel affectation 2",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdAffectation3',
            '{
                "code":"99",
                "libelle":"nouvel affectation 3",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 19,
                    "code":"99",
                    "libelle":"nouvel affectation 3",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdLibre1',
            '{
                "code":"99",
                "libelle":"nouveau libre 1",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 20,
                    "code":"99",
                    "libelle":"nouveau libre 1",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdLibre2',
            '{
                "code":"99",
                "libelle":"nouveau libre 2",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 21,
                    "code":"99",
                    "libelle":"nouveau libre 2",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPostWillReturn(
            '/api/refIdCodeActivite',
            '{
                "code":"99",
                "libelle":"nouveau code activite",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 22,
                    "code":"99",
                    "libelle":"nouveau code activite",
                    "idCodeClient":"TSI504"
                }
            }'
        );
    }

    /**
     * Teste la création d'un objet referential
     *
     * @throws \Exception
     */
    public function testPutReferential()
    {
        $this->client = static::makeclientWithLogin();

        $this->assertPutWillReturn(
            '/api/refIdCodeClient/1',
            '{
                "code":"TSI505",
                "libelle":"nouveau id code client",
                "idCodeClient": "TSI505"
            }',
            '{
                "data": {
                    "id": 1,
                    "code":"TSI505",
                    "libelle":"nouveau id code client",
                    "idCodeClient":"TSI505"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdCodeSociete/1',
            '{
                "code":"999999",
                "libelle":"Nouveau code societe",
                "siren":"900999999",
                "idCodeClient":"TSI504"
             }',
            '{
                "data": {
                    "id": 1,
                    "code":"999999",
                    "libelle":"Nouveau code societe",
                    "siren":"900999999",
                    "idCodeClient":"TSI504"
                }
            }'
        );
		
		$this->assertPutWillReturn(
            '/api/refIdCodeEtablissement/1',
            '{
                "code":"999999",
                "libelle":"Nouveau code etablissement",
                "idSociete":1,
				"nic": 2
             }',
            '{
				"data":{
					"id":1,
					"code":"999999",
					"libelle":"Nouveau code etablissement",
					"idSociete":1,
					"nic":2
				}
			}'
        );

        $this->assertPutWillReturn(
            '/api/refIdCodeJalon/5',
            '{
                "code":"99",
                "libelle":"nouveau code jalon",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 5,
                    "code":"99",
                    "libelle":"nouveau code jalon",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdCodeCategSocioProf/6',
            '{
                "code":"99",
                "libelle":"nouveau code categ socio prof",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 6,
                    "code":"99",
                    "libelle":"nouveau code categ socio prof",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdTypeContrat/4',
            '{
                "code":"99",
                "libelle":"nouveau type contrat",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 4,
                    "code":"99",
                    "libelle":"nouveau type contrat",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdAffectation1/7',
            '{
                "code":"99",
                "libelle":"nouvel affectation 1",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 7,
                    "code":"99",
                    "libelle":"nouvel affectation 1",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdAffectation2/8',
            '{
                "code":"99",
                "libelle":"nouvel affectation 2",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 8,
                    "code":"99",
                    "libelle":"nouvel affectation 2",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdAffectation3/9',
            '{
                "code":"99",
                "libelle":"nouvel affectation 3",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 9,
                    "code":"99",
                    "libelle":"nouvel affectation 3",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdLibre1/10',
            '{
                "code":"99",
                "libelle":"nouveau libre 1",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 10,
                    "code":"99",
                    "libelle":"nouveau libre 1",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdLibre2/11',
            '{
                "code":"99",
                "libelle":"nouveau libre 2",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 11,
                    "code":"99",
                    "libelle":"nouveau libre 2",
                    "idCodeClient":"TSI504"
                }
            }'
        );

        $this->assertPutWillReturn(
            '/api/refIdCodeActivite/12',
            '{
                "code":"99",
                "libelle":"nouveau code activite",
                "idCodeClient": "TSI504"
            }',
            '{
                "data": {
                    "id": 12,
                    "code":"99",
                    "libelle":"nouveau code activite",
                    "idCodeClient":"TSI504"
                }
            }'
        );
    }

    /**
     * Vérifie qu'un GET retourne une réponse attendue
     *
     * @param $url
     * @param $output
     */
    protected function assertGetWillReturn($url, $output) {
        $this->client->request(
            'GET',
            $url,
            [],
            [],
            $this->mandatoryHeaders,
            '{}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify($output)
        );
    }

    /**
     * Vérifie qu'un POST retourne une réponse attendue
     *
     * @param $url
     * @param $values
     * @param $output
     */
    protected function assertPostWillReturn($url, $values, $output) {
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            $this->mandatoryHeaders,
            $values
        );
        $response = $this->client->getResponse();
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify($output)
        );
    }

    /**
     * Vérifie qu'un POST retourne une réponse attendue
     *
     * @param $url
     * @param $values
     * @param $output
     */
    protected function assertPutWillReturn($url, $values, $output) {
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            $this->mandatoryHeaders,
            $values
        );
        $response = $this->client->getResponse();
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify($output)
        );
    }
}
