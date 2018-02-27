<?php

namespace ApiBundle\Tests\Manager;

use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\LasLaserlike;
use ApiBundle\Manager\DocumentManager;
use ApiBundle\Security\UserToken;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class DocumentManagerTest extends BaseManagerTest
{

    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject[]
     */
    protected $repositories;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ContainerInterface
     */
    protected $container;

    /**
     * Initialisation
     */
    public function setUp()
    {
        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken(new UserToken('toto'));

        $this->repositories = array(
            'ApiBundle:LasLaserlike' => $this->getMock('ApiBundle\Repository\LasLaserlikeRepositoryInterface'),
            'ApiBundle:ConConfig' => new \ArrayObject(),
        );

        $this->container = $this->createContainerMock(array(
            'doctrine' => $this->createDoctrineMock($this->repositories),
            'api.manager.metadata' => $this->getMock('ApiBundle\Manager\MetadataManagerInterface'),
            'api.manager.interkeepass' => $this->getMock('ApiBundle\Manager\InterkeepassManagerInterface'),
            'api.dynamic_connection' => $this->getMock('ApiBundle\Listener\DynamicDatabaseConnectionListenerInterface'),
            'security.token_storage' => $tokenStorage
        ));

        $this->documentManager = new DocumentManager($this->container, $tokenStorage);
    }

    /**
     * Teste la génération de script VIS
     *
     * @dataProvider getDataCreateVisScript
     *
     * @param $documentsAssocies
     * @param $localisation
     * @param $expected
     */
    public function testCreateVisScript($documentsAssocies, $localisation, $expected)
    {
        $this->setIkpConfiguration(array(
            'vis_root' => 'VISROOT',
            'cfec_cert' => '[Certificat]',
            'cfec_coffre' => '[UrlDuCoffre]',
            'esc_coffre' => '[UrlDuCoffre]',
            'esc_cert' => '[Certificat]',
            'esc_salle' => 'Salle',
        ));

        $plages = $this->createPdfPlages();

        $this->repositories['ApiBundle:ConConfig']['RACINE_VIS'] = 'c:\images';
        $this->repositories['ApiBundle:LasLaserlike']->method('findOneByNumeroPdf')
            ->will($this->returnValueMap(array(
                array('1234', $plages['1000->1500'])
            )));

        $doc = new IfpIndexfichePaperless();
        $doc->setIfpDocumentsassocies($documentsAssocies);
        $doc->setIfpVdmLocalisation($localisation);

        $this->assertEquals(
            $this->documentManager->createVisScript($doc),
            $expected
        );
    }

    /**
     * Renvoit jeu de tests pour tester création de script VIS
     */
    public function getDataCreateVisScript()
    {
        return array(
            array('E1234/1/1', 'S/S/1/179/339002', 'VIS_GET_FILE;c:\images\1234.pdf;S;S;;;;;'),
            array(
                'E1234/1/1',
                'S/C/1/179/339002',
                'VIS_GET_FILE;c:\images\1234.pdf;S;C;;[UrlDuCoffre];1-179-339002;[Certificat];'
            ),
            array(
                'E1234/1/1',
                'S/E/1/179/339002',
                'VIS_GET_FILE;c:\images\1234.pdf;S;E;;[UrlDuCoffre];179;[Certificat];'
            ),
        );
    }

    /**
     * Créé les plages de PDF
     *
     * @return array
     */
    protected function createPdfPlages()
    {
        $plages = array();
        $plages['1000->1500'] = (new LasLaserlike())
            ->setLasDebut(1000)
            ->setLasFin(1000)
            ->setLasCfecBase('INTERKEEPASS CFECVIS PARAM2')
            ->setLasCfecCert('INTERKEEPASS CFECVIS PARAM1')
            ->setLasChemin('<racine_vis>\\');

        return $plages;
    }

    /**
     * Mocke la configuration des paramètres IKP
     *
     * @param array $config
     */
    protected function setIkpConfiguration(array $config)
    {
        $this->container->get('api.manager.interkeepass')->method('getConfiguration')
            ->will($this->returnValue($config));
    }
}
