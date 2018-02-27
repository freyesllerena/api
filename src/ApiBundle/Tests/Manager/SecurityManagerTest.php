<?php

namespace ApiBundle\Tests\Manager;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumNumPermissionType;
use ApiBundle\Manager\SecurityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SecurityManagerTest extends BaseManagerTest
{

    /**
     * @var SecurityManager
     */
    protected $manager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Initialisation
     */
    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->createContainerMock(
            array(
                'api.repository.config' => $this->getMock('ApiBundle\Repository\ConConfigRepositoryInterface'),
            ),
            array(
                'authorizations' => array(
                    'profiles_rights' => array(
                        'chef de file' => array(
                            'rightAnnotationDoc' => 0,
                            'rightAnnotationDossier' => 0,
                            'rightClasser' => 0,
                            'rightCycleDeVie' => 0,
                            'rightRechercheDoc' => 0,
                            'rightRecycler' => 0,
                            'rightUtilisateurs' => 3,
                            'accessExportCel' => false,
                            'accessImportUnitaire' => false,
                            'accessImportHabilitation' => false
                        ),
                        'expert' => array(
                            'rightAnnotationDoc' => 7,
                            'rightAnnotationDossier' => 7,
                            'rightClasser' => 7,
                            'rightCycleDeVie' => 3,
                            'rightRechercheDoc' => 7,
                            'rightRecycler' => 7,
                            'rightUtilisateurs' => 0,
                            'accessExportCel' => true,
                            'accessImportUnitaire' => true,
                            'accessImportHabilitation' => false
                        )
                    )
                )
            )
        );

        $this->manager = new SecurityManager($this->container);
    }

    /**
     * Teste le renvoi des droits au niveau utilisateur
     */
    public function testGetUserAuthorizations()
    {
        $config = $this->container->get('api.repository.config');
        $config->method('getInstanceAuthorizations')->will($this->returnValue(array(
            'rightAnnotationDoc' => 1,
            'rightAnnotationDossier' => 3,
            'rightRechercheDoc' => 3,
            'accessStatistiques' => true,
            'accessExportPdfExcel' => true,
            'accessImportMasse' => false,
            'accessBoiteArchive' => true,
        )));

        $usr = new UsrUsers();
        $usr->setUsrTypeHabilitation('chef de file expert');
        $usr->setUsrRightAnnotationDoc(7);
        $usr->setUsrRightAnnotationDossier(7);
        $usr->setUsrRightClasser(1);
        $usr->setUsrRightCycleDeVie(3);
        $usr->setUsrRightRechercheDoc(1);
        $usr->setUsrRightRecycler(0);
        $usr->setUsrRightUtilisateurs(1);
        $usr->setUsrAccessExportCel(false);
        $usr->setUsrAccessImportUnitaire(true);

        $expected = array(
            'rightAnnotationDoc' => array('R' => true, 'W' => false, 'D' => false),
            'rightAnnotationDossier' => array('R' => true, 'W' => true, 'D' => false),
            'rightClasser' => array('R' => true, 'W' => false, 'D' => false),
            'rightCycleDeVie' => array('R' => true, 'W' => true, 'D' => false),
            'rightRechercheDoc' => array('R' => true, 'W' => false, 'D' => false),
            'rightRecycler' => array('R' => false, 'W' => false, 'D' => false),
            'rightUtilisateurs' => array('R' => true, 'W' => false, 'D' => false),
            'accessBoiteArchive' => true,
            'accessExportCel' => false,
            'accessExportPdfExcel' => true,
            'accessImportHabilitation' => false,
            'accessImportMasse' => false,
            'accessImportUnitaire' => true,
            'accessStatistiques' => true
        );

        $this->assertEquals(
            $expected,
            $this->manager->getUserAuthorizations($usr)
        );
    }
}
