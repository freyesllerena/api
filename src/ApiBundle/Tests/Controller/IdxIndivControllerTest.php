<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class IdxIndivControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadIinIdxIndivData'
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
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json; charset=utf-8'),
            $response->headers
        );
    }

    /**
     * Test : GET /indiv/matricule/list
     * UseCase : Request pour la liste des matriculesRH issus du référentiel IinIdxIndiv
     */
    public function testGetIndivMatriculeListAction()
    {
// TODO : A revoir car les habilitation font que le résultat ne correspond plus
// TODO : Ajouter un cas où l'utilisateur n'aeffectivement pas le dtoi de voir la liste des matricules
        
//        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
//        $expectedResponse = $this->tools->jsonMinify(
//            '{
//                "data": [{
//                    "iinIdNumMatriculeRh": "01000002",
//                    "iinIdNomSalarie": "ALLAIGRE",
//                    "iinIdPrenomSalarie": "CARINE"
//                }, {
//                    "iinIdNumMatriculeRh": "01000352",
//                    "iinIdNomSalarie": "EUTEBERT",
//                    "iinIdPrenomSalarie": "EVELYNE"
//                }, {
//                    "iinIdNumMatriculeRh": "01010234",
//                    "iinIdNomSalarie": "ACHENAIS",
//                    "iinIdPrenomSalarie": "STEPHANE"
//                }]
//            }'
//        );
//        $route = $this->getUrl('api_apiv1_idxindiv_getmatriculelist');
//        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
//        $response = $this->client->getResponse();
//        $this->assertJsonResponse($response, 200);
//        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Teste le renvoi de la liste
     */
    public function testGetList()
    {
        $this->client = static::makeclientWithLogin();

        $this->client->request(
            'GET',
            '/api/idxIndiv',
            [],
            [],
            $this->mandatoryHeaders,
            '{}'
        );
        $response = $this->client->getResponse();
        $this->assertEquals(
            $response->getContent(),
            $this->tools->jsonMinify('{
                "data":[{
                    "iinId":1,
                    "iinIdNomSalarie":"ALLAIGRE",
                    "iinIdPrenomSalarie":"CARINE"
                },{
                    "iinId":2,
                    "iinIdNomSalarie":"ACHENAIS",
                    "iinIdPrenomSalarie":"STEPHANE"
                },{
                    "iinId":3,
                    "iinIdNomSalarie":"EUTEBERT",
                    "iinIdPrenomSalarie":"EVELYNE"
                },{
                    "iinId":4,
                    "iinIdNomSalarie":"MARTIN",
                    "iinIdPrenomSalarie":"PHILIPPE"
                }]
            }')
        );
    }

    /**
     * Teste la création de IdxIndiv
     */
    public function testPost()
    {
        $this->client = static::makeclientWithLogin();

        $input = $this->getPostRequest();

        $this->client->request(
            'POST',
            '/api/idxIndiv',
            [],
            [],
            $this->mandatoryHeaders,
            json_encode($input)
        );

        $response = $this->client->getResponse();

        $output = array(
            'data' => (array(
                'iinId' => 5,
            ) + $input + array(
                "iinCreatedAt" => "[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}",
                "iinUpdatedAt" => "[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}"
            ))
        );
        $this->assertRegExp(
            json_encode($output),
            $response->getContent()
        );
    }

    /**
     * Teste la modification de IdxIndiv
     */
    public function testPut()
    {
        $this->client = static::makeclientWithLogin();

        $input = $this->getPostRequest();

        $this->client->request(
            'PUT',
            '/api/idxIndiv/1',
            [],
            [],
            $this->mandatoryHeaders,
            json_encode($input)
        );

        $response = $this->client->getResponse();

        $output = array(
            'data' => (array(
                    'iinId' => 1,
                ) + $input + array(
                    "iinCreatedAt" => "[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}",
                    "iinUpdatedAt" => "[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}"
                ))
        );
        $this->assertRegExp(
            json_encode($output),
            $response->getContent()
        );
    }

    /**
     * Renvoit les valeurs envoyés dans POST
     *
     * @return array
     */
    protected function getPostRequest() {
        return array(
            "iinCodeClient" => "TSI504",
            "iinIdCodeSociete" => "99",
            "iinIdCodeJalon" => "",
            "iinIdCodeEtablissement" => "999999",
            "iinIdLibEtablissement" => "POLYCLINIQUE ST FRANCOIS SAINT ANTOINE",
            "iinIdNomSociete" => "POLYCLINIQUE ST FRANCOIS SAINT ANTOINE",
            "iinIdNomClient" => "CARREFOUR",
            "iinIdTypePaie" => "",
            "iinIdPeriodePaie" => "201506",
            "iinIdNomSalarie" => "JANE",
            "iinIdPrenomSalarie" => "DOE",
            "iinIdNomJeuneFilleSalarie" => "SMITH",
            "iinIdDateEntree" => "2014-12-05",
            "iinIdDateSortie" => null,
            "iinIdNumNir" => "272120318505692",
            "iinNumMatricule" => "0999999",
            "iinFichierIndex" => "ADPLINS_PAPINDIV_F3015082",
            "iinIdCodeCategProfessionnelle" => "30",
            "iinIdCodeCategSocioProf" => "30",
            "iinIdTypeContrat" => "00",
            "iinIdAffectation1" => "01",
            "iinIdAffectation2" => "SOIN",
            "iinIdAffectation3" => "PMD",
            "iinIdNumSiren" => "917250151",
            "iinIdNumSiret" => "91725015100029",
            "iinIdDateNaissanceSalarie" => "1977-12-10",
            "iinIdLibre1" => "",
            "iinIdLibre2" => "",
            "iinIdNumMatriculeGroupe" => "",
            "iinIdNumMatriculeRh" => "01000002",
            "iinIdCodeActivite" => "01",
            "iinIdCodeChrono" => null,
            "iinIdDate1" => null,
            "iinIdDate2" => null,
            "iinIdDate3" => null,
            "iinIdDate4" => null,
            "iinIdDate5" => null,
            "iinIdDate6" => null,
            "iinIdDate7" => null,
            "iinIdDate8" => null,
            "iinIdDateAdp1" => null,
            "iinIdDateAdp2" => null,
            "iinIdAlphanum1" => null,
            "iinIdAlphanum2" => null,
            "iinIdAlphanum3" => null,
            "iinIdAlphanum4" => null,
            "iinIdAlphanum5" => null,
            "iinIdAlphanum6" => null,
            "iinIdAlphanum7" => null,
            "iinIdAlphanum8" => null,
            "iinIdAlphanum9" => null,
            "iinIdAlphanum10" => null,
            "iinIdAlphanum11" => null,
            "iinIdAlphanum12" => null,
            "iinIdAlphanum13" => null,
            "iinIdAlphanum14" => null,
            "iinIdAlphanum15" => null,
            "iinIdAlphanum16" => null,
            "iinIdAlphanum17" => null,
            "iinIdAlphanum18" => null,
            "iinIdAlphanumAdp1" => null,
            "iinIdAlphanumAdp2" => null,
            "iinIdNum1" => null,
            "iinIdNum2" => null,
            "iinIdNum3" => null,
            "iinIdNum4" => null,
            "iinIdNum5" => null,
            "iinIdNum6" => null,
            "iinIdNum7" => null,
            "iinIdNum8" => null,
            "iinIdNum9" => null,
            "iinIdNum10" => null,
            "iinIdNumOrdre" => null
        );
    }

    /**
     * Teste le détail de IdxIndiv
     *
     * @throws \Exception
     */
    public function testGetDetail()
    {
        $this->client = static::makeclientWithLogin();

        $this->client->request(
            'GET',
            '/api/idxIndiv/1',
            [],
            [],
            $this->mandatoryHeaders,
            '{}'
        );
        $response = $this->client->getResponse();
        $this->assertRegExp(
            $this->tools->jsonMinify(
            '{
	            "data":{
	                "iinId":1,
	                "iinCodeClient":"TSI504",
	                "iinIdCodeSociete":"01",
	                "iinIdCodeJalon":"",
	                "iinIdCodeEtablissement":"00001",
	                "iinIdLibEtablissement":"POLYCLINIQUE ST FRANCOIS SAINT ANTOINE",
	                "iinIdNomSociete":"POLYCLINIQUE ST FRANCOIS SAINT ANTOINE",
	                "iinIdNomClient":"VITALIA CO",
	                "iinIdTypePaie":"",
	                "iinIdPeriodePaie":"201506",
	                "iinIdNomSalarie":"ALLAIGRE",
	                "iinIdPrenomSalarie":"CARINE",
	                "iinIdNomJeuneFilleSalarie":"",
	                "iinIdDateEntree":"1999-09-02",
	                "iinIdDateSortie":null,
	                "iinIdNumNir":"272120318505692",
	                "iinNumMatricule":"01000002",
	                "iinFichierIndex":"ADPLINS_PAPINDIV_F3015082",
	                "iinIdCodeCategProfessionnelle":"30",
	                "iinIdCodeCategSocioProf":"30",
	                "iinIdTypeContrat":"00",
	                "iinIdAffectation1":"01",
	                "iinIdAffectation2":"SOIN",
	                "iinIdAffectation3":"PMD",
	                "iinIdNumSiren":"917250151",
	                "iinIdNumSiret":"91725015100029",
	                "iinIdDateNaissanceSalarie":"1972-12-13",
	                "iinIdLibre1":"",
	                "iinIdLibre2":"",
	                "iinIdNumMatriculeGroupe":"",
	                "iinIdNumMatriculeRh":"01000002",
	                "iinIdCodeActivite":"01",
	                "iinIdCodeChrono":null,
	                "iinIdDate1":null,
	                "iinIdDate2":null,
	                "iinIdDate3":null,
	                "iinIdDate4":null,
	                "iinIdDate5":null,
	                "iinIdDate6":null,
	                "iinIdDate7":null,
	                "iinIdDate8":null,
	                "iinIdDateAdp1":null,
	                "iinIdDateAdp2":null,
	                "iinIdAlphanum1":null,
	                "iinIdAlphanum2":null,
	                "iinIdAlphanum3":null,
	                "iinIdAlphanum4":null,
	                "iinIdAlphanum5":null,
	                "iinIdAlphanum6":null,
	                "iinIdAlphanum7":null,
	                "iinIdAlphanum8":null,
	                "iinIdAlphanum9":null,
	                "iinIdAlphanum10":null,
	                "iinIdAlphanum11":null,
	                "iinIdAlphanum12":null,
	                "iinIdAlphanum13":null,
	                "iinIdAlphanum14":null,
	                "iinIdAlphanum15":null,
	                "iinIdAlphanum16":null,
	                "iinIdAlphanum17":null,
	                "iinIdAlphanum18":null,
	                "iinIdAlphanumAdp1":null,
	                "iinIdAlphanumAdp2":null,
	                "iinIdNum1":null,
	                "iinIdNum2":null,
	                "iinIdNum3":null,
	                "iinIdNum4":null,
	                "iinIdNum5":null,
	                "iinIdNum6":null,
	                "iinIdNum7":null,
	                "iinIdNum8":null,
	                "iinIdNum9":null,
	                "iinIdNum10":null,
	                "iinIdNumOrdre":null,
	                "iinCreatedAt":"[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}",
                    "iinUpdatedAt":"[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}"
                }
            }'
            ),
            $response->getContent()
        );
    }
}
