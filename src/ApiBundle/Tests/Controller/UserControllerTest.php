<?php
namespace ApiBundle\Tests\Controller;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadConConfigData',
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
     * Teste la sécurité sur les web services utilisateurs
     */
    public function testSecurity()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_user_getuserlist');
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 403);

        $route = $this->getUrl('api_apiv1_user_getuser', array('user' => base64_encode('MyUsrLogin01')));
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 403);

        $route = $this->getUrl('api_apiv1_user_putuser', array('user' => base64_encode('MyUsrLogin01')));
        $this->client->request('PUT', $route, array(), array(), $this->mandatoryHeaders);
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 403);

        $route = $this->getUrl('api_apiv1_user_deleteuser', array('user' => base64_encode('MyUsrLogin01')));
        $this->client->request('DELETE', $route, array(), array(), $this->mandatoryHeaders);
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 403);
    }

    /**
     * Teste la liste des utilisateurs
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testGetUserList()
    {
        $this->changeUserRole('MyUsrLogin01', 'chef de file');

        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_user_getuserlist');
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [
                    {
                        "usrId": "' . base64_encode("MyUsrLogin01") . '",
                        "usrLogin": "MyUsrLogin01",
                        "usrNom": "MyUsrNom01",
                        "usrPrenom": "MyUsrPrenom01",
                        "usrEmail": "",
                        "usrTypeHabilitation": "chef de file",
                        "usrNbrFiltresApplicatifs": 1,
                        "usrNbrFiltresPopulations": 1,
                        "usrActif": true,
                        "usrAuthorizations": {
                            "rightAnnotationDoc": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "rightAnnotationDossier": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "rightClasser": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "rightCycleDeVie": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "rightRechercheDoc": {
                                "R": true,
                                "W": false,
                                "D": false
                            },
                            "rightRecycler": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "rightUtilisateurs": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "accessExportCel": false,
                            "accessImportUnitaire": false
                        }
                    },
                    {
                        "usrId": "' . base64_encode("MyUsrLogin02") . '",
                        "usrLogin": "MyUsrLogin02",
                        "usrNom": "MyUsrNom02",
                        "usrPrenom": "MyUsrPrenom02",
                        "usrEmail": "myusrnom02@test.fr",
                        "usrTypeHabilitation": "chef de file expert",
                        "usrNbrFiltresApplicatifs": 1,
                        "usrNbrFiltresPopulations": 1,
                        "usrActif": true,
                        "usrAuthorizations": {
                            "rightAnnotationDoc": {
                                "R": true,
                                "W": true,
                                "D": true
                            },
                            "rightAnnotationDossier": {
                                "R": true,
                                "W": true,
                                "D": true
                            },
                            "rightClasser": {
                                "R": true,
                                "W": true,
                                "D": true
                            },
                            "rightCycleDeVie": {
                                "R": true,
                                "W": true,
                                "D": false
                            },
                            "rightRechercheDoc": {
                                "R": true,
                                "W": true,
                                "D": true
                            },
                            "rightRecycler": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "rightUtilisateurs": {
                                "R": true,
                                "W": true,
                                "D": false
                            },
                            "accessExportCel": true,
                            "accessImportUnitaire": true
                        }
                    },
                    {
                        "usrId": "' . base64_encode("MyUsrLogin03") . '",
                        "usrLogin": "MyUsrLogin03",
                        "usrNom": "MyUsrNom03",
                        "usrPrenom": "MyUsrPrenom03",
                        "usrEmail": "",
                        "usrTypeHabilitation": "collaborateur",
                        "usrNbrFiltresApplicatifs": 0,
                        "usrNbrFiltresPopulations": 0,
                        "usrActif": true,
                        "usrAuthorizations": {
                            "rightAnnotationDoc": {
                                "R": true,
                                "W": false,
                                "D": false
                            },
                            "rightAnnotationDossier": {
                                "R": true,
                                "W": false,
                                "D": false
                            },
                            "rightClasser": {
                                "R": true,
                                "W": true,
                                "D": false
                            },
                            "rightCycleDeVie": {
                                "R": true,
                                "W": false,
                                "D": false
                            },
                            "rightRechercheDoc": {
                                "R": true,
                                "W": true,
                                "D": false
                            },
                            "rightRecycler": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "rightUtilisateurs": {
                                "R": false,
                                "W": false,
                                "D": false
                            },
                            "accessExportCel": false,
                            "accessImportUnitaire": true
                        }
                    }
                ]
            }'
        );

        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Teste le détail d'un utilisateur
     */
    public function testGetUserDetails()
    {
        $this->changeUserRole('MyUsrLogin01', 'chef de file');

        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_user_getuser', array('user' => base64_encode('MyUsrLogin01')));
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "usrActif": true,
                    "usrLogin": "MyUsrLogin01",
                    "usrNom": "MyUsrNom01",
                    "usrPrenom": "MyUsrPrenom01",
                    "usrAdresseMail": "",
                    "usrMailCycleDeVie": "myuserlogin01@cycledevie.mail",
                    "usrAuthorizations": {
                        "rightAnnotationDoc": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightAnnotationDossier": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightClasser": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightCycleDeVie": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightRechercheDoc": {
                            "R": true,
                            "W": false,
                            "D": false
                        },
                        "rightRecycler": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightUtilisateurs": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "accessExportCel": false,
                        "accessImportUnitaire": false
                    },
                    "usrId": "' . base64_encode("MyUsrLogin01") . '",
                    "usrProfils": [{
                        "proId": 1,
                        "proFiltresApplicatifs": [1],
                        "proFiltresPopulations": [1]
                    }]
                }
            }'
        );

        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Teste la modification d'un utilisateur
     */
    public function testPutUser()
    {
        $this->changeUserRole('MyUsrLogin01', 'chef de file expert');

        $postData = $this->tools->jsonMinify(
            '{
                "usrActif": true,
                "usrLogin": "MyUsrLogin01",
                "usrNom": "MyUsrNom01",
                "usrPrenom": "MyUsrPrenom01",
                "usrAdresseMail": "email@nowhere.com",
                "usrMailCycleDeVie": "cdv@nowhere.com",
                "usrAuthorizations": {
                    "rightAnnotationDoc": {
                        "R": true,
                        "W": false,
                        "D": false
                    },
                    "rightAnnotationDossier": {
                        "R": true,
                        "W": false,
                        "D": false
                    },
                    "rightClasser": {
                        "R": true,
                        "W": true,
                        "D": true
                        },
                    "rightCycleDeVie": {
                        "R": true,
                        "W": true,
                        "D": false
                    },
                    "rightRechercheDoc": {
                        "R": true,
                        "W": true,
                        "D": false
                    },
                    "rightRecycler": {
                        "R": false,
                        "W": false,
                        "D": false
                    },
                    "rightUtilisateurs": {
                        "R": true,
                        "W": true,
                        "D": false
                    },
                    "accessExportCel": false,
                    "accessImportUnitaire": true
                },
                "usrId": "' . base64_encode("MyUsrLogin01") . '",
                "usrProfils": [
                    {
                        "proId": 1,
                        "proFiltresApplicatifs": [2],
                        "proFiltresPopulations": [2]
                    },
                    {
                        "proId": null,
                        "proFiltresApplicatifs": [1],
                        "proFiltresPopulations": [2]
                    }
                ]
            }'
        );

        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_user_putuser', array('user' => base64_encode('MyUsrLogin01')));
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
            '{"data":' . str_replace('null', 4, $postData) . '}',
            $response->getContent()
        );
    }

    /**
     * Teste la modification d'un utilisateur quand le login n'est pas renseigné dans le body
     *
     * @throws \Exception
     */
    public function testPutUserWithEmptyLogin()
    {
        $this->changeUserRole('MyUsrLogin01', 'chef de file expert');
        $postData = $this->tools->jsonMinify(
            '{
                "usrAuthorizations": {
                  "rightAnnotationDoc": {
                    "R": false,
                    "W": false,
                    "D": false
                  }
                },
                "usrProfils": [
                  {
                    "proId": 6,
                    "proFiltresApplicatifs": [
                      3,
                      6,
                      7
                    ],
                    "proFiltresPopulations": [
                      7
                    ]
                  }
                ]
            }'
        );
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_user_putuser', array('user' => base64_encode('MyUsrLogin01')));
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
    }

    /**
     * Teste la suppression d'un utilisateur
     *
     * @throws \Exception
     */
    public function testDeleteUser()
    {
        $this->changeUserRole('MyUsrLogin01', 'chef de file');

        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_user_deleteuser', array('user' => base64_encode('MyUsrLogin01')));
        $this->client->request('DELETE', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);

        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "usrActif": false,
                    "usrLogin": "MyUsrLogin01",
                    "usrNom": "MyUsrNom01",
                    "usrPrenom": "MyUsrPrenom01",
                    "usrAdresseMail": "",
                    "usrMailCycleDeVie": "myuserlogin01@cycledevie.mail",
                    "usrAuthorizations": {
                        "rightAnnotationDoc": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightAnnotationDossier": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightClasser": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightCycleDeVie": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightRechercheDoc": {
                            "R": true,
                            "W": false,
                            "D": false
                        },
                        "rightRecycler": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "rightUtilisateurs": {
                            "R": false,
                            "W": false,
                            "D": false
                        },
                        "accessExportCel": false,
                        "accessImportUnitaire": false
                    },
                    "usrId": "' . base64_encode("MyUsrLogin01") . '",
                    "usrProfils": [{
                        "proId": 1,
                        "proFiltresApplicatifs": [1],
                        "proFiltresPopulations": [1]
                    }]
                }
            }'
        );

        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : POST /user/autocomplete/search - Test sur la recherche en autocompletion
     * UseCase1 : Request - à partir du champ usrPrenom incomplet avec un champ usrNom complet
     * UseCase2 : Request - à partir du champ usrAdresseMail incomplet sans champs optionnels
     */
    public function testPostAutocompleteSearchUsers()
    {
        $route = $this->getUrl('api_apiv1_user_postautocompleteusersearch', array());
        $this->postAutocompleteSearchUsersUseCase01($route);
        $this->postAutocompleteSearchUsersUseCase02($route);
    }

    /**
     * UseCase1
     * @param $route
     */
    private function postAutocompleteSearchUsersUseCase01($route)
    {
        $this->changeUserRole('MyUsrLogin01', 'chef de file');
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "usrPrenom": "MyUsrPrenom02",
                    "context": "MyUsrNom02 MyUsrPrenom02 myusrnom02@test.fr MyUsrLogin02"
                }]
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "referencialPac": ["TSI504"],
                "main": {
                    "code": "usrPrenom",
                    "value": "MyUsr"
                },
                "fields": {
                    "usrNom": [
                        "MyUsrNom02"
                    ]
                },
                "start": 0,
                "limit": 10
            }'
        );
        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            $this->mandatoryHeaders,
            $sentRequest
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2
     * @param $route
     */
    private function postAutocompleteSearchUsersUseCase02($route)
    {
        $this->changeUserRole('MyUsrLogin01', 'chef de file');
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "usrAdresseMail": "myusrnom02@test.fr",
                    "context": "MyUsrNom02 MyUsrPrenom02 myusrnom02@test.fr MyUsrLogin02"
                }]
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "referencialPac": ["TSI504"],
                "main": {
                    "code": "usrAdresseMail",
                    "value": "myusr"
                },
                "start": 0,
                "limit": 10
            }'
        );
        $this->client->request(
            'POST',
            $route,
            array(),
            array(),
            $this->mandatoryHeaders,
            $sentRequest
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Demande PUT ou POST du client WS
     *
     * @param $route
     * @param $method
     * @param null $sentRequest
     */
    public function clientByParams($route, $method, $sentRequest = null)
    {
        $this->client = static::makeclientWithLogin();
        $this->client->request($method, $route, [], [], $this->mandatoryHeaders, $sentRequest);
    }

    /**
     * Change le role d'un utilisateur
     *
     * @param $login
     * @param $role
     * @throws \Exception
     */
    protected function changeUserRole($login, $role)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        /** @var UsrUsers $user */
        $user = $entityManager->getRepository('ApiBundle:UsrUsers')->findOneByUsrLogin($login);
        if (!$user) {
            throw new \Exception(sprintf(
                "Impossible de modifier le role de l'utilisateur '%s'. Celui-ci n'existe pas.",
                $login
            ));
        }

        $user->setUsrTypeHabilitation($role);
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
