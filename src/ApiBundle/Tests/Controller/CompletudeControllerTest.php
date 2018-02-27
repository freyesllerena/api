<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class CompletudeControllerTest
 * @package ApiBundle\Tests\Controller
 */
class CompletudeControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadComCompletudeData',
                'ApiBundle\DataFixtures\ORM\LoadCtyCompletudeTypeData',
                'ApiBundle\DataFixtures\ORM\LoadIfpIndexfichePaperlessData',
                'ApiBundle\DataFixtures\ORM\LoadIinIdxIndivData',
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
     * Tests : POST /completude
     */
    public function testPostCompletude()
    {
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_completude_postcompletude');

        // UseCase1 : Request pour ajouter une complétude dont le paramètre tiroir est erroné
        $this->postCompletudeUseCase01($route);

        //UseCase2 : Request pour ajouter une complétude dont un tiroir est collectif
        $this->postCompletudeUseCase02($route);

        // UseCase3 : Request pour ajouter une complétude avec deux tiroirs
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin01');
        $this->postCompletudeUseCase03($route);
    }

    /**
     * UseCase1 : Request pour ajouter une complétude dont le paramètre tiroir est erroné
     * @param $route
     */
    private function postCompletudeUseCase01($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterTypeIsNotAnArray",
                        "values": {"__parameter__": "ctyType", "__value__": ""},
                        "fieldName": ""
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
                    "comLibelle": "Ma nouvelle complétude",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [],
                    "comPeriode": "",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "ctyType": ""
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request pour ajouter une complétude dont un tiroir est collectif
     * @param $route
     */
    private function postCompletudeUseCase02($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errTypTypeIsNotIndividual",
                        "values": {"__value__": "D325800"},
                        "fieldName": "ctyType"
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "comLibelle": "Ma nouvelle complétude",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [],
                    "comPeriode": "",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "ctyType": [
                        "D150010",
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
     * UseCase3 : Request pour ajouter une complétude avec deux tiroirs
     * @param $route
     */
    private function postCompletudeUseCase03($route)
    {
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "comIdCompletude": 4,
                    "comLibelle": "Ma nouvelle compl\u00e9tude",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [""],
                    "comPeriode": "quotidien",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "comNotification": "EnAttente"
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "comLibelle": "Ma nouvelle complétude",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [],
                    "comPeriode": "",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "ctyType": [
                        "D150010",
                        "D256900"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : PUT /completude
     */
    public function testPutCompletude()
    {
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_completude_putcompletude');

        // UseCase1 : Request pour modifier une complétude dont le paramètre "comLibelle" est manquant
        $this->putCompletudeUseCase01($route);

        // UseCase2 : Request pour modifier une complétude qui n'est pas celle de l'utilisateur
        $this->putCompletudeUseCase02($route);

        // UseCase3 : Request pour modifier une complétude dont le libellé existe déjà pour une autre complétude
        $this->putCompletudeUseCase03($route);

        // UseCase4 : Request pour modifier une complétude et n'ajouter qu'un tiroir
        $this->putCompletudeUseCase04($route);

    }

    /**
     * UseCase1 : Request pour modifier une complétude dont le paramètre "comLibelle" est manquant
     * @param $route
     */
    private function putCompletudeUseCase01($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsMissing",
                        "values": {"__parameter__":"comLibelle"},
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
                    "comIdCompletude": 1,
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [],
                    "comPeriode": "",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "ctyType": [
                        "D465200"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request pour modifier une complétude qui n'est pas celle de l'utilisateur
     * @param $route
     */
    private function putCompletudeUseCase02($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errComCompletudeNotOwner",
                        "values": {"__label__": "La compl\u00e9tude de test de MyUsrLogin01"},
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "comIdCompletude": 1,
                    "comLibelle": "La complétude que je modifie",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [],
                    "comPeriode": "",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "ctyType": [
                        "D465200"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 403);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase3 : Request pour modifier une complétude dont le libellé existe déjà pour une autre complétude
     * @param $route
     */
    private function putCompletudeUseCase03($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errComCompletudeLabelExists",
                        "values": {"__label__": "La deuxi\u00e8me compl\u00e9tude de test de MyUsrLogin02"},
                        "fieldName":""
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "comIdCompletude": 2,
                    "comLibelle": "La deuxième complétude de test de MyUsrLogin02",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [],
                    "comPeriode": "",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "ctyType": [
                        "D465200"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase4 : Request pour modifier une complétude et n'ajouter qu'un tiroir
     * @param $route
     */
    private function putCompletudeUseCase04($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "comIdCompletude": 3,
                    "comLibelle": "Ma deuxi\u00e8me compl\u00e9tude modifi\u00e9e",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [
                        "alexandre@symfony.com",
                        "nicolas@symfony.com"
                    ],
                    "comPeriode": "quotidien",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "comNotification": "EnAttente"
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "comIdCompletude": 3,
                    "comLibelle": "Ma deuxième complétude modifiée",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [
                        "alexandre@symfony.com",
                        "nicolas@symfony.com"
                    ],
                    "comPeriode": "",
                    "comAvecDocuments": true,
                    "comPopulation": "ALL_POP",
                    "ctyType": [
                        "D465200"
                    ]
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : DELETE /completude
     * UseCase1 : Request pour supprimer une complétude dont le paramètre "comIdCompletude" est manquant
     * UseCase2 : Request pour supprimer une complétude qui n'existe pas
     * UseCase3 : Request pour supprimer une complétude dont l'utilisateur n'est pas propriétaire
     * UseCase4 : Request pour supprimer une complétude
     */
    public function testDeleteCompletude()
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsMissing",
                        "values": {"__parameter__":"comIdCompletude"},
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_completude_deletecompletude');
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errComCompletudeDoesNotExist",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"comIdCompletude":90}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errComCompletudeNotOwner",
                        "values": {"__label__":"La compl\u00e9tude de test de MyUsrLogin01"},
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"comIdCompletude":1}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 403);
        $this->assertEquals($expectedResponse, $response->getContent());

        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"comIdCompletude":3}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test : GET /completude/list
     * UseCase : Request pour récupérer la liste des complétudes d'un utilisateur
     */
    public function testGetCompletudeList()
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "comIdCompletude": 2,
                    "comLibelle": "La compl\u00e9tude de test de MyUsrLogin02",
                    "comPrivee": true,
                    "comAuto": false,
                    "comEmail": [""],
                    "comPeriode": "hebdomadaire",
                    "comAvecDocuments": false,
                    "comPopulation": "PRE_POP",
                    "ctyType": ["D465200"],
                    "comUser": {
                        "usrLogin": "MyUsrLogin02",
                        "usrNom": "MyUsrNom02",
                        "usrPrenom": "MyUsrPrenom02"
                    }
                }, {
                    "comIdCompletude": 3,
                    "comLibelle": "La deuxi\u00e8me compl\u00e9tude de test de MyUsrLogin02",
                    "comPrivee": false,
                    "comAuto": true,
                    "comEmail": [
                        "alexandre@symfony.com",
                        "thomas@symfony.com",
                        "fabien@symfony.com"
                    ],
                    "comPeriode": "mensuel",
                    "comAvecDocuments": false,
                    "comPopulation": "OUT_POP",
                    "ctyType": ["D150010"],
                    "comUser": {
                        "usrLogin": "MyUsrLogin02",
                        "usrNom": "MyUsrNom02",
                        "usrPrenom": "MyUsrPrenom02"
                    }
                }]
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_completude_getcompletudeslist');
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test : POST /completude/search
     */
    public function testPostCompletudeSearch()
    {
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_completude_postcompletudesearch');

        // UseCase1 :
        // Request pour récupérer la liste des documents d'une complétude dont le paramètre "comPopulation"
        // n'est pas initialisé
        $this->postCompletudeSearchUseCase01($route);

        // UseCase2 : Request pour récupérer la liste des documents d'une complétude qui n'existe pas
        $this->postCompletudeSearchUseCase02($route);

        // UseCase3 : Request pour récupérer la liste des documents d'une complétude dont l'utilisateur
        // n'est pas propriétaire
        $this->postCompletudeSearchUseCase03($route);

        // UseCase4 : Request pour récupérer la liste des documents d'une complétude (complétude avec documents)
        $this->postCompletudeSearchUseCase04($route);

        // UseCase5 : Request pour récupérer la liste des salariés d'une complétude (complétude sans document)
        $this->postCompletudeSearchUseCase05($route);
    }

    /**
     * UseCase1 :
     * Request pour récupérer la liste des documents d'une complétude dont le paramètre "comPopulation"
     * n'est pas initialisé
     * @param $route
     */
    private function postCompletudeSearchUseCase01($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsMissing",
                        "values": {"__parameter__":"population"},
                        "fieldName": ""
                    }]
                }
            }'
        );

        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{
                "node": {
                    "type": "COMPLETUDE",
                    "withoutDoc": true,
                    "value": 2
                },
                "start": 0,
                "limit": 100,
                "fields": {
                    "iinIdNomSalarie": {
                        "dir": "DESC"
                    }
                }
            }'
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request pour récupérer la liste des documents d'une complétude qui n'existe pas
     * @param $route
     */
    private function postCompletudeSearchUseCase02($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errComCompletudeDoesNotExist",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "node": {
                        "type": "COMPLETUDE",
                        "population": "ALL_POP",
                        "withoutDoc": false,
                        "value": 20
                    },
                    "start": 0,
                    "limit": 100,
                    "fields": {
                        "iinIdNomSalarie": {
                            "dir": "DESC"
                        }
                    }
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase3 : Request pour récupérer la liste des documents d'une complétude dont l'utilisateur
     * n'est pas propriétaire
     * @param $route
     */
    private function postCompletudeSearchUseCase03($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errComCompletudeNotOwner",
                        "values": {"__label__": "La compl\u00e9tude de test de MyUsrLogin01"},
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "node": {
                        "type": "COMPLETUDE",
                        "population": "ALL_POP",
                        "withoutDoc": false,
                        "value": 1
                    },
                    "start": 0,
                    "limit": 100,
                    "fields": {
                        "iinIdNomSalarie": {
                            "dir": "DESC"
                        }
                    }
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 403);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase4 : Request pour récupérer la liste des documents d'une complétude (complétude avec documents)
     * @param $route
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    private function postCompletudeSearchUseCase04($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "items": [{
                        "ifpId": 1,
                        "ifpNombrepages": 1,
                        "ifpIdCodeChrono": "",
                        "ifpIdNumeroBoiteArchive": "",
                        "ifpIdNomSociete": "POLYCLINIQUE ST FRANCOIS SAINT ANTOINE",
                        "ifpIdNomClient": "VITALIA CO",
                        "ifpCodeClient": "TSI504",
                        "ifpIdCodeSociete": "01",
                        "ifpIdCodeEtablissement": "01001",
                        "ifpIdLibEtablissement": "POLYCLINIQUE ST FRANCOIS SAINT ANTOINE",
                        "ifpIdCodeJalon": null,
                        "ifpIdLibelleJalon": "",
                        "ifpIdNumSiren": "917250151",
                        "ifpIdNumSiret": "91725015100029",
                        "ifpIdIndiceClassement": "D150010",
                        "ifpIdUniqueDocument": null,
                        "ifpIdTypeDocument": null,
                        "ifpIdLibelleDocument": "Justificatifs adresse",
                        "ifpCodeDocument": "D150010",
                        "ifpIdFormatDocument": "PDFa",
                        "ifpIdAuteurDocument": null,
                        "ifpIdSourceDocument": "BVRH UPLOAD",
                        "ifpIdNumVersionDocument": "1",
                        "ifpIdPoidsDocument": "000000149251",
                        "ifpIdNbrPagesDocument": null,
                        "ifpIdProfilArchivage": null,
                        "ifpIdEtatArchive": null,
                        "ifpIdPeriodePaie": {
                            "date": "2015-10-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdPeriodeExerciceSociale": null,
                        "ifpIdDateDernierAccesDocument": null,
                        "ifpIdDateArchivageDocument": {
                            "date": "2015-10-29 17:46:14.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDureeArchivageDocument": "30",
                        "ifpIdDateFinArchivageDocument": {
                            "date": "2045-10-29 17:46:14.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdNomSalarie": "ALLAIGRE",
                        "ifpIdPrenomSalarie": "CARINE",
                        "ifpIdNomJeuneFilleSalarie": null,
                        "ifpIdDateNaissanceSalarie": {
                            "date": "1972-12-13 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDateEntree": {
                            "date": "1999-09-02 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDateSortie": null,
                        "ifpIdNumNir": null,
                        "ifpIdCodeCategProfessionnelle": null,
                        "ifpIdCodeCategSocioProf": null,
                        "ifpIdTypeContrat": null,
                        "ifpIdAffectation1": null,
                        "ifpIdAffectation2": null,
                        "ifpIdAffectation3": null,
                        "ifpIdLibre1": null,
                        "ifpIdLibre2": null,
                        "ifpIdAffectation1Date": null,
                        "ifpIdAffectation2Date": null,
                        "ifpIdAffectation3Date": null,
                        "ifpIdLibre1Date": null,
                        "ifpIdLibre2Date": null,
                        "ifpNumMatricule": "01000002",
                        "ifpIdNumMatriculeRh": "01000002",
                        "ifpIdConteneur": "",
                        "ifpIdBoite": "",
                        "ifpIdLot": "",
                        "ifpIdNumOrdre": "1",
                        "ifpLogin": "expertms_DPS",
                        "ifpModedt": "",
                        "ifpNumdtr": "",
                        "ifpIdCodeActivite": "",
                        "ifpGeler": true,
                        "ifpCycleTempsParcouru": null,
                        "ifpCycleTempsRestant": null
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "node": {
                        "type": "COMPLETUDE",
                        "population": "PRE_POP",
                        "withoutDoc": false,
                        "value": 3
                    },
                    "start": 0,
                    "limit": 100,
                    "fields": {
                        "ifpIdLibelleDocument": {
                            "dir": "ASC"
                        }
                    }
                }'
            )
        );
        $response = $this->client->getResponse();
        $result = @json_decode($response->getContent(), true);
        unset($result['data']['items'][0]['ifpCreatedAt']);
        unset($result['data']['items'][0]['ifpUpdatedAt']);
        $actualResponse = @json_encode($result);
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * UseCase5 : Request pour récupérer la liste des salariés d'une complétude (complétude sans document)
     * @param $route
     */
    private function postCompletudeSearchUseCase05($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "totalCount": "2",
                    "items": [{
                        "ctyType": "D150010",
                        "iinIdNomSalarie": "EUTEBERT",
                        "iinIdPrenomSalarie": "EVELYNE",
                        "iinIdNomJeuneFilleSalarie": "",
                        "iinIdDateSortie": null,
                        "iinIdNumMatriculeRh": "01000352"
                    }, {
                        "ctyType": "D150010",
                        "iinIdNomSalarie": "MARTIN",
                        "iinIdPrenomSalarie": "PHILIPPE",
                        "iinIdNomJeuneFilleSalarie": "",
                        "iinIdDateSortie": null,
                        "iinIdNumMatriculeRh": "01000123"
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "node": {
                        "type": "COMPLETUDE",
                        "population": "PRE_POP",
                        "withoutDoc": true,
                        "value": 3
                    },
                    "start": 0,
                    "limit": 100,
                    "fields": {
                        "iinIdNomSalarie": {
                            "dir": "DESC"
                        }
                    }
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}
