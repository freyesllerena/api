<?php
namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;

class BasketControllerTest extends DocapostWebTestCase
{
    /**
     * @var Client instance
     */
    private $client;

    const JSON_IFP_1 =
        '{
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
        }';

    const JSON_IFP_2 =
        '{
            "ifpId": 2,
            "ifpNombrepages": 1,
            "ifpIdCodeChrono": "",
            "ifpIdNumeroBoiteArchive": "",
            "ifpIdNomSociete": "SOCIETE DEMO PARIS",
            "ifpIdNomClient": "RSI CO",
            "ifpCodeClient": "TSI504",
            "ifpIdCodeSociete": "01",
            "ifpIdCodeEtablissement": "00001",
            "ifpIdLibEtablissement": "RSI TSI SIEGE PARIS",
            "ifpIdCodeJalon": null,
            "ifpIdLibelleJalon": "",
            "ifpIdNumSiren": "393555123",
            "ifpIdNumSiret": "39355512300057",
            "ifpIdIndiceClassement": "D256900",
            "ifpIdUniqueDocument": null,
            "ifpIdTypeDocument": null,
            "ifpIdLibelleDocument": "Changement \u00e9tat civil",
            "ifpCodeDocument": "D256900",
            "ifpIdFormatDocument": "PDFa",
            "ifpIdAuteurDocument": null,
            "ifpIdSourceDocument": "BVRH UPLOAD",
            "ifpIdNumVersionDocument": "1",
            "ifpIdPoidsDocument": "000000081403",
            "ifpIdNbrPagesDocument": null,
            "ifpIdProfilArchivage": null,
            "ifpIdEtatArchive": null,
            "ifpIdPeriodePaie": {
                "date": "2015-12-01 00:00:00.000000",
                "timezone_type": 3,
                "timezone": "Europe\/Paris"
            },
            "ifpIdPeriodeExerciceSociale": null,
            "ifpIdDateDernierAccesDocument": null,
            "ifpIdDateArchivageDocument": {
                "date": "2015-12-22 01:29:47.000000",
                "timezone_type": 3,
                "timezone": "Europe\/Paris"
            },
            "ifpIdDureeArchivageDocument": "20",
            "ifpIdDateFinArchivageDocument": {
                "date": "2035-12-01 00:00:00.000000",
                "timezone_type": 3,
                "timezone": "Europe\/Paris"
            },
            "ifpIdNomSalarie": "ACHENAIS",
            "ifpIdPrenomSalarie": "STEPHANE",
            "ifpIdNomJeuneFilleSalarie": null,
            "ifpIdDateNaissanceSalarie": null,
            "ifpIdDateEntree": {
                "date": "1996-01-01 00:00:00.000000",
                "timezone_type": 3,
                "timezone": "Europe\/Paris"
            },
            "ifpIdDateSortie": {
                "date": "2014-02-28 00:00:00.000000",
                "timezone_type": 3,
                "timezone": "Europe\/Paris"
            },
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
            "ifpNumMatricule": "01010234",
            "ifpIdNumMatriculeRh": "01010234",
            "ifpIdConteneur": "",
            "ifpIdBoite": "",
            "ifpIdLot": "",
            "ifpIdNumOrdre": "1",
            "ifpLogin": "expertms_DPS",
            "ifpModedt": "",
            "ifpNumdtr": "",
            "ifpIdCodeActivite": "",
            "ifpGeler": false,
            "ifpCycleTempsParcouru": null,
            "ifpCycleTempsRestant": null
        }';

    public function setUp()
    {
        $this->initTests(
            [
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadFolFolderData',
                'ApiBundle\DataFixtures\ORM\LoadFusFolderUserData',
                'ApiBundle\DataFixtures\ORM\LoadFdoFolderDocData',
                'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
                'ApiBundle\DataFixtures\ORM\LoadIfpIndexfichePaperlessData',
                'ApiBundle\DataFixtures\ORM\LoadRidRefIdData',
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
     * Teste le renvoi des documents du panier
     */
    public function testGetBasketDocument()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_basket_getbasketdocument');
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "items":['.self::JSON_IFP_1.','.$this->tools->jsonMinify(self::JSON_IFP_2).']
                }
            }'
        );
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : POST /basket/documents
     * UseCase1 : Essaye d'ajouter des documents lorsque l'utilisateur n'est pas authentifié
     * UseCase2 : Essaye d'ajouter des documents qui n'existent pas dans le panier
     * UseCase3 : Ajoute des documents dans un panier et vérifie si le document a bien été ajouté
     */
    public function testPostBasketDocument()
    {
        // UseCase 1
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": "Full authentication is required to access this resource."
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_basket_postbasketdocument');
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 403);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase 2
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 2,
                    "infos": [{
                        "code": "errIfpIndexfichePaperlessUserNotAllowed",
                        "values": {
                            "__value__": 20
                        },
                        "fieldName": ""
                    }, {
                        "code": "errIfpIndexfichePaperlessUserNotAllowed",
                        "values": {
                            "__value__": 21
                        },
                        "fieldName": ""
                    }]
                }
            }'
        );

        // Reconnection avec l'utilisateur MyUsrLogin02
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_basket_postbasketdocument');
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, '{"documentIds":[20,21]}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase 3
        $route = $this->getUrl('api_apiv1_basket_postbasketdocument');
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, '{"documentIds":[2]}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        $route = $this->getUrl('api_apiv1_basket_getbasketdocument');
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "items":['.self::JSON_IFP_2.']
                }
            }'
        );
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : DELETE /baskets/documents
     * UseCase : Supprime des documents dans un panier et vérifie s'ils sont bien supprimés dans les documents du panier
     */
    public function testDeleteBasketDocument()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_basket_deletebasketdocument');
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"documentIds":[2]}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_basket_getbasketdocument');
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "items":['.self::JSON_IFP_1.']
                }
            }'
        );
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testGetCountBasketDocument()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_basket_getcountbasketdocument');
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $expectedResponse = '{"nbrDocs":"2","lastAccess":null}';
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}
