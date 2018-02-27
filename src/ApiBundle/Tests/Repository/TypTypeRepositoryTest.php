<?php

namespace ApiBundle\Tests\Repository;

class TypTypeRepositoryTest extends AbstractRepositoryTest
{

    /**
     * Initialisation
     */
    public function setUp()
    {
        $this->initDatabaseAndLoadFixtures(array(
            'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
            'ApiBundle\DataFixtures\ORM\LoadUsrUsersData'
        ));
    }

    /**
     * Test : Repository retrieveLevelDrawersList(210)
     * UseCase : Récupération des tiroirs pour un noeud donné
     */
    public function testGetLevelDrawersListAction()
    {
        $this->setAuthenticatedUser(
            $this->getUserByLogin('MyUsrLogin01'),
            ['numinstance' => '000001']
        );
        $this->assertEquals(
            [
                'D256900',
                'D325800'
            ],
            $this->getContainer()->get('api.manager.type')
                ->retrieveLevelDrawersList(210)
        );
    }
}
