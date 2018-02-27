<?php
namespace ApiBundle\Tests\Manager;

use ApiBundle\Manager\ClassificationPlanManager;

class ClassificationPlanManagerTest extends BaseManagerTest
{

    /**
     * @var ClassificationPlanManager
     */
    protected $manager;

    /**
     * Différents noeuds composant un plan de classement
     *
     * @var array
     */
    protected $nodes = array();

    protected function setUp()
    {

        $this->nodes['D501090'] = (object)['id' => 'D501090', 'category' => 'I'];
        $this->nodes['D501040'] = (object)['id' => 'D501040', 'category' => 'C'];
        $this->nodes['D501080'] = (object)['id' => 'D501080', 'category' => 'I'];

        $container = $this->createContainerMock(array(
            'api.manager.type' => $this->getMock('ApiBundle\Manager\TypeManagerInterface')
        ));

        $this->manager = new ClassificationPlanManager($container);
    }

    /**
     *  Créé divers noeuds pour les jeux de tests
     */
    protected function createNodes()
    {
        $nodes = array();

        // Nodes de niveau 3
        $nodes['D501090'] = (object)['id' => 'D501090', 'category' => 'I', 'subscription' => 2];
        $nodes['D501040'] = (object)['id' => 'D501040', 'category' => 'C', 'subscription' => 1];
        $nodes['D501080'] = (object)['id' => 'D501080', 'category' => 'I', 'subscription' => 4];
        $nodes['E200015'] = (object)['id' => 'E200015', 'category' => 'I', 'subscription' => 3];
        $nodes['D501100'] = (object)['id' => 'D501100', 'category' => 'I', 'subscription' => 3];

        // Nodes de niveau 2
        $nodes['102'] =  (object) [ 'id' => 102, 'text' => 'Arrêts de travail', 'children' => array(
            $nodes['D501090'],
            $nodes['D501040'],
            $nodes['D501080'],
        )];
        $nodes['220'] =  (object) [ 'id' => 220, 'text' => 'Documents collectifs', 'children' => array(
            $nodes['E200015']
        )];

        // Nodes de niveau 1
        $nodes['1'] = (object) ['id' => 1, 'text' => 'Dossier', 'children' => [$nodes['102']]];
        $nodes['2'] = (object) ['id' => 2, 'text' => 'Paie et déclaratifs', 'children' => [ $nodes['220']]];

        return $nodes;
    }

    /**
     * Teste l'application d'un filtre applicatif sur un plan de classement à un niveau de profondeur
     *
     * @dataProvider getTestCasesForApplicationFilterWhenDepthEqualsOne
     * @param $codeTiroirs
     * @param $expected
     */
    public function testFilterClassificationPlanByApplicationWhenDepthEqualsOne($codeTiroirs, $expected)
    {
        $nodes = $this->createNodes();

        $pdc = [ $nodes['D501090'], $nodes['D501040'], $nodes['D501080'] ];

        $filters = explode('|', $codeTiroirs);

        $this->assertEquals($expected, $this->manager->filterClassificationPlanByApplication($pdc, $filters));
    }

    /**
     * Jeu de données pour tester un filtre applicatif sur un plan de classement à un niveau
     *
     * @return array
     */
    public function getTestCasesForApplicationFilterWhenDepthEqualsOne()
    {
        $nodes = $this->createNodes();

        return array(
            ['D501040', array($nodes['D501040'])],
            ['D501040|D501090', array($nodes['D501090'], $nodes['D501040'])],
        );
    }

    /**
     * Teste l'application d'un filtre applicatif sur un plan de classement à deux niveaux de profondeur
     *
     * @dataProvider getTestCasesForApplicationFilterWhenDepthEqualsTwo
     * @param $codeTiroirs
     * @param $expected
     */
    public function testFilterClassificationPlanByApplicationWhenDepthEqualsTwo($codeTiroirs, $expected)
    {

        $nodes = $this->createNodes();
        $pdc = [ $nodes['102'], $nodes['220'] ];

        $filters = explode('|', $codeTiroirs);

        $actual = $this->manager->filterClassificationPlanByApplication($pdc, $filters);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Jeu de données pour tester un filtre applicatif sur un plan de classement à deux niveaux
     *
     * @return array
     */
    public function getTestCasesForApplicationFilterWhenDepthEqualsTwo()
    {

        $nodes = $this->createNodes();

        return array(
            array(
                'D501040',
                [(object) ['id' => 102, 'text' => 'Arrêts de travail', 'children' => [$nodes['D501040']] ]]
            ),
        );
    }

    /**
     * Teste l'application d'un filtre applicatif sur un plan de classement à trois niveaux de profondeur
     *
     * @dataProvider getTestCasesForApplicationFilterWhenDepthEqualsThree
     *
     * @param $codeTiroirs
     * @param $expected
     */
    public function testFilterClassificationPlanByApplicationWhenDepthEqualsThree($codeTiroirs, $expected)
    {

        $nodes = $this->createNodes();

        $pdc = [ $nodes['1'], $nodes['2'] ];

        $filters = explode('|', $codeTiroirs);

        $actual = $this->manager->filterClassificationPlanByApplication($pdc, $filters);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Jeu de données pour tester un filtre applicatif sur un plan de classement à trois niveaux
     *
     * @return array
     */
    public function getTestCasesForApplicationFilterWhenDepthEqualsThree()
    {

        $nodes = $this->createNodes();

        return array(
            array('D501040|E200015', [
                (object) ['id' => 1, 'text' => 'Dossier', 'children' => [
                    (object) ['id' => 102, 'text' => 'Arrêts de travail', 'children' => [
                        $nodes['D501040'],
                    ]]
                ]],
                $nodes['2']
            ]),
            array('E200015', [ $nodes['2'] ])
        );
    }

    /**
     * Teste le filtrage par abonnement d'un plan de classement sur un niveau
     *
     * @dataProvider getTestCasesForMembershipFilterWhenDepthEqualsOne
     *
     * @param $membershipLevel
     * @param $expected
     */
    public function testFilterClassificationPlanByMembershipWhenDepthEqualsOne($membershipLevel, $expected)
    {

        $nodes = $this->createNodes();

        $pdc = [ $nodes['D501090'], $nodes['D501040'], $nodes['D501080'], $nodes['D501100'] ];

        $this->assertEquals(
            $expected,
            $this->manager->filterClassificationPlanByMembershipLevel($pdc, $membershipLevel)
        );

    }

    /**
     * Jeu de données pour tester le filtrage par abonnement d'un plan de classement à un niveau
     *
     * @return array
     */
    public function getTestCasesForMembershipFilterWhenDepthEqualsOne()
    {
        $nodes = $this->createNodes();

        return array(
            [1, array( $nodes['D501040'] )],
            [2, array( $nodes['D501090'], $nodes['D501040']) ],
            [3, array( $nodes['D501090'], $nodes['D501040'], $nodes['D501100'] )],
            [4, array( $nodes['D501090'], $nodes['D501040'], $nodes['D501080'], $nodes['D501100'] )]
        );
    }

    /**
     * Teste le filtrage par abonnement d'un plan de classement sur deux niveaux
     *
     * @dataProvider getTestCasesForMembershipFilterWhenDepthEqualsTwo
     *
     * @param $membershipLevel
     * @param $expected
     */
    public function testFilterClassificationPlanByMembershipWhenDepthEqualsTwo($membershipLevel, $expected)
    {
        $nodes = $this->createNodes();

        $pdc = array($nodes['102'], $nodes['220']);

        $this->assertEquals(
            $expected,
            $this->manager->filterClassificationPlanByMembershipLevel($pdc, $membershipLevel)
        );
    }

    /**
     * Jeu de données pour tester le filtrage par abonnement d'un plan de classement à deux niveaux
     *
     * @return array
     */
    public function getTestCasesForMembershipFilterWhenDepthEqualsTwo()
    {
        $nodes = $this->createNodes();

        return array(
            [1, array(
                (object) ['id' => 102, 'text' => 'Arrêts de travail', 'children' => [ $nodes['D501040'] ]]
            )],

            [2, array(
                (object) [
                    'id' => 102,
                    'text' => 'Arrêts de travail',
                    'children' => [ $nodes['D501090'], $nodes['D501040'] ]
                ]
            )],

            [3, array(
                (object) [
                    'id' => 102,
                    'text' => 'Arrêts de travail',
                    'children' => [ $nodes['D501090'], $nodes['D501040'] ]
                ],
                (object) [ 'id' => 220, 'text' => 'Documents collectifs', 'children' => [ $nodes['E200015'] ]]
            )],

            [4, array( $nodes['102'], $nodes['220']) ]
        );
    }

    /**
     * Teste le filtrage par abonnement d'un plan de classement sur trois niveaux
     *
     * @dataProvider getTestCasesForMembershipFilterWhenDepthEqualsThree
     *
     * @param $membershipLevel
     * @param $expected
     */
    public function testFilterClassificationPlanByMembershipWhenDepthEqualsThree($membershipLevel, $expected)
    {
        $nodes = $this->createNodes();
        $pdc = array($nodes['1'], $nodes['2']);

        $this->assertEquals(
            $expected,
            $this->manager->filterClassificationPlanByMembershipLevel($pdc, $membershipLevel)
        );
    }

    /**
     * Jeu de données pour tester le filtrage par abonnement d'un plan de classement à trois niveaux
     *
     * @return array
     */
    public function getTestCasesForMembershipFilterWhenDepthEqualsThree()
    {
        $nodes = $this->createNodes();

        return array(
            [1, array(
                (object) [ 'id' => 1, 'text' => 'Dossier', 'children' => [
                    (object) [ 'id' => 102, 'text' => 'Arrêts de travail', 'children' => [ $nodes['D501040'] ] ]
                ]]
            )],
            [2, array(
                (object) [ 'id' => 1, 'text' => 'Dossier', 'children' => [
                    (object) [
                        'id' => 102,
                        'text' => 'Arrêts de travail',
                        'children' => [ $nodes['D501090'], $nodes['D501040']]
                    ]
                ]],
            )],
            [3, array(
                (object) [ 'id' => 1, 'text' => 'Dossier', 'children' => [
                    (object) [
                        'id' => 102,
                        'text' => 'Arrêts de travail',
                        'children' => [ $nodes['D501090'], $nodes['D501040'] ]
                    ]
                ]],
                (object) [ 'id' => 2, 'text' => 'Paie et déclaratifs', 'children' => [
                    (object) [ 'id' => 220, 'text' => 'Documents collectifs', 'children' => [ $nodes['E200015'] ] ]
                ]]
            )],
            [4, array($nodes[1], $nodes[2])]

        );
    }
}
