<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class AnnotationControllerTest
 * @package ApiBundle\Tests\Controller
 */
class AnnotationControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadAnoAnnotationsData',
                'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
                'ApiBundle\DataFixtures\ORM\LoadIfpIndexfichePaperlessData',
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadAdoAnnotationsDossierData',
                'ApiBundle\DataFixtures\ORM\LoadFolFolderData',
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
     * Test : GET /annotation/document/{documentId}/list
     * UseCase : Request pour afficher la liste des annotations d'un document
     */
    public function testGetDocumentAnnotationsList()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "anoId": 6,
                    "anoStatut": "PUBLIQUE",
                    "anoTexte": "myAnnotation - 6",
                    "anoLogin": {
                        "usrLogin": "MyUsrLogin01",
                        "usrNom": "MyUsrNom01",
                        "usrPrenom": "MyUsrPrenom01"
                    }
                },
                {
                    "anoId": 2,
                    "anoStatut": "PUBLIQUE",
                    "anoTexte": "myAnnotation - 2",
                    "anoLogin": {
                        "usrLogin": "MyUsrLogin02",
                        "usrNom": "MyUsrNom02",
                        "usrPrenom": "MyUsrPrenom02"
                    }
                },
                {
                    "anoId": 1,
                    "anoStatut": "PRIVEE",
                    "anoTexte": "myAnnotation - 1",
                    "anoLogin": {
                        "usrLogin": "MyUsrLogin01",
                        "usrNom": "MyUsrNom01",
                        "usrPrenom": "MyUsrPrenom01"
                    }
                }]
            }'
        );
        $route = $this->getUrl('api_apiv1_annotation_getdocumentannotationslist', ['documentId' => 1]);

        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $result = @json_decode($response->getContent(), true);
        unset($result['data'][0]['anoCreatedAt']);
        unset($result['data'][1]['anoCreatedAt']);
        unset($result['data'][2]['anoCreatedAt']);
        $actualResponse = @json_encode($result);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * Test : GET /annotation/folder/{folderId}/list
     * UseCase : Request pour afficher la liste des annotations d'un dossier
     */
    public function testGetFolderAnnotationsList()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "adoId": 6,
                    "adoStatut": "PUBLIQUE",
                    "adoTexte": "myAnnotation dossier - 6",
                    "adoLogin": {
                        "usrLogin": "MyUsrLogin01",
                        "usrNom": "MyUsrNom01",
                        "usrPrenom": "MyUsrPrenom01"
                    }
                },
                {
                    "adoId": 2,
                    "adoStatut": "PUBLIQUE",
                    "adoTexte": "myAnnotation dossier - 2",
                    "adoLogin": {
                        "usrLogin": "MyUsrLogin02",
                        "usrNom": "MyUsrNom02",
                        "usrPrenom": "MyUsrPrenom02"
                    }
                },
                {
                    "adoId": 1,
                    "adoStatut": "PRIVEE",
                    "adoTexte": "myAnnotation dossier - 1",
                    "adoLogin": {
                        "usrLogin": "MyUsrLogin01",
                        "usrNom": "MyUsrNom01",
                        "usrPrenom": "MyUsrPrenom01"
                    }
                }]
            }'
        );
        $route = $this->getUrl('api_apiv1_annotation_getfolderannotationslist', ['folderId' => 1]);
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $result = @json_decode($response->getContent(), true);
        unset($result['data'][0]['adoCreatedAt']);
        unset($result['data'][1]['adoCreatedAt']);
        unset($result['data'][2]['adoCreatedAt']);
        $actualResponse = @json_encode($result);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * Test : DELETE /document/annotation
     * UseCase1 : Request pour supprimer une annotation dont le paramètre "anoId" est manquant
     * UseCase2 : Request pour supprimer une annotation qui n'existe pas
     * UseCase3 : Request pour supprimer une annotation dont l'utilisateur n'est pas propriétaire
     * UseCase4 : Request pour supprimer une annotation qui n'est pas la dernière
     * UseCase5 : Request pour supprimer une annotation
     */
    public function testDeleteDocumentAnnotation()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerObjectContentIncorrect",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );

        $route = $this->getUrl('api_apiv1_annotation_deletedocumentannotation');
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{"code": "errAnoAnnotationsDoesNotExist", "values": [], "fieldName": ""}]
                }
            }'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"anoId":9}');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{"msg": {"level": 3, "infos": [{"code": "errAnoAnnotationsNotOwner", "values": [], "fieldName": ""}]}}'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"anoId":2}');
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{"code": "errAnoAnnotationsNotAllowedToDelete", "values": [], "fieldName": ""}]
                }
            }'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"anoId":1}');
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"anoId":6}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test : DELETE /folder/annotation
     * UseCase1 : Request pour supprimer une annotation dont le paramètre "adoId" est manquant
     * UseCase2 : Request pour supprimer une annotation qui n'existe pas
     * UseCase3 : Request pour supprimer une annotation dont l'utilisateur n'est pas propriétaire
     * UseCase4 : Request pour supprimer une annotation qui n'est pas la dernière
     * UseCase5 : Request pour supprimer un annotation
     */
    public function testDeleteFolderAnnotation()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsMissing",
                        "values": {"__parameter__": "adoId"},
                        "fieldName": ""
                    }]
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_annotation_deletefolderannotation');

        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{}');
        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{"msg": {"level": 3, "infos": [{"code": "errAnoAnnotationsDoesNotExist", "values": [], "fieldName": ""}]}}'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"adoId":9}');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{"msg": {"level": 3, "infos": [{"code": "errAnoAnnotationsNotOwner", "values": [], "fieldName": ""}]}}'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"adoId":2}');
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{"code": "errAnoAnnotationsNotAllowedToDelete", "values": [], "fieldName": ""}]
                }
            }'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"adoId":1}');
        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        $this->client = static::makeclientWithLogin();
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"adoId":6}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test : POST /document/annotation
     * UseCase1 : Request pour ajouter une annotation dont le paramètre "anoFiche" est vide
     * UseCase2 : Request pour ajouter une annotation sur un document qui n'existe pas
     * UseCase3 : Request pour ajouter une annotation dont le statut est erroné
     * UseCase4 : Request pour ajouter une annotation
     */
    public function testPostDocumentAnnotation()
    {
        // UseCase1 : Request pour ajouter une annotation dont le paramètre "anoFiche" est vide
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsEmpty",
                        "values": {"__parameter__": "anoFiche"},
                        "fieldName": ""
                    }]
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_annotation_postdocumentannotation');
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{"anoTexte":"Test d\'ajout d\'annotation sur un document","anoStatut":"PRIVEE","anoFiche":""}'
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase2 : Request pour ajouter une annotation sur un document qui n'existe pas
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errAnoAnnotationsIncorrectValue",
                        "values": {"__value__": "\"ERROR\""},
                        "fieldName": "anoStatut"
                    }]
                }
            }'
        );
        $this->client = static::makeclientWithLogin();
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{"anoTexte":"Test d\'ajout d\'annotation sur un document","anoStatut":"ERROR","anoFiche":2}'
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase3 : Request pour ajouter une annotation dont le statut est erroné
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errIfpIndexfichePaperlessUserNotAllowed",
                        "values": {
                            "__value__": 100
                        },
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{"anoTexte":"Test d\'ajout d\'annotation sur un document","anoStatut":"PRIVEE","anoFiche":100}'
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase4 : Request pour ajouter une annotation
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "anoId": 7,
                    "anoEtat": "ACTIVE",
                    "anoStatut": "PRIVEE",
                    "anoTexte": "Test d\'ajout d\'annotation sur un document"
                }
            }'
        );
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{"anoTexte":"Test d\'ajout d\'annotation sur un document","anoStatut":"PRIVEE","anoFiche":2}'
        ));
        $response = $this->client->getResponse();
        $result = @json_decode($response->getContent(), true);
        unset($result['data']['anoCreatedAt']);
        unset($result['data']['anoUpdatedAt']);
        $actualResponse = @json_encode($result);
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * Test : POST /document/annotation
     * UseCase1 : Request pour ajouter une annotation dont le paramètre "adoFolder" est vide
     * UseCase2 : Request pour ajouter une annotation sur un dossier qui n'existe pas
     * UseCase3 : Request pour ajouter une annotation dont le statut est erroné
     * UseCase3 : Request pour ajouter une annotation
     */
    public function testPostFolderAnnotation()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsEmpty",
                        "values": {"__parameter__": "adoFolder"},
                        "fieldName": ""
                    }]
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_annotation_postfolderannotation');
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{"adoTexte":"Test d\'ajout d\'annotation sur un dossier","adoStatut":"PRIVEE","adoFolder":""}'
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errAnoAnnotationsIncorrectValue",
                        "values": {"__value__": "\"ERROR\""},
                        "fieldName": "adoStatut"
                    }]
                }
            }'
        );
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{"adoTexte":"Test d\'ajout d\'annotation sur un dossier","adoStatut":"ERROR","adoFolder":2}'
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errFolFolderDoNotExist",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{"adoTexte":"Test d\'ajout d\'annotation sur un dossier","adoStatut":"PRIVEE","adoFolder":10}'
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $this->assertEquals($expectedResponse, $response->getContent());

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "adoId": 7,
                    "adoEtat": "ACTIVE",
                    "adoStatut": "PRIVEE",
                    "adoTexte": "Test d\'ajout d\'annotation sur un dossier"
                }
            }'
        );
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, $this->tools->jsonMinify(
            '{"adoTexte":"Test d\'ajout d\'annotation sur un dossier","adoStatut":"PRIVEE","adoFolder":2}'
        ));
        $response = $this->client->getResponse();
        $result = @json_decode($response->getContent(), true);
        unset($result['data']['adoCreatedAt']);
        unset($result['data']['adoUpdatedAt']);
        $actualResponse = @json_encode($result);
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
