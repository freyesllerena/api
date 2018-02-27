<?php

namespace ApiBundle\Tests\Repository;

use ApiBundle\Repository\PdhProfilDefHabiRepository;

// @SuppressWarnings("CPD-START")
class PdhProfilDefHabiRepositoryTest extends AbstractRepositoryTest
{

    /**
     * @var PdhProfilDefHabiRepository
     */
    protected $repository;

    /**
     * Initialisation
     */
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

        $this->repository = $this->getRepository('ApiBundle\Entity\PdhProfilDefHabi');

    }

    /**
     * Teste la recherche de filtre par population associés à un utilisateur
     */
    public function testFindByUser()
    {
        $user01 = $this->getRepository('ApiBundle:UsrUsers')->findOneBy(array(
            'usrLogin' => 'MyUsrLogin01'
        ));
        $habi01 = $this->getRepository('ApiBundle:PdhProfilDefHabi')->find(1);

        $actual = $this->repository->findByUser($user01);
        $expected = array($habi01);

        $this->assertEquals($expected, $actual);
    }
}
// @SuppressWarnings("CPD-END")
