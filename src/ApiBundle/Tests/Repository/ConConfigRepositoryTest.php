<?php

namespace ApiBundle\Tests\Repository;

use ApiBundle\Repository\ConConfigRepository;

class ConConfigRepositoryTest extends AbstractRepositoryTest
{

    /**
     * @var ConConfigRepository
     */
    protected $configRepository;

    /**
     * Initialisation
     */
    public function setUp()
    {
        $this->initDatabaseAndLoadFixtures(array(
            'ApiBundle\DataFixtures\ORM\LoadConConfigData',
        ));

        $this->configRepository = $this->getRepository('ApiBundle:ConConfig');
    }

    /**
     * Teste la récupération de la valeur d'un paramètre de configuration
     */
    public function testGetConfigurationValueWithArrayNotation()
    {
        $this->assertEquals($this->configRepository['type_abo_visu'], 2);
        $this->assertEquals($this->configRepository['pac'], 'TSI504');
        $this->assertNull($this->configRepository['une_valeur_quelconque']);

    }

    /**
     * Teste l'existence de la valeur d'un paramètre de configuration
     */
    public function testIssetConfigurationValueWithArrayNotation()
    {
        $this->assertTrue(isset($this->configRepository['type_abo_visu']));
        $this->assertTrue(isset($this->configRepository['pac']));
        $this->assertFalse(isset($this->configRepository['une_valeur_quelconque']));
    }

    /**
     * Vérifie qu'un paramètre ne peut pas être modifié
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Not implemented
     */
    public function testSetConfigurationValueWithArrayNotation()
    {
        $this->configRepository['type_abo_visu'] = 2;
    }

    /**
     * Vérifie qu'un paramètre ne peut pas être supprimé
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Not implemented
     */
    public function testUnsetConfigurationValueWithArrayNotation()
    {
        unset($this->configRepository['type_abo_visu']);
    }

    /**
     * Test le renvoi des droits au niveau de l'instance
     */
    public function testGetInstanceAuthorizations()
    {
        $this->assertEquals(
            [
                'rightAnnotationDoc' => '7',
                'rightAnnotationDossier' => '7',
                'rightRechercheDoc' => '7',
                'accessStatistiques' => '1',
                'accessExportPdfExcel' => '1',
                'accessImportMasse' => '1',
                'accessBoiteArchive' => '1'
            ],
            $this->configRepository->getInstanceAuthorizations()
        );
    }
}
