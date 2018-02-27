<?php

namespace ApiBundle\Tests\Repository;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelModeHabilitationType;
use ApiBundle\Repository\IfpIndexfichePaperlessRepository;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;

class IfpIndexfichePaperlessRepositoryTest extends AbstractRepositoryTest
{
    /**
     * @var IfpIndexfichePaperlessRepository
     */
    protected $repository;

    /**
     * Initialisation
     */
    public function setUp()
    {
        $this->initDatabaseAndLoadFixtures(array(
            'ApiBundle\DataFixtures\ORM\LoadTypTypeData',
            'ApiBundle\DataFixtures\ORM\LoadIfpIndexfichePaperlessData',
        ));

        $this->repository = $this->getRepository('ApiBundle:IfpIndexFichePaperless');
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

        $habi = $this->getRepository('ApiBundle:PdhProfilDefHabi')->find(1);
        $user = $this->getUserByLogin('MyUsrLogin01');

        $habi->setPdhMode(EnumLabelModeHabilitationType::ARCHIVE_MODE);
        $habi->setPdhHabilitationI(
            '<operator>
                <type>and</type>
                <operand>
                    <comparator>
                        <field>ID_NOM_SALARIE</field>
                        <type>eq</type>
                        <value>GRANGER</value>
                    </comparator>
                </operand>
            </operator>'
        );
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
            'SELECT i0_.ifp_id AS ifp_id_0 FROM ifp_indexfiche_paperless i0_ '.
            'WHERE (i0_.ifp_id_nom_salarie = ?) AND (i0_.ifp_id_prenom_salarie = ?)',
            $queryBuilder->getQuery()->getSQL()
        );

        $habi->setPdhMode(EnumLabelModeHabilitationType::REFERENCE_MODE);
        $this->getEntityManager()->persist($habi);
        $this->getEntityManager()->flush();
        $queryBuilder = $this->repository->createUserHabilitationsQuery($user);

        $this->assertEquals(
            'SELECT i0_.ifp_id AS ifp_id_0 FROM ifp_indexfiche_paperless i0_ '.
            'INNER JOIN iin_idx_indiv i1_ ON '.
            '(i0_.ifp_id_num_matricule = i1_.iin_id_num_matricule AND i0_.ifp_id_code_client = i1_.iin_id_code_client)'.
            ' WHERE (i1_.iin_id_nom_salarie = ?) AND (i0_.ifp_id_prenom_salarie = ?)',
            $queryBuilder->getQuery()->getSQL()
        );

        $habi->setPdhMode(EnumLabelModeHabilitationType::MIXED_MODE);
        $this->getEntityManager()->persist($habi);
        $this->getEntityManager()->flush();
        $queryBuilder = $this->repository->createUserHabilitationsQuery($user);

        $this->assertEquals(
            'SELECT i0_.ifp_id AS ifp_id_0 FROM ifp_indexfiche_paperless i0_ '.
            'LEFT JOIN iin_idx_indiv i1_ ON '.
            '(i0_.ifp_id_num_matricule = i1_.iin_id_num_matricule AND i0_.ifp_id_code_client = i1_.iin_id_code_client)'.
            ' WHERE (i0_.ifp_id_nom_salarie = ? OR i1_.iin_id_nom_salarie = ?)'.
            ' AND (i0_.ifp_id_prenom_salarie = ?)',
            $queryBuilder->getQuery()->getSQL()
        );

        $habi->setPdhHabilitationI('');
        $habi->setPdhHabilitationC('');
        $this->getEntityManager()->persist($habi);
        $this->getEntityManager()->flush();
        $queryBuilder = $this->repository->createUserHabilitationsQuery($user);

        $this->assertEquals(
            'SELECT i0_.ifp_id AS ifp_id_0 FROM ifp_indexfiche_paperless i0_ '.
            'LEFT JOIN iin_idx_indiv i1_ ON '.
            '(i0_.ifp_id_num_matricule = i1_.iin_id_num_matricule AND i0_.ifp_id_code_client = i1_.iin_id_code_client)'.
            ' WHERE 1 = 0',
            $queryBuilder->getQuery()->getSQL()
        );

    }
}
