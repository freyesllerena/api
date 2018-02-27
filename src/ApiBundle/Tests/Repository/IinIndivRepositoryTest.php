<?php

namespace ApiBundle\Tests\Repository;

use ApiBundle\Repository\IinIdxIndivRepository;

class IinIndivRepositoryTest extends AbstractRepositoryTest
{

    /**
     * @var IinIdxIndivRepository
     */
    protected $repository;

    /**
     * Initialisation
     */
    public function setUp()
    {
        $this->initDatabaseAndLoadFixtures(array(
            'ApiBundle\DataFixtures\ORM\LoadIinIdxIndivData',
        ));

        $this->repository = $this->getRepository('ApiBundle\Entity\IinIdxIndiv');
    }

    /**
     * Vérifie que la génération du SQL d'un filtre par population ne renvoit pas d'erreur
     */
    public function testQueryBuilderWithHabilitationsReturnsNoSQLErrors()
    {
        $this->loadFixtures(array(
            'ApiBundle\DataFixtures\ORM\LoadUsrUsersData',
            'ApiBundle\DataFixtures\ORM\LoadProProfilData',
            'ApiBundle\DataFixtures\ORM\LoadPusProfilUserData',
            'ApiBundle\DataFixtures\ORM\LoadPdaProfilDefAppliData',
            'ApiBundle\DataFixtures\ORM\LoadPdhProfilDefHabiData',
            'ApiBundle\DataFixtures\ORM\LoadPdeProfilDefData',
        ));

        $user = $this->getUserByLogin('MyUsrLogin01');

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->from('ApiBundle:IinIdxIndiv', 'd')->select('d');
        $queryBuilder->getQuery()->getSQL();

        $habi = $this->getRepository('ApiBundle:PdhProfilDefHabi')->find(1);
        $habi->setPdhHabilitationC(
            '<operator>
                <type>and</type>
                <operand>
                    <comparator>
                        <field>ID_PRENOM_SALARIE</field>
                        <type>eq</type>
                        <value>HERMIONE</value>
                    </comparator>
                </operand>
            </operator>'
        );
        $this->getEntityManager()->persist($habi);
        $this->getEntityManager()->flush();
        $queryBuilder = $this->repository->createUserHabilitationsQuery($user);

        $this->assertEquals(
            'SELECT i0_.iin_id AS iin_id_0 FROM iin_idx_indiv i0_ WHERE i0_.iin_id_prenom_salarie = ?',
            $queryBuilder->getQuery()->getSQL()
        );
    }
}
