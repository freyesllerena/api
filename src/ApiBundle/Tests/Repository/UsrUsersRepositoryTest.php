<?php

namespace ApiBundle\Tests\Repository;

use ApiBundle\Repository\PdaProfilDefAppliRepository;

// @SuppressWarnings("CPD-START")
class UsrUsersRepositoryTest extends AbstractRepositoryTest
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

        $this->repository = $this->getRepository('ApiBundle\Entity\UsrUsers');

    }

    /**
     * Teste la recherche d'utilisateurs associé à un filtre par population
     */
    public function testFindByPdhProfilDefHabi()
    {

        $habi01 = $this->getRepository('ApiBundle:PdhProfilDefHabi')->find(1);
        $user01 = $this->repository->findOneByUsrLogin("MyUsrLogin01");
        $user02 = $this->repository->findOneByUsrLogin("MyUsrLogin02");

        $actual = $this->repository->findByPdhProfilDefHabi($habi01);
        $expected = array($user01, $user02);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Teste la recherche d'utilisateurs associé à un filtre applicatif
     */
    public function testFindByPdaProfilDefAppli()
    {
        $appli01 = $this->getRepository('ApiBundle:PdaProfilDefAppli')->find(1);
        $user01 = $this->repository->findOneByUsrLogin("MyUsrLogin01");

        $actual = $this->repository->findByPdaProfilDefAppli($appli01);
        $expected = array($user01);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Teste la selection de tous les utilisateurs avec le nombre de filtres associés pour chaque utilisateur
     */
    public function testSelectAllUsersAndCountFilters()
    {
        $user01 = $this->repository->findOneByUsrLogin("MyUsrLogin01");
        $user02 = $this->repository->findOneByUsrLogin("MyUsrLogin02");
        $user03 = $this->repository->findOneByUsrLogin("MyUsrLogin03");

        $expected = array(
            array('user' => $user01, 'filtres_applicatifs' => 1,'filtres_populations' =>  1),
            array('user' => $user02, 'filtres_applicatifs' => 1,'filtres_populations' =>  1),
            array('user' => $user03, 'filtres_applicatifs' => 0,'filtres_populations' =>  0),
        );

        $rows = $this->repository->selectAllUsersAndCountFilters("MyUsrLogin01");
        $this->assertEquals(
            $expected,
            $rows
        );
    }

    public function testGetOrCreateUserByLogin()
    {
        $user01 = $this->repository->findOneByUsrLogin("MyUsrLogin01");
        $user = $this->repository->getOrCreateUserByLogin("MyUsrLogin01");
        $this->assertSame($user01, $user);

        try {
            $usr = $this->repository->getOrCreateUserByLogin("UserCreatedOnTheFly");
            $this->fail("Une exception de type \BadMethodCallException était attendu");
        } catch (\BadMethodCallException $exception) {}

        $usr = $this->repository->getOrCreateUserByLogin("UserCreatedOnTheFly", array(
            'usrTypeHabilitation' => 'expertms'
        ));
        $this->assertNotNull($usr);
        $this->assertEquals($usr->getUsrTypeHabilitation(), 'expertms');
        $this->assertSame(
            $usr,
            $this->repository->findOneByUsrLogin("UserCreatedOnTheFly")
        );

    }
}
//@SuppressWarnings("CPD-END")
