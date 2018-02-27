<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class ReportControllerTest extends DocapostWebTestCase
{
    /**
     * @var Client instance
     */
    private $client;

    public function setUp()
    {
        $this->initTests(
            [
                'ApiBundle\DataFixtures\ORM\LoadIfpIndexfichePaperlessData',
                'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
                'ApiBundle\DataFixtures\ORM\LoadProProfilData',
                'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData',
                'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
                'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
                'ApiBundle\DataFixtures\ORM\LoadRidRefIdData',
                'ApiBundle\DataFixtures\ORM\LoadRapRapportData',
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
     * Tests : POST /report/cel/export
     */
    public function testPostReportCelExport()
    {
        $route = $this->getUrl('api_apiv1_report_postreportcelexport');
        $this->postReportCelExportUseCase01($route);
        $this->postReportCelExportUseCase02($route);
    }

    /**
     * UseCase1 : Request pour exporter la liste des flux CEL par période
     * @param $route
     */
    private function postReportCelExportUseCase01($route)
    {
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "dateArchivage": {
		                "start": "2011-06-01T00:00:00+02:00",
		                "end": "2016-06-30T00:00:00+02:00"
	                },
	                "periodeArchivage": {
		                "start": "2009-01-01T00:00:00+02:00",
		                "end": "2015-12-31T00:00:00+02:00"
	                },
	                "source": [
	                    "CEL"
                    ],
	                "codePac": [
	                    "TSI504"
                    ],
	                "codeSourceOrigine": [
	                    "ALPHA 01"
                    ],
                    "typeRapport": "periode"
                }'
            )
        );
        $this->client->getResponse()->sendContent();
        $content = ob_get_contents();
        ob_clean();
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->assertStringStartsWith(
            'attachment; filename="export_CEL_report_MyUsrLogin01.xlsx"',
            $this->client->getResponse()->headers->get('Content-Disposition')
        );
        $this->assertNotEmpty($content);
        $this->assertNotNull($content);
    }

    /**
     * UseCase2 : Request pour exporter la liste des flux CEL par type de documents
     * @param $route
     */
    private function postReportCelExportUseCase02($route)
    {
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "dateArchivage": {
		                "start": "2011-06-01T00:00:00+02:00",
		                "end": "2016-06-30T00:00:00+02:00"
	                },
	                "periodeArchivage": {
		                "start": "2009-01-01T00:00:00+02:00",
		                "end": "2015-12-31T00:00:00+02:00"
	                },
	                "source": [
	                    "CEL"
                    ],
	                "codePac": [
	                    "TSI504"
                    ],
	                "codeSourceOrigine": [
	                    "ALPHA 01"
                    ],
                    "typeRapport": "type"
                }'
            )
        );
        $this->client->getResponse()->sendContent();
        $content = ob_get_contents();
        ob_clean();
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->assertStringStartsWith(
            'attachment; filename="export_CEL_report_MyUsrLogin01.xlsx"',
            $this->client->getResponse()->headers->get('Content-Disposition')
        );
        $this->assertNotEmpty($content);
        $this->assertNotNull($content);
    }

    /**
     * Tests : GET /report/mass
     * UseCase : Request pour récupèrer la liste des rapports de masse
     */
    public function testGetReportMass()
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [
                    {
                        "rapId": 3,
                        "rapLibelleFic": "rejected.csv",
                        "rapEtat": "KO"
                    }
                ]
            }'
        );
        $route = $this->getUrl('api_apiv1_report_getreportmass');
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);
        unset($result['data'][0]['rapCreatedAt']);
        $actualResponse = json_encode($result);

        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * Tests : GET /report/{rapId}
     * UseCase : Request pour récupèrer un rapport
     */
    public function testGetReport()
    {
        $route = $this->getUrl('api_apiv1_report_getreport', ['rapId' => 3]);
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $this->client->getResponse()->sendContent();
        $content = ob_get_contents();
        ob_clean();
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->assertStringStartsWith(
            'attachment; filename="rejected.csv"',
            $this->client->getResponse()->headers->get('Content-Disposition')
        );
        $this->assertNotEmpty($content);
        $this->assertNotNull($content);
    }
}
