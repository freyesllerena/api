<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class FolderControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadFolFolderData',
                'ApiBundle\DataFixtures\ORM\LoadFusFolderUserData',
                'ApiBundle\DataFixtures\ORM\LoadFdoFolderDocData',
                'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
                'ApiBundle\DataFixtures\ORM\LoadIfpIndexfichePaperlessData',
                'ApiBundle\DataFixtures\ORM\LoadProProfilData',
                'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
                'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
                'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
                'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData'
            ]
        );

        $this->client = static::makeclientWithLogin();
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
     * Test : POST /folder
     * UseCase1 : Essaye d'ajouter un dossier dont le paramètre 'libellé' est manquant
     * UseCase2 : Essaye d'ajouter un dossier dont le libellé est vide
     * UseCase3 : Ajoute un dossier
     */
    public function testPostFolder()
    {
        // UseCase 1
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsMissing",
                        "values": {
                            "__parameter__": "folLibelle"
                        },
                        "fieldName": ""
                    }]
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_folder_postfolder');
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, '{}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase 2
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errFolFolderLabelEmpty",
                        "values": {
                            "__value__": ""
                        },
                        "fieldName": "folLibelle"
                    }]
                }
            }'
        );
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, '{"folLibelle":""}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase 3
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "folType": "FOL",
                    "folId": 5,
                    "folLibelle": "Mes Fiches de paie",
                    "folIdOwner": "MyUsrLogin01",
                    "folNbDoc": 0
                }
            }'
        );

        $this->client = static::makeclientWithLogin();
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, '{"folLibelle":"Mes Fiches de paie"}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : GET /folder/list
     * UseCase : Récupère la liste des dossiers d'un utilisateur
     */
    public function testGetFoldersList()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "folId": 1,
                    "folLibelle": "Mon Dossier Test",
                    "folIdOwner": "MyUsrLogin01",
                    "folNbDoc": 5
                }]
            }'
        );
        $route = $this->getUrl('api_apiv1_folder_getfolderslist');
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : PUT /folder
     * UseCase1 : Essaye de mettre à jour un dossier qui n'appartient pas à l'utilisateur
     * UseCase2 : Met à jour un dossier
     */
    public function testPutFolder()
    {
        // UseCase 1
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errFolFolderNotOwner",
                        "values": {
                            "__label__": "Attestations Test"
                        },
                        "fieldName": ""
                    }]
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_folder_putfolder');
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            '{"folId":2,"folLibelle":"Mes Documents Personnels"}'
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 403);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase 2
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "folType": "FOL",
                    "folId": 1,
                    "folLibelle": "Mes Documents Personnels",
                    "folIdOwner": "MyUsrLogin01",
                    "folNbDoc": 5
                }
            }'
        );
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            '{"folId":1,"folLibelle":"Mes Documents Personnels"}'
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : DELETE /folder
     * UseCase : Supprime un dossier
     */
    public function testDeleteFolder()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_folder_deletefolder');
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"folId":1}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test : POST /folder/documents
     * UseCase1 : Essaye d'ajouter des documents qui n'existent pas dans un dossier
     * UseCase2 : Ajoute des documents dans un dossier
     */
    public function testPostFolderDocument()
    {
        // UseCase 1
        $this->client = static::makeclientWithLogin();
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
        $route = $this->getUrl('api_apiv1_folder_postfolderdocument');
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, '{"folId":1,"documentIds":[20,21]}');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase 2
        $this->client->request('POST', $route, [], [], $this->mandatoryHeaders, '{"folId":1,"documentIds":[4,5]}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test : DELETE /folder/documents
     * UseCase : Supprime des documents dans un dossier
     */
    public function testDeleteFolderDocument()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_folder_deletefolderdocument');
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"folId":1,"documentIds":[2,4]}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test : POST /folder/user
     * UseCase1 : Essaye d'ajouter une relation entre un dossier et un utilisateur
     * UseCase2 : Ajoute une relation entre un dossier et un utilisateur
     */
    public function testPostFolderUser()
    {
        // UseCase 1
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerInternalError",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_folder_postfolderuser');
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            '{"folId":1,"userIds":["MyUsrLogin03","MyUsrLogin99"]}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase 2
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            '{"folId":1,"userIds":["MyUsrLogin03"]}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test : DELETE /folder/user
     * UseCase : Retire une relation entre un dossier et un utilisateur
     */
    public function testDeleteFolderUser()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_folder_deletefolderuser');
        $this->client->request(
            'DELETE',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            '{"folId":1,"userId":"MyUsrLogin02"}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test : GET /folder/{id}/list/users/share
     * UseCase : Affiche la liste des utilisateurs pour lesquels le dossier est partagé
     */
    public function testGetUsersListIsSharedFolder()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = '{"data":["MyUsrLogin02"]}';
        $route = $this->getUrl('api_apiv1_folder_getuserslistissharedfolder', ['folId' => 1]);
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : GET /folder/{id}/list/users/share
     * UseCase : Affiche la liste des utilisateurs pour lesquels le dossier n'est pas partagé
     */
    public function testGetUsersListIsUnsharedFolder()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = '{"data":["MyUsrLogin03"]}';
        $route = $this->getUrl('api_apiv1_folder_getuserslistisunsharedfolder', ['folId' => 1]);
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}
