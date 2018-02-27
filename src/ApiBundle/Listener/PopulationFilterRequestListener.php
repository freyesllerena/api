<?php

namespace ApiBundle\Listener;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelUserType;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\SQLLogger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PopulationFilterRequestListener implements SQLLogger
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var array
     */
    protected $createdTables = array();

    /**
     * DoctrineFilterRequestListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritdoc
     */
    public function onKernelRequest()
    {
        // Empêche l'application des filtres si utilisateur automate
        $user = $this->getUsrUsers();
        if (null != $user && $user->getUsrType() == EnumLabelUserType::AUTO_USER) {
            return;
        }

        $this->createDoctrineFilter();
        $this->enable();
        $this->registerSQLLogger();
    }

    /**
     * Désactive les filtres par population
     */
    public function disable()
    {
        $filterName = $this->getFilterName();
        $filters = $this->entityManager->getFilters();
        if ($filters->has($filterName)) {
            $filters->disable($filterName);
        }
    }

    /**
     * Active les filtres par population
     */
    public function enable()
    {
        $filterName = $this->getFilterName();
        $filters = $this->entityManager->getFilters();
        if ($filters->has($filterName)) {
            $filters->enable($filterName);
        }
    }

    /**
     * Crée le filtre Doctrine qui va gérer l'application auto des filtres par populations
     */
    protected function createDoctrineFilter()
    {
        $filterName = $this->getFilterName();

        $this->entityManager->getConfiguration()->addFilter(
            $filterName,
            'ApiBundle\PopulationFilter\DoctrineFilter'
        );

        $token = $this->tokenStorage->getToken();
        if ($token && $token->getUser() instanceof UsrUsers) {
            $filter = $this->entityManager->getFilters()->enable($filterName);
            $filter->setUserName($token->getUser()->getUsrLogin());
        }
    }

    /**
     * Enregistre l'écouter SQL
     */
    protected function registerSQLLogger()
    {
        $configuration = $this->entityManager->getConnection()->getConfiguration();
        $sqlLogger = $configuration->getSQLLogger();
        if ($sqlLogger == null) {
            $configuration->setSQLLogger($this);
        } else {
            $newSqlLogger = new LoggerChain();
            $newSqlLogger->addLogger($this);
            $newSqlLogger->addLogger($sqlLogger);
            $configuration->setSQLLogger($newSqlLogger);
        }
    }

    /**
     * @return UsrUsers|null
     */
    protected function getUsrUsers()
    {
        $token = $this->tokenStorage->getToken();
        if ($token && $token->getUser() instanceof UsrUsers) {
            return $token->getUser();
        }
        return null;
    }

    /**
     * Renvoit le nom du filtre doctrine utilisé
     *
     * @return string
     */
    protected function getFilterName()
    {
        $filterName = 'habi_';
        $token = $this->tokenStorage->getToken();
        if ($token && $token->getUser() instanceof UsrUsers) {
            $filterName .= $token->getUser()->getUsrLogin();
        }
        return $filterName;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createIfpTemporaryTable()
    {
        $token = $this->tokenStorage->getToken();
        if ($token && $token->getUser() instanceof UsrUsers) {
            $user = $token->getUser();

            $tableName = 'tmp_habi_ifp_' . $user->getUsrLogin();
            if (in_array($tableName, $this->createdTables)) {
                return;
            }

            $queryBuilder = $this->entityManager
                ->getRepository('ApiBundle:IfpIndexfichePaperless')
                ->createUserHabilitationsQuery($user);

            $queryBuilder->select('ifp.ifpId');

            $sql = $queryBuilder->getQuery()->getSQL();
            $sql = preg_replace(
                '/(\w)*\.ifp_id IN \(SELECT \* FROM `'. $tableName. '`\)/',
                '1',
                $sql
            );

            $this->entityManager->getConnection()->exec('DROP TEMPORARY TABLE IF EXISTS `' . $tableName . '`');
            $stmt = $this->entityManager->getConnection()->prepare(
                'CREATE TEMPORARY TABLE  `' . $tableName . '` AS ' . $sql
            );

            foreach ($queryBuilder->getParameters() as $n => $param) {
                $value = $param->getValue();
                $stmt->bindParam($n + 1, $value);
            }
            $stmt->execute();

            $this->createdTables[] = $tableName;
        }
    }

    /**
     * Supprime les tables temporaires créées
     */
    public function dropCreatedTables()
    {
        foreach ($this->createdTables as $table) {
            $query = 'DROP TEMPORARY TABLE IF EXISTS `' . $table . '`';
            $this->entityManager->getConnection()->exec($query);
        }
    }

    /**
     * Logs a SQL statement somewhere.
     *
     * @inheritdoc
     * @param string $sql The SQL to be executed.
     * @param array|null $params The SQL parameters.
     * @param array|null $types The SQL parameter types.
     *
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $token = $this->tokenStorage->getToken();
        if ($token && $token->getUser() instanceof UsrUsers) {
            $user = $token->getUser();

            if (strpos($sql, 'SELECT * FROM `tmp_habi_ifp_' . $user->getUsrLogin() . '`') !== false) {
                $this->createIfpTemporaryTable();
            }
        }
    }

    /**
     * Marks the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {

    }
}
