<?php

namespace ApiBundle\Tests\Repository;

use ApiBundle\Repository\PdaProfilDefAppliRepository;

// @SuppressWarnings("CPD-START")
class PdaProfilDefAppliRepositoryTest extends AbstractRepositoryTest
{

    /**
     * @var PdaProfilDefAppliRepository
     */
    protected $repository;

    /**
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp()
    {
        // @SuppressWarnings("CPD-START")
        $this->initDatabaseAndLoadFixtures(array(
            'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
            'ApiBundle\DataFixtures\ORM\LoadProProfilData',
            'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
            'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
            'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
            'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData',
        ));

        $this->repository = $this->getRepository('ApiBundle\Entity\PdaProfilDefAppli');

    }

    /**
     * Teste la recherche de filtre applicatifs associés à un utilisateur
     */
    public function testFindByUser()
    {

        $user01 = $this->getRepository('ApiBundle:UsrUsers')->findOneBy(array(
            'usrLogin' => "MyUsrLogin01"
        ));
        $filtreAppli01 = $this->getRepository('ApiBundle:PdaProfilDefAppli')->find(1);

        $actual = $this->repository->findByUser($user01);
        $expected = array($filtreAppli01);

        $this->assertEquals($expected, $actual);
    }
}
//@SuppressWarnings("CPD-END")
