<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\DocapostWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

/**
 * Class CustomControllerTest
 * @package ApiBundle\Tests\Controller
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class CustomControllerTest extends DocapostWebTestCase
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
                'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
                'ApiBundle\DataFixtures\ORM\LoadDicDictionnaireData',
                'ApiBundle\DataFixtures\ORM\LoadConConfigData',
                'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
                'ApiBundle\DataFixtures\ORM\LoadProProfilData',
                'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData',
                'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
                'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
                'ApiBundle\DataFixtures\ORM\LoadRidRefIdData',
                'ApiBundle\DataFixtures\ORM\LoadUprUserPreferencesData',
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
     * @param $query
     * @return string
     */
    protected function encodeQueryString($query)
    {
        return base64_encode($query);
    }

    /**
     * @return mixed
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    private function expectedResponseGetCustomContext()
    {
        return $this->tools->jsonMinify(
            '{
                "data": {
                    "userProfile": {
                        "id": "MyUsrLogin02",
                        "lastname": "MyUsrNom02",
                        "firstname": "MyUsrPrenom02",
                        "profile": "chef de file expert",
                        "rights": {
                            "user_access": {
                                "accessBoiteArchive": true,
                                "accessExportCel": true,
                                "accessExportPdfExcel": true,
                                "accessImportHabilitation": false,
                                "accessImportMasse": true,
                                "accessImportUnitaire": true,
                                "accessStatistiques": true
                            },
                            "user_rights": {
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
                                }
                            }
                        }
                    },
                    "appData": {
                        "version": "BVRH 5.0",
                        "langs": [{
                            "code": "fr_FR",
                            "label": "Français",
                            "selected": true
                        }, {
                            "code": "en_EN",
                            "label": "English",
                            "selected": false
                        }, {
                            "code": "it_IT",
                            "label": "Italiano",
                            "selected": false
                        }],
                        "pdc": [{
                            "id": "2",
                            "text": "Test niveau 1: 2",
                            "expanded": false,
                            "leaf": false,
                            "path": [],
                            "children": [{
                                "id": "D150010",
                                "text": "Test tiroir D150010",
                                "leaf": true,
                                "category": "I",
                                "subscription": 2,
                                "path": ["Test niveau 1: 2"]
                            }, {
                                "id": "210",
                                "text": "Test niveau 2: 210",
                                "expanded": false,
                                "leaf": false,
                                "path": ["Test niveau 1: 2"],
                                "children": [{
                                    "id": "D256900",
                                    "text": "Test tiroir D256900",
                                    "leaf": true,
                                    "category": "I",
                                    "subscription": 2,
                                    "path": ["Test niveau 1: 2", "Test niveau 2: 210"]
                                }, {
                                    "id": "21025",
                                    "text": "Test niveau 3: 21025",
                                    "expanded": false,
                                    "leaf": false,
                                    "path": ["Test niveau 1: 2", "Test niveau 2: 210"],
                                    "children": [{
                                        "id": "D325800",
                                        "text": "Test tiroir D325800",
                                        "leaf": true,
                                        "category": "C",
                                        "subscription": 2,
                                        "path": ["Test niveau 1: 2", "Test niveau 2: 210", "Test niveau 3: 21025"]
                                    }],
                                    "category": "C"
                                }],
                                "category": "M"
                            }],
                            "category": "M"
                        }, {
                            "id": "3",
                            "text": "Test niveau 1: 3",
                            "expanded": false,
                            "leaf": false,
                            "path": [],
                            "children": [{
                                "id": "D465200",
                                "text": "Test tiroir D465200",
                                "leaf": true,
                                "category": "I",
                                "subscription": 2,
                                "path": ["Test niveau 1: 3"]
                            }],
                            "category": "I"
                        }],
                        "metaData": {
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
                        },
                        "dashboard": ["W01", "W04"],
                        "preferences": {
                            "dashboard": "some data",
                            "plandeclassement": "some others data",
                            "mesdossiers": [],
                            "completudesAvec": [],
                            "completudesSans": [],
                            "thematiques": [],
                            "docavance": []
                        }
                    },
                    "mapping": {
                        "TSI504": {
                            "ifpTypeContrat": {
                                "06": "CONTRAT EMPLOI JEUNES"
                            },
                            "ifpIdCodeJalon": {
                                "JA": "DESTINATAIRE N0 1"
                            },
                            "ifpIdCodeCategProfessionnelle": {
                                "05": "PDG"
                            },
                            "ifpIdAffectation1":{
                                "01":"DIRECTION GENERALE"
                            },
                            "ifpIdAffectation2":{
                                "0001":"DIRECTION"
                            },
                            "ifpIdAffectation3":{
                                "01":"EUROPE PARIS"
                            },
                            "ifpIdLibre1":{
                                "01":"LOREM IPSUM"
                            },
                            "ifpIdLibre2":{
                                "01":"DOLOR SIT AMET"
                            },
                            "ifpIdCodeActivite":{
                                "01":"CONSECTETUR ADSICIPING"
                            }
                        }
                    },
                    "instance": {
                        "multiPac": false,
                        "pacList": [{
                            "code": "TSI504",
                            "label": "Libellé PAC TSI504",
                            "selected": true
                        }]
                    }
                }
            }'
        );
    }

    /**
     * Test : GET /customContext?device=DES
     * UseCase : Request pour récupérer le user context
     */
    public function testGetCustomContextAction()
    {
        $this->client = static::makeclientWithLogin(
            true,
            [],
            'MyUsrLogin02'
        );
        $expectedResponse = $this->expectedResponseGetCustomContext();
        $queryString = $this->encodeQueryString('device=DES');

        $route = $this->getUrl('api_apiv1_custom_getcustomcontext', array('q' => $queryString));

        $this->client->request('GET', $route, array(), array(), $this->mandatoryHeaders);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $actualResponse = json_encode(
            json_decode(
                $response->getContent()
            ),
            JSON_UNESCAPED_UNICODE
        );
        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
