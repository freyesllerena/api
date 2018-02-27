<?php

namespace ApiBundle\Tests\Repository;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Security\UserToken;
use ApiBundle\Tests\DocapostWebTestCase;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\Tools\SchemaTool;

abstract class AbstractRepositoryTest extends DocapostWebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Références défini dans les fixtures
     *
     * @var ReferenceRepository
     */
    protected $references;

    /**
     * Initialise la base de données et charge des fixtures
     *
     * @param $fixtures
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function initDatabaseAndLoadFixtures($fixtures)
    {
        // Flush memcache content
        $this->getContainer()->get('memcache.default')->flush();
        // Prepare fixtures
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->postFixtureSetup();

        $this->loadFixtures($fixtures);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Renvoit le repository d'une entité
     *
     * @param $entityName
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($entityName)
    {
        return $this->entityManager->getRepository($entityName);
    }

    /**
     * Définit l'utilisateur actuellement connecté
     *
     * @param UsrUsers $user
     * @param array $attributes
     */
    protected function setAuthenticatedUser(UsrUsers $user, $attributes = [])
    {
        $this->getContainer()->get('security.token_storage')->setToken(
            new UserToken(
                $user,
                $attributes
            )
        );
    }

    /**
     * Renvoit un utilisateur par son login
     *
     * @param $login
     * @return null|UsrUsers
     */
    protected function getUserByLogin($login)
    {
        return $this->getRepository('ApiBundle:UsrUsers')->findOneBy(array(
            'usrLogin' => $login
        ));
    }
}
