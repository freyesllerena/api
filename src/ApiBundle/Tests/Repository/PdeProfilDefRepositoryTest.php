<?php

namespace ApiBundle\Tests\Repository;

use ApiBundle\Repository\PdaProfilDefAppliRepository;

// @SuppressWarnings("CPD-START")
class PdeProfilDefRepositoryTest extends AbstractRepositoryTest
{

    /**
     * @var PdaProfilDefAppliRepository
     */
    protected $repository;

    public function setUp()
    {
        $this->initDatabaseAndLoadFixtures(array(
                'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
                'ApiBundle\DataFixtures\ORM\LoadProProfilData',
                'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
                'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
                'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
                'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData',
        ));

        $this->repository = $this->getRepository('ApiBundle\Entity\PdeProfilDef');

    }

    /**
     * Teste la recherche des profils associés à un utilisateur
     */
    public function testFindByPdhProfilDefHabi()
    {
        $user01 = $this->getRepository('ApiBundle:UsrUsers')->findOneByUsrLogin("MyUsrLogin01");

        $expected = array(
            $this->repository->findOneBy(array('pdeId' => 1, 'pdeIdProfilDef' => 1, 'pdeType' => 'appli')),
            $this->repository->findOneBy(array('pdeId' => 1, 'pdeIdProfilDef' => 1, 'pdeType' => 'habi')),
        );

        $this->assertEquals(
            $expected,
            $this->repository->findByUser($user01)
        );

    }
}
//@SuppressWarnings("CPD-END")
