<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class DocumentControllerTest
 * @package ApiBundle\Tests\Controller
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class DocumentControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadIinIdxIndivData',
                'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
                'ApiBundle\DataFixtures\ORM\LoadIfpIndexfichePaperlessData',
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadConConfigData',
                'ApiBundle\DataFixtures\ORM\LoadDicDictionnaireData',
                'ApiBundle\DataFixtures\ORM\LoadRidRefIdData',
                'ApiBundle\DataFixtures\ORM\LoadProProfilData',
                'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
                'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
                'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
                'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData',
                'ApiBundle\DataFixtures\ORM\LoadRapRapportData',
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
     * Réponse attendue pour le test GetListPropertiesFields
     * @return mixed
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    private function expectedResponseGetListPropertiesFields()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
                    "table": "IfpIndexfichePaperless",
                    "fields": {
                        "ifpId": {
                            "hidden": false,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "integer",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpDocumentsassocies": {
                            "hidden": "true,",
                            "editable": "false,",
                            "category": "t",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 40,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpVdmLocalisation": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "text",
                            "length": 65535,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpRefpapier": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 4,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpNombrepages": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "smallint",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdCodeChrono": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNumeroBoiteArchive": {
                            "hidden": false,
                            "editable": true,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpInterbox": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "boolean",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpLotIndex": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 100,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpLotProduction": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "smallint",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNomSociete": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdCompany": {
                            "hidden": true,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNomClient": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": true,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpCodeClient": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdCodeSociete": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdCodeEtablissement": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdLibEtablissement": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdCodeJalon": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdLibelleJalon": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNumSiren": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNumSiret": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdIndiceClassement": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdUniqueDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdTypeDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdLibelleDocument": {
                            "hidden": false,
                            "editable": true,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": false,
                            "default": {
                                "plandeclassement": true,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpCodeDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "nullable": true,
                            "length": 10,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdFormatDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAuteurDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": true,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdSourceDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "choices",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNumVersionDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdPoidsDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNbrPagesDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdProfilArchivage": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdEtatArchive": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "choices",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdPeriodePaie": {
                            "hidden": false,
                            "editable": true,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "datemonth",
                            "length": 6,
                            "nullable": true,
                            "default": {
                                "plandeclassement": true,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdPeriodeExerciceSociale": {
                            "hidden": false,
                            "editable": true,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "dateyear",
                            "length": 4,
                            "nullable": true,
                            "default": {
                                "plandeclassement": true,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDateDernierAccesDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDateArchivageDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": true,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDureeArchivageDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDateFinArchivageDocument": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNomSalarie": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "completudeWithoutDoc": true,
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": true,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdPrenomSalarie": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "completudeWithoutDoc": true,
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": true,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNomJeuneFilleSalarie": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "completudeWithoutDoc": true,
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDateNaissanceSalarie": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "dateday",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDateEntree": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "dateday",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDateSortie": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "dateday",
                            "completudeWithoutDoc": true,
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNumNir": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdCodeCategProfessionnelle": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "choices",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdCodeCategSocioProf": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "choices",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdTypeContrat": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "choices",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAffectation1": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "choices",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAffectation2": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "choices",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAffectation3": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "choices",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdLibre1": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdLibre2": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAffectation1Date": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "dateday",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAffectation2Date": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "dateday",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAffectation3Date": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "dateday",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdLibre1Date": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdLibre2Date": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpNumMatricule": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNumMatriculeRh": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "completudeWithoutDoc": true,
                            "type": "string",
                            "length": 255,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNumMatriculeGroupe": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAnnotation": {
                            "hidden": true,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "text",
                            "length": 65535,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdConteneur": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdBoite": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdLot": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 255,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNumOrdre": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 2,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpArchiveCfec": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "text",
                            "length": 65535,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpArchiveSerialnumber": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "text",
                            "length": 65535,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpArchiveDatetime": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": 65535,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpArchiveName": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "text",
                            "length": 65535,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpArchiveCfe": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "text",
                            "length": 65535,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpNumeropdf": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 50,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpOpnProvenance": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 100,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpStatusNum": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 10,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIsDoublon": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "boolean",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpLogin": {
                            "hidden": false,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 100,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpModedt": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 20,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpNumdtr": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 20,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpOldIdDateDernierAccesDocument": {
                            "hidden": true,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpOldIdDateArchivageDocument": {
                            "hidden": true,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpOldIdDateFinArchivageDocument": {
                            "hidden": true,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpOldIdDateNaissanceSalarie": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "dateday",
                            "length": 65535,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpOldIdDateEntree": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "dateday",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpOldIdDateSortie": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp"],
                            "type": "dateday",
                            "length": 255,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdCodeActivite": {
                            "hidden": false,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 255,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpCycleFinDeVie": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "boolean",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpCyclePurger": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 1,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpGeler": {
                            "hidden": false,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "boolean",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDate1": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDate2": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDate3": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDate4": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDate5": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDate6": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDate7": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDate8": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDateAdp1": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdDateAdp2": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "datetime",
                            "length": 8,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum1": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum2": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum3": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum4": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum5": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum6": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum7": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum8": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum9": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum10": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum11": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum12": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum13": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum14": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum15": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum16": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum17": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanum18": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanumAdp1": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdAlphanumAdp2": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum1": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum2": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum3": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum4": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum5": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum6": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum7": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum8": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum9": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIdNum10": {
                            "hidden": true,
                            "editable": false,
                            "category": "i",
                            "source": ["ifp", "iin"],
                            "type": "float",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpCycleTempsParcouru": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpCycleTempsRestant": {
                            "hidden": false,
                            "editable": false,
                            "category": "m",
                            "source": ["ifp"],
                            "type": "string",
                            "length": 50,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpSetFinArchivage": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "boolean",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpIsPersonnel": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "boolean",
                            "length": null,
                            "nullable": false,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpCreatedAt": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        },
                        "ifpUpdatedAt": {
                            "hidden": true,
                            "editable": false,
                            "category": "t",
                            "source": ["ifp"],
                            "type": "datetime",
                            "length": null,
                            "nullable": true,
                            "default": {
                                "plandeclassement": false,
                                "mesdossiers": false,
                                "completudesAvec": false,
                                "completudesSans": false,
                                "thematiques": false,
                                "docavance": false
                            }
                        }
                    }
                }
            }'
        );
    }

    /**
     * Réponse attendue pour le test PutDocument
     * @return mixed
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    private function expectedResponsePutDocument()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
                    "ifpId": 5,
                    "ifpNombrepages": 1,
                    "ifpIdCodeChrono": "",
                    "ifpIdNumeroBoiteArchive": "Test",
                    "ifpIdNomSociete": "SOCIETE DEMO LYON",
                    "ifpIdNomClient": "RSI CO",
                    "ifpCodeClient": "TSI504",
                    "ifpIdCodeSociete": "02",
                    "ifpIdCodeEtablissement": "00003",
                    "ifpIdLibEtablissement": "RSI TSI RHONE LYON",
                    "ifpIdCodeJalon": "JC",
                    "ifpIdLibelleJalon": "DESTINATAIRE N0 3",
                    "ifpIdNumSiren": "330740382",
                    "ifpIdNumSiret": "33074038280448",
                    "ifpIdIndiceClassement": "D325800",
                    "ifpIdUniqueDocument": null,
                    "ifpIdTypeDocument": null,
                    "ifpIdLibelleDocument": "Test tiroir D256900",
                    "ifpCodeDocument": "D256900",
                    "ifpIdFormatDocument": "PDF",
                    "ifpIdAuteurDocument": null,
                    "ifpIdSourceDocument": "BVRH UPLOAD",
                    "ifpIdNumVersionDocument": "1",
                    "ifpIdPoidsDocument": "000000013121",
                    "ifpIdNbrPagesDocument": null,
                    "ifpIdProfilArchivage": null,
                    "ifpIdEtatArchive": null,
                    "ifpIdPeriodePaie": "201604",
                    "ifpIdPeriodeExerciceSociale": "2016",
                    "ifpIdDateDernierAccesDocument": null,
                    "ifpIdDateArchivageDocument": {
                        "date": "2015-06-29 15:22:35.000000",
                        "timezone_type": 3,
                        "timezone": "Europe\/Paris"
                    },
                    "ifpIdDureeArchivageDocument": "30",
                    "ifpIdDateFinArchivageDocument": null,
                    "ifpIdNomSalarie": null,
                    "ifpIdPrenomSalarie": null,
                    "ifpIdNomJeuneFilleSalarie": null,
                    "ifpIdDateNaissanceSalarie": null,
                    "ifpIdDateEntree": null,
                    "ifpIdDateSortie": null,
                    "ifpIdNumNir": null,
                    "ifpIdCodeCategProfessionnelle": null,
                    "ifpIdCodeCategSocioProf": null,
                    "ifpIdTypeContrat": null,
                    "ifpIdAffectation1": null,
                    "ifpIdAffectation2": null,
                    "ifpIdAffectation3": null,
                    "ifpIdLibre1": "DSN3",
                    "ifpIdLibre2": null,
                    "ifpIdAffectation1Date": null,
                    "ifpIdAffectation2Date": null,
                    "ifpIdAffectation3Date": null,
                    "ifpIdLibre1Date": "W238 : DSN3",
                    "ifpIdLibre2Date": null,
                    "ifpNumMatricule": null,
                    "ifpIdNumMatriculeRh": "",
                    "ifpIdConteneur": "",
                    "ifpIdBoite": "",
                    "ifpIdLot": "",
                    "ifpIdNumOrdre": "1",
                    "ifpLogin": "MyUsrLogin01",
                    "ifpModedt": "ME0260",
                    "ifpNumdtr": "089",
                    "ifpIdCodeActivite": "",
                    "ifpGeler": false,
                    "ifpCycleTempsParcouru": null,
                    "ifpCycleTempsRestant": null
                }
            }'
        );
    }

    /**
     * Réponse attendue pour le test FreezeDocument
     * @return mixed
     */
    private function expectedResponseFreezeDocument()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
                    "items": [{
                        "ifpId": 3,
                        "ifpNombrepages": 1,
                        "ifpIdCodeChrono": "",
                        "ifpIdNumeroBoiteArchive": "",
                        "ifpIdNomSociete": "SOCIETE LILLE",
                        "ifpIdNomClient": "RSI CO",
                        "ifpCodeClient": "TSI504",
                        "ifpIdCodeSociete": "03",
                        "ifpIdCodeEtablissement": "00002",
                        "ifpIdLibEtablissement": "RSI TSI NORD LILLE LILLE",
                        "ifpIdCodeJalon": "JA",
                        "ifpIdLibelleJalon": "DESTINATAIRE N0 1",
                        "ifpIdNumSiren": "330740382",
                        "ifpIdNumSiret": "33074038200552",
                        "ifpIdIndiceClassement": "D465200",
                        "ifpIdUniqueDocument": null,
                        "ifpIdTypeDocument": null,
                        "ifpIdLibelleDocument": "BULLETIN PAIE 5",
                        "ifpCodeDocument": "D465200",
                        "ifpIdFormatDocument": "PDF",
                        "ifpIdAuteurDocument": null,
                        "ifpIdSourceDocument": "CEL",
                        "ifpIdNumVersionDocument": "1",
                        "ifpIdPoidsDocument": "000000057318",
                        "ifpIdNbrPagesDocument": null,
                        "ifpIdProfilArchivage": null,
                        "ifpIdEtatArchive": null,
                        "ifpIdPeriodePaie": {
                            "date": "2009-10-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdPeriodeExerciceSociale": null,
                        "ifpIdDateDernierAccesDocument": null,
                        "ifpIdDateArchivageDocument": {
                            "date": "2015-09-01 20:53:12.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDureeArchivageDocument": "5",
                        "ifpIdDateFinArchivageDocument": {
                            "date": "2020-09-01 20:53:12.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdNomSalarie": "EUTEBERT",
                        "ifpIdPrenomSalarie": "EVELYNE",
                        "ifpIdNomJeuneFilleSalarie": "DUPONT",
                        "ifpIdDateNaissanceSalarie": {
                            "date": "1972-12-13 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDateEntree": {
                            "date": "1996-01-01 00:00:00.000000",
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
                        "ifpNumMatricule": "01000352",
                        "ifpIdNumMatriculeRh": "01000352",
                        "ifpIdConteneur": "",
                        "ifpIdBoite": "",
                        "ifpIdLot": "",
                        "ifpIdNumOrdre": "0",
                        "ifpLogin": "expertms_DPS",
                        "ifpModedt": "",
                        "ifpNumdtr": "085",
                        "ifpIdCodeActivite": "",
                        "ifpGeler": true,
                        "ifpCycleTempsParcouru": null,
                        "ifpCycleTempsRestant": null
                    }]
                }
            }'
        );
    }

    /**
     * Réponse attendue pour le test SetUnfreezeDocumentSuccess
     * @return mixed
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    private function expectedResponseSetUnfreezeDocumentSuccess()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
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
                    "ifpIdPeriodePaie": "201510",
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
                    "ifpGeler": false,
                    "ifpCycleTempsParcouru": null,
                    "ifpCycleTempsRestant": null
                }
            }'
        );
    }

    /**
     * Réponse attendue pour le test GetDocument à partir d'un niveau
     * @return mixed
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    private function expectedResponseGetDocumentFromLevel()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
                    "items": [{
                        "ifpId": 4,
                        "ifpNombrepages": 4,
                        "ifpIdCodeChrono": "",
                        "ifpIdNumeroBoiteArchive": "",
                        "ifpIdNomSociete": "SOCIETE DEMO PARIS",
                        "ifpIdNomClient": "RSI CO",
                        "ifpCodeClient": "TSI504",
                        "ifpIdCodeSociete": "01",
                        "ifpIdCodeEtablissement": "00001",
                        "ifpIdLibEtablissement": "RSI TSI SIEGE PARIS",
                        "ifpIdCodeJalon": "JA",
                        "ifpIdLibelleJalon": "",
                        "ifpIdNumSiren": "393555123",
                        "ifpIdNumSiret": "39355512300057",
                        "ifpIdIndiceClassement": "D325800",
                        "ifpIdUniqueDocument": null,
                        "ifpIdTypeDocument": null,
                        "ifpIdLibelleDocument": "RECAP. RUBRIQUES S",
                        "ifpCodeDocument": "D325800",
                        "ifpIdFormatDocument": "PDF",
                        "ifpIdAuteurDocument": null,
                        "ifpIdSourceDocument": "SPOOL_ADP",
                        "ifpIdNumVersionDocument": "1",
                        "ifpIdPoidsDocument": "000000021158",
                        "ifpIdNbrPagesDocument": null,
                        "ifpIdProfilArchivage": null,
                        "ifpIdEtatArchive": null,
                        "ifpIdPeriodePaie": {
                            "date": "2014-03-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdPeriodeExerciceSociale": {
                            "date": "2014-01-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDateDernierAccesDocument": null,
                        "ifpIdDateArchivageDocument": {
                            "date": "2014-07-11 10:42:38.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDureeArchivageDocument": "30",
                        "ifpIdDateFinArchivageDocument": null,
                        "ifpIdNomSalarie": null,
                        "ifpIdPrenomSalarie": null,
                        "ifpIdNomJeuneFilleSalarie": null,
                        "ifpIdDateNaissanceSalarie": null,
                        "ifpIdDateEntree": null,
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
                        "ifpNumMatricule": null,
                        "ifpIdNumMatriculeRh": "",
                        "ifpIdConteneur": "",
                        "ifpIdBoite": "",
                        "ifpIdLot": "",
                        "ifpIdNumOrdre": "1",
                        "ifpLogin": "",
                        "ifpModedt": "ME0085",
                        "ifpNumdtr": "089",
                        "ifpIdCodeActivite": "",
                        "ifpGeler": true,
                        "ifpCycleTempsParcouru": null,
                        "ifpCycleTempsRestant": null
                    }, {
                        "ifpId": 5,
                        "ifpNombrepages": 1,
                        "ifpIdCodeChrono": "",
                        "ifpIdNumeroBoiteArchive": "",
                        "ifpIdNomSociete": "SOCIETE DEMO LYON",
                        "ifpIdNomClient": "RSI CO",
                        "ifpCodeClient": "TSI504",
                        "ifpIdCodeSociete": "02",
                        "ifpIdCodeEtablissement": "00003",
                        "ifpIdLibEtablissement": "RSI TSI RHONE LYON",
                        "ifpIdCodeJalon": "JC",
                        "ifpIdLibelleJalon": "DESTINATAIRE N0 3",
                        "ifpIdNumSiren": "330740382",
                        "ifpIdNumSiret": "33074038280448",
                        "ifpIdIndiceClassement": "D325800",
                        "ifpIdUniqueDocument": null,
                        "ifpIdTypeDocument": null,
                        "ifpIdLibelleDocument": "JUSTIF. DECL.PERIOD.",
                        "ifpCodeDocument": "D325800",
                        "ifpIdFormatDocument": "PDF",
                        "ifpIdAuteurDocument": null,
                        "ifpIdSourceDocument": "BVRH UPLOAD",
                        "ifpIdNumVersionDocument": "1",
                        "ifpIdPoidsDocument": "000000013121",
                        "ifpIdNbrPagesDocument": null,
                        "ifpIdProfilArchivage": null,
                        "ifpIdEtatArchive": null,
                        "ifpIdPeriodePaie": {
                            "date": "2015-02-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdPeriodeExerciceSociale": null,
                        "ifpIdDateDernierAccesDocument": null,
                        "ifpIdDateArchivageDocument": {
                            "date": "2015-06-29 15:22:35.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDureeArchivageDocument": "30",
                        "ifpIdDateFinArchivageDocument": null,
                        "ifpIdNomSalarie": null,
                        "ifpIdPrenomSalarie": null,
                        "ifpIdNomJeuneFilleSalarie": null,
                        "ifpIdDateNaissanceSalarie": null,
                        "ifpIdDateEntree": null,
                        "ifpIdDateSortie": null,
                        "ifpIdNumNir": null,
                        "ifpIdCodeCategProfessionnelle": null,
                        "ifpIdCodeCategSocioProf": null,
                        "ifpIdTypeContrat": null,
                        "ifpIdAffectation1": null,
                        "ifpIdAffectation2": null,
                        "ifpIdAffectation3": null,
                        "ifpIdLibre1": "DSN3",
                        "ifpIdLibre2": null,
                        "ifpIdAffectation1Date": null,
                        "ifpIdAffectation2Date": null,
                        "ifpIdAffectation3Date": null,
                        "ifpIdLibre1Date": "W238 : DSN3",
                        "ifpIdLibre2Date": null,
                        "ifpNumMatricule": null,
                        "ifpIdNumMatriculeRh": "",
                        "ifpIdConteneur": "",
                        "ifpIdBoite": "",
                        "ifpIdLot": "",
                        "ifpIdNumOrdre": "1",
                        "ifpLogin": "MyUsrLogin01",
                        "ifpModedt": "ME0260",
                        "ifpNumdtr": "089",
                        "ifpIdCodeActivite": "",
                        "ifpGeler": false,
                        "ifpCycleTempsParcouru": null,
                        "ifpCycleTempsRestant": null
                    }, {
                        "ifpId": 6,
                        "ifpNombrepages": 4,
                        "ifpIdCodeChrono": "",
                        "ifpIdNumeroBoiteArchive": "",
                        "ifpIdNomSociete": "SOCIETE DEMO TOULOUSE",
                        "ifpIdNomClient": "RSI CO",
                        "ifpCodeClient": "TSI504",
                        "ifpIdCodeSociete": "01",
                        "ifpIdCodeEtablissement": "00001",
                        "ifpIdLibEtablissement": "RSI TSI SIEGE PARIS",
                        "ifpIdCodeJalon": "JA",
                        "ifpIdLibelleJalon": "",
                        "ifpIdNumSiren": "393555129",
                        "ifpIdNumSiret": "39355512300059",
                        "ifpIdIndiceClassement": "D325800",
                        "ifpIdUniqueDocument": null,
                        "ifpIdTypeDocument": null,
                        "ifpIdLibelleDocument": "RECAP. RUBRIQUES Z",
                        "ifpCodeDocument": "D325800",
                        "ifpIdFormatDocument": "PDF",
                        "ifpIdAuteurDocument": null,
                        "ifpIdSourceDocument": "CEL",
                        "ifpIdNumVersionDocument": "1",
                        "ifpIdPoidsDocument": "000000021158",
                        "ifpIdNbrPagesDocument": null,
                        "ifpIdProfilArchivage": null,
                        "ifpIdEtatArchive": null,
                        "ifpIdPeriodePaie": {
                            "date": "2015-03-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdPeriodeExerciceSociale": null,
                        "ifpIdDateDernierAccesDocument": null,
                        "ifpIdDateArchivageDocument": {
                            "date": "2015-07-11 10:42:38.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDureeArchivageDocument": "30",
                        "ifpIdDateFinArchivageDocument": null,
                        "ifpIdNomSalarie": null,
                        "ifpIdPrenomSalarie": null,
                        "ifpIdNomJeuneFilleSalarie": null,
                        "ifpIdDateNaissanceSalarie": null,
                        "ifpIdDateEntree": null,
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
                        "ifpNumMatricule": null,
                        "ifpIdNumMatriculeRh": "",
                        "ifpIdConteneur": "",
                        "ifpIdBoite": "",
                        "ifpIdLot": "",
                        "ifpIdNumOrdre": "1",
                        "ifpLogin": "MyUsrLogin02",
                        "ifpModedt": "ME0085",
                        "ifpNumdtr": "089",
                        "ifpIdCodeActivite": "",
                        "ifpGeler": false,
                        "ifpCycleTempsParcouru": null,
                        "ifpCycleTempsRestant": null
                    }]
                }
            }'
        );
    }

    /**
     * Réponse attendue pour le test GetDocument à partir d'un tiroir
     * @return mixed
     */
    private function expectedResponseGetDocumentFromDrawer()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
                    "items": [{
                        "ifpId": 3,
                        "ifpNombrepages": 1,
                        "ifpIdCodeChrono": "",
                        "ifpIdNumeroBoiteArchive": "",
                        "ifpIdNomSociete": "SOCIETE LILLE",
                        "ifpIdNomClient": "RSI CO",
                        "ifpCodeClient": "TSI504",
                        "ifpIdCodeSociete": "03",
                        "ifpIdCodeEtablissement": "00002",
                        "ifpIdLibEtablissement": "RSI TSI NORD LILLE LILLE",
                        "ifpIdCodeJalon": "JA",
                        "ifpIdLibelleJalon": "DESTINATAIRE N0 1",
                        "ifpIdNumSiren": "330740382",
                        "ifpIdNumSiret": "33074038200552",
                        "ifpIdIndiceClassement": "D465200",
                        "ifpIdUniqueDocument": null,
                        "ifpIdTypeDocument": null,
                        "ifpIdLibelleDocument": "BULLETIN PAIE 5",
                        "ifpCodeDocument": "D465200",
                        "ifpIdFormatDocument": "PDF",
                        "ifpIdAuteurDocument": null,
                        "ifpIdSourceDocument": "CEL",
                        "ifpIdNumVersionDocument": "1",
                        "ifpIdPoidsDocument": "000000057318",
                        "ifpIdNbrPagesDocument": null,
                        "ifpIdProfilArchivage": null,
                        "ifpIdEtatArchive": null,
                        "ifpIdPeriodePaie": {
                            "date": "2009-10-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdPeriodeExerciceSociale": null,
                        "ifpIdDateDernierAccesDocument": null,
                        "ifpIdDateArchivageDocument": {
                            "date": "2015-09-01 20:53:12.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDureeArchivageDocument": "5",
                        "ifpIdDateFinArchivageDocument": {
                            "date": "2020-09-01 20:53:12.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdNomSalarie": "EUTEBERT",
                        "ifpIdPrenomSalarie": "EVELYNE",
                        "ifpIdNomJeuneFilleSalarie": "DUPONT",
                        "ifpIdDateNaissanceSalarie": {
                            "date": "1972-12-13 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDateEntree": {
                            "date": "1996-01-01 00:00:00.000000",
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
                        "ifpNumMatricule": "01000352",
                        "ifpIdNumMatriculeRh": "01000352",
                        "ifpIdConteneur": "",
                        "ifpIdBoite": "",
                        "ifpIdLot": "",
                        "ifpIdNumOrdre": "0",
                        "ifpLogin": "expertms_DPS",
                        "ifpModedt": "",
                        "ifpNumdtr": "085",
                        "ifpIdCodeActivite": "",
                        "ifpGeler": false,
                        "ifpCycleTempsParcouru": null,
                        "ifpCycleTempsRestant": null
                    }]
                }
            }'
        );
    }

    /**
     * Réponse attendue pour le test GetDocument à partir d'un tiroir
     * @return mixed
     */
    private function expectedResponseGetDocumentFromDrawerWithCount()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
                    "items": [{
                        "ifpId": 3,
                        "ifpNombrepages": 1,
                        "ifpIdCodeChrono": "",
                        "ifpIdNumeroBoiteArchive": "",
                        "ifpIdNomSociete": "SOCIETE LILLE",
                        "ifpIdNomClient": "RSI CO",
                        "ifpCodeClient": "TSI504",
                        "ifpIdCodeSociete": "03",
                        "ifpIdCodeEtablissement": "00002",
                        "ifpIdLibEtablissement": "RSI TSI NORD LILLE LILLE",
                        "ifpIdCodeJalon": "JA",
                        "ifpIdLibelleJalon": "DESTINATAIRE N0 1",
                        "ifpIdNumSiren": "330740382",
                        "ifpIdNumSiret": "33074038200552",
                        "ifpIdIndiceClassement": "D465200",
                        "ifpIdUniqueDocument": null,
                        "ifpIdTypeDocument": null,
                        "ifpIdLibelleDocument": "BULLETIN PAIE 5",
                        "ifpCodeDocument": "D465200",
                        "ifpIdFormatDocument": "PDF",
                        "ifpIdAuteurDocument": null,
                        "ifpIdSourceDocument": "CEL",
                        "ifpIdNumVersionDocument": "1",
                        "ifpIdPoidsDocument": "000000057318",
                        "ifpIdNbrPagesDocument": null,
                        "ifpIdProfilArchivage": null,
                        "ifpIdEtatArchive": null,
                        "ifpIdPeriodePaie": {
                            "date": "2009-10-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdPeriodeExerciceSociale": null,
                        "ifpIdDateDernierAccesDocument": null,
                        "ifpIdDateArchivageDocument": {
                            "date": "2015-09-01 20:53:12.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDureeArchivageDocument": "5",
                        "ifpIdDateFinArchivageDocument": {
                            "date": "2020-09-01 20:53:12.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdNomSalarie": "EUTEBERT",
                        "ifpIdPrenomSalarie": "EVELYNE",
                        "ifpIdNomJeuneFilleSalarie": "DUPONT",
                        "ifpIdDateNaissanceSalarie": {
                            "date": "1972-12-13 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDateEntree": {
                            "date": "1996-01-01 00:00:00.000000",
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
                        "ifpNumMatricule": "01000352",
                        "ifpIdNumMatriculeRh": "01000352",
                        "ifpIdConteneur": "",
                        "ifpIdBoite": "",
                        "ifpIdLot": "",
                        "ifpIdNumOrdre": "0",
                        "ifpLogin": "expertms_DPS",
                        "ifpModedt": "",
                        "ifpNumdtr": "085",
                        "ifpIdCodeActivite": "",
                        "ifpGeler": false,
                        "ifpCycleTempsParcouru": null,
                        "ifpCycleTempsRestant": null
                    }],
                    "totalCount": "1"
                }
            }'
        );
    }

    /**
     * Réponse attendue pour le test FreezeDocument collectif
     * @return mixed
     */
    private function expectedResponseFreezeCollectiveDocument()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
                    "items": [{
                        "ifpId": 6,
                        "ifpNombrepages": 4,
                        "ifpIdCodeChrono": "",
                        "ifpIdNumeroBoiteArchive": "",
                        "ifpIdNomSociete": "SOCIETE DEMO TOULOUSE",
                        "ifpIdNomClient": "RSI CO",
                        "ifpCodeClient": "TSI504",
                        "ifpIdCodeSociete": "01",
                        "ifpIdCodeEtablissement": "00001",
                        "ifpIdLibEtablissement": "RSI TSI SIEGE PARIS",
                        "ifpIdCodeJalon": "JA",
                        "ifpIdLibelleJalon": "",
                        "ifpIdNumSiren": "393555129",
                        "ifpIdNumSiret": "39355512300059",
                        "ifpIdIndiceClassement": "D325800",
                        "ifpIdUniqueDocument": null,
                        "ifpIdTypeDocument": null,
                        "ifpIdLibelleDocument": "RECAP. RUBRIQUES Z",
                        "ifpCodeDocument": "D325800",
                        "ifpIdFormatDocument": "PDF",
                        "ifpIdAuteurDocument": null,
                        "ifpIdSourceDocument": "CEL",
                        "ifpIdNumVersionDocument": "1",
                        "ifpIdPoidsDocument": "000000021158",
                        "ifpIdNbrPagesDocument": null,
                        "ifpIdProfilArchivage": null,
                        "ifpIdEtatArchive": null,
                        "ifpIdPeriodePaie": {
                            "date": "2015-03-01 00:00:00.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdPeriodeExerciceSociale": null,
                        "ifpIdDateDernierAccesDocument": null,
                        "ifpIdDateArchivageDocument": {
                            "date": "2015-07-11 10:42:38.000000",
                            "timezone_type": 3,
                            "timezone": "Europe\/Paris"
                        },
                        "ifpIdDureeArchivageDocument": "30",
                        "ifpIdDateFinArchivageDocument": null,
                        "ifpIdNomSalarie": null,
                        "ifpIdPrenomSalarie": null,
                        "ifpIdNomJeuneFilleSalarie": null,
                        "ifpIdDateNaissanceSalarie": null,
                        "ifpIdDateEntree": null,
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
                        "ifpNumMatricule": null,
                        "ifpIdNumMatriculeRh": "",
                        "ifpIdConteneur": "",
                        "ifpIdBoite": "",
                        "ifpIdLot": "",
                        "ifpIdNumOrdre": "1",
                        "ifpLogin": "MyUsrLogin02",
                        "ifpModedt": "ME0085",
                        "ifpNumdtr": "089",
                        "ifpIdCodeActivite": "",
                        "ifpGeler": true,
                        "ifpCycleTempsParcouru": null,
                        "ifpCycleTempsRestant": null
                    }]
                }
            }'
        );
    }

    /**
     * Test : GET /document/metadata
     * UseCase : Request pour la liste de metadonnées des documents
     */
    public function testGetListPropertiesFields()
    {
        $expectedResponse = $this->expectedResponseGetListPropertiesFields();
        $route = $this->getUrl('api_apiv1_document_getlistpropertiesfields', array());
        $this->clientRequestGetByParam($route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : POST /document/search - Test sur la recherche de documents
     * UseCase1 : Request - à partir d'un code tiroir et des parametres erronés - ERROR
     * UseCase2 : Request - à partir d'un code niveau (sans compteur)
     * UseCase3 : Request - à partir d'un code tiroir (sans compteur)
     * UseCase4 : Request - à partir d'un code tiroir (avec compteur)
     */
    public function testGetDocumentAction()
    {
        $route = $this->getUrl('api_apiv1_document_postdocumentsearch', array());
        $this->searchDocumentActionUseCase01($route);
        $this->searchDocumentActionUseCase02($route);
        $this->searchDocumentActionUseCase03($route);
        $this->searchDocumentActionUseCase04($route);
    }

    /**
     * UseCase1 : Request - à partir d'un code tiroir et des parametres erronés - ERROR
     * @param $route
     */
    private function searchDocumentActionUseCase01($route)
    {
        // UseCase1
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errDocapostControllerParameterIsMissing",
                        "values": {
                            "__parameter__": "limit"
                        },
                        "fieldName": ""
                    }]
                }
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "node": {
                    "type": "PDC",
                    "leaf": true,
                    "value": ["D150010"]
                },
                "start": 0,
                "sorts": {
                    "ifpIdUniqueDocument": {
                        "dir": "ASC",
                        "pertinence": 0
                    }
                }
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request - à partir d'un code niveau (sans compteur)
     * @param $route
     */
    private function searchDocumentActionUseCase02($route)
    {
        // UseCase2
        $expectedResponse = $this->expectedResponseGetDocumentFromLevel();
        $sentRequest = $this->tools->jsonMinify(
            '{
                "node": {
                    "type": "PDC",
                    "leaf": false,
                    "value": "21025"
                },
                "start": 0,
                "limit": 100,
                "sorts": {
                    "ifpIdUniqueDocument": {
                        "dir": "ASC",
                        "pertinence": 0
                    }
                }
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase3 : Request - à partir d'un code tiroir (sans compteur)
     * @param $route
     */
    private function searchDocumentActionUseCase03($route)
    {
        // UseCase3
        $expectedResponse = $this->expectedResponseGetDocumentFromDrawer();
        $sentRequest = $this->tools->jsonMinify(
            '{
                "node": {
                    "type": "PDC",
                    "leaf": true,
                    "value": [
                        "D465200"
                    ]
                },
                "start": 0,
                "limit": 100,
                "sorts": {
                    "ifpIdUniqueDocument": {
                        "dir": "ASC",
                        "pertinence": 0
                    }
                },
                "totalCount": false
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase4 : Request - à partir d'un code tiroir (avec compteur)
     * @param $route
     */
    private function searchDocumentActionUseCase04($route)
    {
        // UseCase4
        $expectedResponse = $this->expectedResponseGetDocumentFromDrawerWithCount();
        $sentRequest = $this->tools->jsonMinify(
            '{
                "node": {
                    "type": "PDC",
                    "leaf": true,
                    "value": [
                        "D465200"
                    ]
                },
                "start": 0,
                "limit": 100,
                "sorts": {
                    "ifpIdUniqueDocument": {
                        "dir": "ASC",
                        "pertinence": 0
                    }
                },
                "totalCount": true
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : POST /document/search/count - Test sur le compteur de la recherche de documents
     * UseCase1 : Request - à partir d'un code tiroir et des parametres erronés - ERROR
     * UseCase2 : Request - à partir d'un code niveau
     * UseCase3 : Request - à partir d'un code tiroir
     */
    public function testCountDocumentAction()
    {
        $route = $this->getUrl('api_apiv1_document_postdocumentsearchcount', array());
        $this->countDocumentActionUseCase01($route);
        $this->countDocumentActionUseCase02($route);
    }

    /**
     * UseCase1 : Request - à partir d'un code niveau
     * @param $route
     */
    private function countDocumentActionUseCase01($route)
    {
        // UseCase2
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "totalCount": "3"
                }
            }'
        );

        $sentRequest = $this->tools->jsonMinify(
            '{
                "node": {
                    "type": "PDC",
                    "leaf": false,
                    "value": "21025"
                },
                "start": 0,
                "limit": 100,
                "sorts": {
                    "ifpIdUniqueDocument": {
                        "dir": "ASC",
                        "pertinence": 0
                    }
                }
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request - à partir d'un code tiroir
     * @param $route
     */
    private function countDocumentActionUseCase02($route)
    {
        // UseCase3
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "totalCount": "1"
                }
            }'
        );

        $sentRequest = $this->tools->jsonMinify(
            '{
                "node": {
                    "type": "PDC",
                    "leaf": true,
                    "value": [
                        "D465200"
                    ]
                },
                "start": 0,
                "limit": 100,
                "sorts": {
                    "ifpIdUniqueDocument": {
                        "dir": "ASC",
                        "pertinence": 0
                    }
                }
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : GET /document/1/unfreeze
     * UseCase : Request - Test sur le dégel du document N° 1 qui est gelé
     */
    public function testSetUnfreezeDocumentSuccess()
    {
        $expectedResponse = $this->expectedResponseSetUnfreezeDocumentSuccess();
        $route = $this->getUrl('api_apiv1_document_getunfreezedocument', array('documentId' => 1));
        $this->clientRequestGetByParam($route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : GET /document/3/unfreeze
     * UseCase : Request - Test sur le dégel du document N° 3 qui est déjà gelé
     */
    public function testSetUnfreezeDocumentFrozen()
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 2,
                    "infos": [{
                        "code": "errIfpIndexfichePaperlessAlreadyUnfrozen",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );
        $route = $this->getUrl('api_apiv1_document_getunfreezedocument', array('documentId' => 3));
        $this->clientRequestGetByParam($route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : GET /document/{documentId}/freeze - Test sur le gel de documents individuels
     * UseCase1 : Request - le document N°3 n'est pas gelé
     * UseCase2 : Request - le document N°3 est déjà gelé
     */
    public function testFreezeIndividualDocumentSuccess()
    {
        $route = $this->getUrl('api_apiv1_document_getfreezedocument', array('documentId' => 3));

        // UseCase1
        $expectedResponse = $this->expectedResponseFreezeDocument();
        $this->clientRequestGetByParam($route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());

        // UseCase2
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 2,
                    "infos": [{
                        "code": "errIfpIndexfichePaperlessAlreadyFrozen",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );
        $this->clientRequestGetByParam($route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : GET /document/{documentId}/freeze - Test sur le gel de documents collectifs
     * UseCase1 : Request - le document N°6 qui n'est pas gelé
     */
    public function testFreezeCollectiveDocumentSuccess()
    {
        // UseCase1
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_document_getfreezedocument', array('documentId' => 6));
        $expectedResponse = $this->expectedResponseFreezeCollectiveDocument();
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
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

    /**
     * Demande GET du client WS
     *
     * @param $route
     */
    private function clientRequestGetByParam($route)
    {
        $this->client = static::makeclientWithLogin();
        $this->client->request('GET', $route, [], [], $this->mandatoryHeaders);
    }

    /**
     * Test : POST /document/autocomplete/search - Test sur la recherche en autocompletion
     * UseCase1 : Request - à partir du champ ifpIdPrenomSalarie avec un code Pac existant
     * UseCase2 : Request - à partir du champ ifpIdNomJeuneFilleSalarie sur un context nom jeune fille salarié
     * UseCase3 : Request - à partir du champ ifpIdLibEtablissement sur un context Etablissement
     * UseCase4 : Request - à partir du champ ifpIdNomSociete sur un context Societe
     * UseCase5 : Request - à partir du champ ifpIdPrenomSalarie avec un code Pac Non-existant
     */
    public function testPostAutocompleteSearchDocument()
    {
        $route = $this->getUrl('api_apiv1_document_postautocompletesearch', array());
        $this->postAutocompleteSearchDocumentUseCase01($route);
        $this->postAutocompleteSearchDocumentUseCase02($route);
        $this->postAutocompleteSearchDocumentUseCase03($route);
        $this->postAutocompleteSearchDocumentUseCase04($route);
        $this->postAutocompleteSearchDocumentUseCase05($route);
        $this->postAutocompleteSearchDocumentUseCase06($route);
    }

    /**
     * UseCase1 : Request - à partir du champ ifpIdPrenomSalarie avec un code Pac existant
     * @param $route
     */
    private function postAutocompleteSearchDocumentUseCase01($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "code": "STEPHANE",
                    "value": "STEPHANE"
                }]
            }'
        );

        $sentRequest = $this->tools->jsonMinify(
            '{
                "referencialPac": [
                    "TSI504"
                ],
                "actualData": true,
                "archivedData": true,
                "main": {
                    "code": "ifpIdPrenomSalarie",
                    "value": "STEPH"
                },
                "fields": {
                    "ifpIdNomSalarie": [
                        "ACHENAIS"
                    ],
                    "ifpDateEntree": {
                        "start": "820450800",
                        "end": "820450800"
                    }
                },
                "start": 0,
                "limit": 20
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase2 : Request - à partir du champ ifpIdNomJeuneFilleSalarie sur un context nom jeune fille salarié
     * @param $route
     */
    private function postAutocompleteSearchDocumentUseCase02($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "code": "DUPONT",
                    "value": "DUPONT"
                }]
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "referencialPac": [
                    "TSI504"
                ],
                "actualData": true,
                "archivedData": true,
                "main": {
                    "code": "ifpIdNomJeuneFilleSalarie",
                    "value": "DUPONT"
                },
                "fields": {
                    "ifpIdPrenomSalarie": [
                        "EVELYNE"
                    ],
                    "ifpIdDateNaissanceSalarie": {
                        "start": "93049200",
                        "end": "93049200"
                    }
                },
                "start": 0,
                "limit": 20
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase3 : Request - à partir du champ ifpIdLibEtablissement sur un context Etablissement
     * @param $route
     */
    private function postAutocompleteSearchDocumentUseCase03($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "code": "RSI TSI NORD LILLE LILLE",
                    "value": "00002 RSI TSI NORD LILLE LILLE 03"
                }]
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "referencialPac": [
                    "TSI504"
                ],
                "actualData": true,
                "archivedData": true,
                "main": {
                    "code": "ifpIdLibEtablissement",
                    "value": "RSI TSI NORD LILLE LILLE"
                },
                "fields": {
                    "ifpIdNomSalarie": [
                        "EUTEBERT"
                    ],
                    "ifpIdPrenomSalarie": [
                        "EVELYNE"
                    ]
                },
                "start": 0,
                "limit": 20
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase4 : Request - à partir du champ ifpIdNomSociete sur un context Societe
     * @param $route
     */
    private function postAutocompleteSearchDocumentUseCase04($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "code": "SOCIETE DEMO LYON",
                    "value": "33074038280448 SOCIETE DEMO LYON 02"
                }]
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "referencialPac": [
                    "TSI504"
                ],
                "actualData": false,
                "archivedData": true,
                "main": {
                    "code": "ifpIdNomSociete",
                    "value": "SOCIETE DEMO LYON"
                },
                "fields": {
                    "ifpIdCodeSociete": [
                        "02"
                    ],
                    "ifpIdNumSiret": [
                        "33074038280448"
                    ],
                    "ifpIdDateArchivageDocument": {
                        "start": "1435584155",
                        "end": "1435584155"
                    }
                },
                "start": 0,
                "limit": 20
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase5 : Request - à partir du champ ifpIdPrenomSalarie avec un code Pac Non-existant
     * @param $route
     */
    private function postAutocompleteSearchDocumentUseCase05($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "msg": {
                    "level": 3,
                    "infos": [{
                        "code": "errRcsRefCodeSocietePacDoesNotExist",
                        "values": [],
                        "fieldName": ""
                    }]
                }
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "referencialPac": ["TSI574"],
                "actualData": true,
                "archivedData": true,
                "main": {
                    "code": "ifpIdPrenomSalarie",
                    "value": "STEPH"
                },
                "fields": {
                    "ifpIdNomSalarie": ["ACHENAIS"],
                    "ifpDateEntree": {
                        "start": "820450800",
                        "end": "820450800"
                    }
                },
                "start": 0,
                "limit": 20
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * UseCase6 : Request - à partir de la valeur ifpIdNomSalarie sur un context ifpIdNumMatriculeRh
     * @param $route
     */
    private function postAutocompleteSearchDocumentUseCase06($route)
    {
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [{
                    "code": "01000123",
                    "value": "01000123 MARTIN PHILIPPE "
                }]
            }'
        );
        $sentRequest = $this->tools->jsonMinify(
            '{
                "referencialPac": ["TSI504"],
                "actualData": true,
                "archivedData": true,
                "main": {
                    "code": "ifpIdNumMatriculeRh",
                    "value": "M"
                },
                "start": 0,
                "limit": 10,
                "fields": {}
            }'
        );
        $this->clientByParams($route, 'POST', $sentRequest);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : PUT /document
     * UseCase1 : Requête pour tester le contrôle de l'id de l'URL et celui du JSON
     * UseCase2 : Request pour mettre à jour les métadonnées d'un document
     */
    public function testPutDocument()
    {
        $this->client = static::makeclientWithLogin();
        // UseCase 1
        $route = $this->getUrl('api_apiv1_document_putdocument', ['documentId' => 999]);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "ifpId": 5,
                    "ifpIdPeriodePaie": "201604",
                    "ifpIdPeriodeExerciceSociale": "2016",
                    "ifpIdNumeroBoiteArchive": "Test",
                    "ifpCodeDocument": "D256900"
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 400);

        // UseCase 2
        $expectedResponse = $this->expectedResponsePutDocument();
        $route = $this->getUrl('api_apiv1_document_putdocument', ['documentId' => 5]);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "ifpId": 5,
                    "ifpIdPeriodePaie": "201604",
                    "ifpIdPeriodeExerciceSociale": "2016",
                    "ifpIdNumeroBoiteArchive": "Test",
                    "ifpCodeDocument": "D256900"
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    /**
     * Test : DELETE /document
     * UseCase : Request pour supprimer un document
     */
    public function testDeleteDocument()
    {
        $this->client = static::makeclientWithLogin();
        $route = $this->getUrl('api_apiv1_document_deletedocument');
        $this->client->request('DELETE', $route, [], [], $this->mandatoryHeaders, '{"ifpId": 5}');
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Tests : POST /document/export
     */
    public function testPostDocumentExport()
    {
        $this->client = static::makeclientWithLogin(true, [], 'MyUsrLogin02');
        $route = $this->getUrl('api_apiv1_document_postdocumentexport');
        $this->postDocumentExportUseCase01($route);
        $this->postDocumentExportUseCase02($route);
    }

    /**
     * UseCase1 : Request pour exporter le résultat d'une recherche en synchrone
     * @param $route
     */
    private function postDocumentExportUseCase01($route)
    {
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "node": {
                        "type": "PDC",
                        "leaf": true,
                        "value": "D256900"
                    },
                    "columns": [
                        "ifpIdCodeSociete",
                        "ifpIdCodeEtablissement",
                        "ifpIdNumSiret",
                        "ifpIdLibelleDocument",
                        "ifpIdNomSalarie",
                        "ifpIdPrenomSalarie",
                        "ifpIdDateNaissanceSalarie"
                    ],
                    "totalCount": true
                }'
            )
        );
        $this->assertFileComplies('export_doc_MyUsrLogin02.xlsx');
    }

    /**
     * UseCase2 : Request pour exporter le résultat d'une recherche en asynchrone
     * @param $route
     */
    private function postDocumentExportUseCase02($route)
    {
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "node": {
                        "type": "PDC",
                        "leaf": false,
                        "value": 2
                    },
                    "columns": [
                        "ifpIdCodeSociete",
                        "ifpIdCodeEtablissement",
                        "ifpIdNumSiret",
                        "ifpIdLibelleDocument",
                        "ifpIdNomSalarie",
                        "ifpIdPrenomSalarie",
                        "ifpIdDateNaissanceSalarie"
                    ],
                    "totalCount": true
                }'
            )
        );
        // Vérifie que le fichier existe et est intégre dans la base
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": {
                    "rapId": 4,
                    "rapLibelleFic": "export_doc_MyUsrLogin02.xlsx"
                }
            }'
        );
        $this->client->request(
            'GET',
            $this->getUrl('api_apiv1_report_getreportsearch'),
            [],
            [],
            $this->mandatoryHeaders
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());

        // Récupération du fichier
        $result = json_decode($response->getContent(), true);

        $route = $this->getUrl('api_apiv1_report_getreport', ['rapId' => $result['data']['rapId']]);
        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $this->assertFileComplies($result['data']['rapLibelleFic']);
    }

    /**
     * Vérifie que le contenu renvoyé au navigateur est un fichier Excel
     *
     * @param $filename
     */
    private function assertFileComplies($filename)
    {
        $this->client->getResponse()->sendContent();
        $content = ob_get_contents();
        ob_clean();
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->assertStringStartsWith(
            'attachment; filename="' . $filename . '"',
            $this->client->getResponse()->headers->get('Content-Disposition')
        );
        $this->assertNotEmpty($content);
        $this->assertNotNull($content);
    }

    /**
     * Test : POST /document/field/search
     * UseCase : Request pour récupérer les valeurs d'un champ de la table IfpIndexfichePaperless
     */
    public function testPostDocumentFieldSearch()
    {
        $this->client = static::makeclientWithLogin();
        $expectedResponse = $this->tools->jsonMinify(
            '{
                "data": [
                    "CARINE",
                    "EVELYNE",
                    "STEPHANE"
                ]
            }'
        );
        $route = $this->getUrl('api_apiv1_document_postdocumentfieldsearch');
        $this->client->request(
            'POST',
            $route,
            [],
            [],
            $this->mandatoryHeaders,
            $this->tools->jsonMinify(
                '{
                    "field": "ifpIdPrenomSalarie",
                    "distinct": true
                }'
            )
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}
