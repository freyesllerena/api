<?php
namespace InteruploadBundle\Tests\Controller;

use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InteruploadControllerTest
 * @package InteruploadBundle\Tests\Controller
 */
class InteruploadControllerTest extends DocapostWebTestCase
{
    /**
     * @var Client
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
                'ApiBundle\DataFixtures\ORM\LoadIupInteruploadData',
                'ApiBundle\DataFixtures\ORM\LoadIucInteruploadCfgData',
                'ApiBundle\DataFixtures\ORM\LoadConConfigData',
                'ApiBundle\DataFixtures\ORM\LoadIniInteruploadIndexationData',
                'ApiBundle\DataFixtures\ORM\LoadIinIdxIndivData',
                'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
                'ApiBundle\DataFixtures\ORM\LoadRidRefIdData',
                'ApiBundle\DataFixtures\ORM\LoadIfpIndexfichePaperlessData'
            ]
        );

        $this->client = static::makeClient();
    }

    /**
     * @param $response
     * @param int $statusCode
     */
    protected function assertJsonResponse(Response $response, $statusCode = 200)
    {
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json; charset=utf-8'),
            $response->headers
        );
    }

    /**
     * Test : PUT /interupload/ticket/statut - recherche d'un ticket et
     * création d'un nouveau document
     * UseCase1 : Request - Test SUCCESS : Insertion document
     * UseCase2 : Request - Test WARNING : Document déjà insèré
     * UseCase3 : Request - Test ERROR   : Numero de ticket pas présent dans les params.
     * UseCase4 : Request - Test ERROR   : Le numero de ticket pas trouvé.
     * UseCase5 : Request - Test ERROR   : Demande sans paramètres JSON.
     * UseCase6 : Request - Test ERROR   : Interupload repond ERROR sur le statut du ticket
     */
    public function testSetTicketStatusSuccess()
    {
        $route = $this->getUrl('interupload_apiv1_ticket_setticketstatus');
        // UseCase1
        $this->useCaseAddDocument($route);
        // UseCase 2
        $this->useCaseDocumentIsExist($route);
        // UseCase3
        $this->useCaseInteruploadNotTicket($route);
        // UseCase4
        $this->useCaseInteruploadNotFoundTicket($route);
        // UseCase5
        $this->useCaseInteruploadErrorNotJsonParams($route);
        // UseCase6
        $this->useCaseInteruploadStatutError($route);
    }

    /**
     * Test cas d'utilisation : Insertion document
     * @param $route
     */
    private function useCaseAddDocument($route)
    {
        $sentRequest = $this->tools->jsonMinify(
            '{
                "ticket": "f7c3db80883ea95b7dfa11ce6b63af38d979d2c1c699eef74474ad9a3126d9b3556f45be",
                "statut": "OK_PRODUCTION",
	            "pac": "TSI504",
	            "metadataProduction": "<metadata>
	                                       <document>
	                                           <type>PDFa</type>
	                                           <pages>1</pages>
	                                           <taille>20418</taille>
	                                           <location>82240b896f124fa895258f39dd4bbb42</location>
	                                           <filename>000000000020418.pdf</filename>
	                                           <filename_sans_extension>000000000020418</filename_sans_extension>
	                                           <filename_original>000000000020418.pdf</filename_original>
	                                           <VolumeStockageImagerie>PAPERLESS</VolumeStockageImagerie>
	                                       </document>
	                                   </metadata>"
            }'
        );
        $this->clientByParams($route, 'PUT', $sentRequest);
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());
        $infos = $content->data->msg->infos[0];
        $documentId = $infos->values[1];
        $documentManager = $this->getContainer()->get('api.manager.document');
        $newDocument = $documentManager->findIndexfichePaperless('IfpIndexfichePaperless', ['ifpId' => $documentId]);
        /* @var $newDocument IfpIndexfichePaperless */
        $this->assertEquals("E000000000020418/1/1", $newDocument->getIfpDocumentsassocies());
        $this->assertEquals("S/E//82240b896f124fa895258f39dd4bbb42", $newDocument->getIfpVdmLocalisation());
        $this->assertEquals("1", $newDocument->getIfpNombrepages());
        $this->assertNull($newDocument->getIfpIdCodeChrono());
        $this->assertNull($newDocument->getIfpIdNumeroBoiteArchive());
        $this->assertEmpty($newDocument->getIfpLotIndex());
        $this->assertEquals("0", $newDocument->getIfpLotProduction());
        $this->assertEquals("POLYCLINIQUE ST FRANCOIS SAINT ANTOINE", $newDocument->getIfpIdNomSociete());
        $this->assertEmpty($newDocument->getIfpIdCompany());
        $this->assertEquals("VITALIA CO", $newDocument->getIfpIdNomClient());
        $this->assertEquals("01", $newDocument->getIfpIdCodeSociete());
        $this->assertEquals("00001", $newDocument->getIfpIdCodeEtablissement());
        $this->assertEquals("POLYCLINIQUE ST FRANCOIS SAINT ANTOINE", $newDocument->getIfpIdLibEtablissement());
        $this->assertNull($newDocument->getIfpIdCodeJalon());
        $this->assertNull($newDocument->getIfpIdLibelleJalon());
        $this->assertEquals("917250151", $newDocument->getIfpIdNumSiren());
        $this->assertEquals("91725015100029", $newDocument->getIfpIdNumSiret());
        $this->assertEquals("D256900", $newDocument->getIfpIdIndiceClassement());
        $this->assertEmpty($newDocument->getIfpIdUniqueDocument());
        $this->assertEmpty($newDocument->getIfpIdTypeDocument());
        $this->assertEquals("Changement de RIB_11111", $newDocument->getIfpIdLibelleDocument());
        $this->assertEquals("ADP", $newDocument->getIfpIdAuteurDocument());
        $this->assertEquals("BVRH UPLOAD", $newDocument->getIfpIdSourceDocument());
        $this->assertEquals("1", $newDocument->getIfpIdNumVersionDocument());
        $this->assertEquals("000000020418", $newDocument->getIfpIdPoidsDocument());
        $this->assertEmpty($newDocument->getIfpIdNbrPagesDocument());
        $this->assertEmpty($newDocument->getIfpIdProfilArchivage());
        $this->assertEmpty($newDocument->getIfpIdEtatArchive());
        $this->assertEquals("201606", $newDocument->getIfpIdPeriodePaie());
        $this->assertEquals("2016", $newDocument->getIfpIdPeriodeExerciceSociale());
        $this->assertNull($newDocument->getIfpIdDateDernierAccesDocument());
        $this->assertEquals("30", $newDocument->getIfpIdDureeArchivageDocument());
        $this->assertEquals("ALLAIGRE", $newDocument->getIfpIdNomSalarie());
        $this->assertEquals("CARINE", $newDocument->getIfpIdPrenomSalarie());
        $this->assertEmpty($newDocument->getIfpIdNomJeuneFilleSalarie());
        $this->assertNull($newDocument->getIfpIdDateSortie());
        $this->assertEquals("272120318505692", $newDocument->getIfpIdNumNir());
        $this->assertEquals("30", $newDocument->getIfpIdCodeCategProfessionnelle());
        $this->assertEquals("30", $newDocument->getIfpIdCodeCategSocioProf());
        $this->assertEquals("00", $newDocument->getIfpIdTypeContrat());
        $this->assertEquals("01", $newDocument->getIfpIdAffectation1());
        $this->assertEquals("SOIN", $newDocument->getIfpIdAffectation2());
        $this->assertEquals("PMD", $newDocument->getIfpIdAffectation3());
        $this->assertEquals("01", $newDocument->getIfpIdCodeActivite());
        $this->assertEquals(false, $newDocument->isIfpGeler());
        $this->assertEquals(false, $newDocument->isIfpCycleFinDeVie());
        $this->assertEquals("01000002", $newDocument->getIfpIdNumMatriculeRh());
        $this->assertEquals("82240b896f124fa895258f39dd4bbb42", $newDocument->getIfpArchiveSerialnumber());
        $this->assertEquals("82240b896f124fa895258f39dd4bbb42", $newDocument->getIfpArchiveCfe());
        $this->assertEquals("INTERUPLOAD", $newDocument->getIfpOpnProvenance());
        $this->assertEquals("OK", $newDocument->getIfpStatusNum());
        $this->assertEquals("MyUsrLogin01", $newDocument->getIfpLogin());
        $this->assertEquals("01000002", $newDocument->getIfpNumMatricule());
        $this->assertEquals("TSI504", $newDocument->getIfpCodeClient());
        $this->assertJsonResponse($response, 200);
    }

    /**
     * Test cas d'utilisation : Document déjà insèré
     * @param $route
     */
    private function useCaseDocumentIsExist($route)
    {
        $sentRequest = $this->tools->jsonMinify(
            '{
                "ticket": "f7c3db80883ea95b7dfa11ce6b63af38d979d2c1c699eef74474ad9a3126d9b3556f45be",
                "statut": "OK_PRODUCTION",
	            "pac": "TSI504",
	            "metadataProduction": "<metadata>
	                                       <document>
	                                           <type>PDFa</type>
	                                           <pages>1</pages>
	                                           <taille>20418</taille>
	                                           <location>82240b896f124fa895258f39dd4bbb42</location>
	                                           <filename>000000000020418.pdf</filename>
	                                           <filename_sans_extension>000000000020418</filename_sans_extension>
	                                           <filename_original>000000000020418.pdf</filename_original>
	                                           <VolumeStockageImagerie>PAPERLESS</VolumeStockageImagerie>
	                                       </document>
	                                   </metadata>"
            }'
        );
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "msg": {
                        "level": 1,
                        "infos": [{
                            "code": "alreadyDone",
                            "values": ["f7c3db80883ea95b7dfa11ce6b63af38d979d2c1c699eef74474ad9a3126d9b3556f45be"],
                            "fieldName": "iupTicket"
                        }]
                    }
                }
            }'
        );
        $this->clientByParams($route, 'PUT', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test cas d'utilisation : Interupload : Numero de ticket pas présent dans les params.
     * @param $route
     */
    private function useCaseInteruploadNotTicket($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "ticketNotFound",
                        "values": [""],
                        "fieldName": "iupTicket"
                    }]
                }
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "ticket":"",
                "statut":"OK_PRODUCTION",
                "pac": "TSI504",
                "metadataProduction": "<metadata>
                                           <document>
                                               <type>PDFa</type>
                                               <pages>1</pages>
                                               <taille>20418</taille>
                                               <location>82240b896f124fa895258f39dd4bbb42</location>
                                               <filename>000000000041823.pdf</filename>
                                               <filename_sans_extension>000000000041823</filename_sans_extension>
                                               <filename_original>000000000041823.pdf</filename_original>
                                               <VolumeStockageImagerie>PAPERLESS</VolumeStockageImagerie>
                                           </document>
                                       </metadata>"
            }'
        );
        $this->clientByParams($route, 'PUT', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test cas d'utilisation : Le numero de ticket pas trouvé.
     * @param $route
     */
    private function useCaseInteruploadNotFoundTicket($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "ticketNotFound",
                        "values": ["99d4bc8f6a14cc32bce11ef02fda5caff6869f60f753b62f0cc728938ea285b5c6271e27---"],
                        "fieldName": "iupTicket"
                    }]
                }
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "ticket": "99d4bc8f6a14cc32bce11ef02fda5caff6869f60f753b62f0cc728938ea285b5c6271e27---",
                "statut": "OK_PRODUCTION",
                "pac": "TSI504",
                "metadataProduction": "<metadata>
                                           <document>
                                               <type>PDFa</type>
                                               <pages>1</pages>
                                               <taille>20418</taille>
                                               <location>82240b896f124fa895258f39dd4bbb42</location>
                                               <filename>000000000041823.pdf</filename>
                                               <filename_sans_extension>000000000041823</filename_sans_extension>
                                               <filename_original>000000000041823.pdf</filename_original>
                                               <VolumeStockageImagerie>PAPERLESS</VolumeStockageImagerie>
                                           </document>
                                       </metadata>"
            }'
        );
        $this->clientByParams($route, 'PUT', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test cas d'utilisation : appel WS sans parametres Json
     * @param $route
     */
    private function useCaseInteruploadErrorNotJsonParams($route)
    {
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
        $sentRequest = '';
        $this->clientByParams($route, 'PUT', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test cas d'utilisation : Interupload repond statut error
     * @param $route
     */
    private function useCaseInteruploadStatutError($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "msg": {
                        "level": 1,
                        "infos": [{
                            "code": "successEntryTicketStatus",
                            "values": ["ER_DP_Erreur CFEC : ERREUR 7"],
                            "fieldName": "iupStatut"
                        }]
                    }
                }
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "ticket": "99d4bc8f6a14cc32bce11ef02fda5caff6869f60f753b62f0cc728938ea285b5c6271e27",
                "statut": "ER_DP_Erreur CFEC : ERREUR 7",
                "pac": "TSI504",
                "metadataProduction": "<metadata>
                                           <document>
                                               <type>PDFa</type>
                                               <pages>1</pages>
                                               <taille>20418</taille>
                                               <location>82240b896f124fa895258f39dd4bbb42</location>
                                               <filename>000000000041823.pdf</filename>
                                               <filename_sans_extension>000000000041823</filename_sans_extension>
                                               <filename_original>000000000041823.pdf</filename_original>
                                               <VolumeStockageImagerie>PAPERLESS</VolumeStockageImagerie>
                                           </document>
                                       </metadata>"
            }'
        );
        $this->clientByParams($route, 'PUT', $sentRequest);
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
    private function clientByParams($route, $method, $sentRequest = null)
    {
        $this->client = static::makeclientWithLogin();
        $this->client->request($method, $route, [], [], $this->mandatoryHeaders, $sentRequest);
    }
}
