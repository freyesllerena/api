<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\PdaProfilDefAppli;
use ApiBundle\Entity\PdhProfilDefHabi;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelUserType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UsrUsersRepository extends BaseRepository
{

    /**
     * Recherche un utilisateur à partir d'un login encodé en base 64
     *
     * @param $encodedLogin
     * @return null|object
     */
    public function findOneByBase64EncodedLogin($encodedLogin)
    {
        return $this->findOneBy(array(
            'usrLogin' => base64_decode($encodedLogin)
        ));
    }

    /**
     * Retourne les utilisateurs associés à un filtre par population
     *
     * @param PdhProfilDefHabi $pdhProfilDefHabi Le filtre par population
     *
     * @return usrUsers[] Une liste d'utilisateurs
     */
    public function findByPdhProfilDefHabi(PdhProfilDefHabi $pdhProfilDefHabi)
    {
        $queryBuilder = $this->createQueryBuilder('usr');
        $queryBuilder->select('usr');
        $this->joinWithPdeProfilDef($queryBuilder);
        $queryBuilder->andWhere("pde.pdeType = 'habi' AND pde.pdeIdProfilDef = :pdh_id");
        $queryBuilder->setParameter('pdh_id', $pdhProfilDefHabi->getPdhId());

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Retourne les utilisateurs associés à un filtre applicatif
     *
     * @param PdaProfilDefAppli $pdaProfilDefAppli Le filtre applicatif
     *
     * @return usrUsers[] Une liste d'utilisateurs
     */
    public function findByPdaProfilDefAppli(PdaProfilDefAppli $pdaProfilDefAppli)
    {
        $queryBuilder = $this->createQueryBuilder('usr');
        $queryBuilder->select('usr');
        $this->joinWithPdeProfilDef($queryBuilder);
        $queryBuilder->andWhere("pde.pdeType = 'appli' AND pde.pdeIdProfilDef = :pda_id");
        $queryBuilder->setParameter('pda_id', $pdaProfilDefAppli->getPdaId());

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Renvoit la liste des utilisateurs avec le nombre filtres applicatifs et population associés
     *
     * @return array
     */
    public function selectAllUsersAndCountFilters()
    {
        $queryBuilder = $this->createQueryBuilder('usr');
        $queryBuilder->select('usr AS user');
        $queryBuilder->addSelect("SUM(CASE WHEN pde.pdeType = 'appli' THEN 1 ELSE 0 END) AS filtres_applicatifs");
        $queryBuilder->addSelect("SUM(CASE WHEN pde.pdeType = 'habi' THEN 1 ELSE 0 END) AS filtres_populations");
        $queryBuilder->andWhere('usr.usrType = :usrType');
        $queryBuilder->setParameter('usrType', EnumLabelUserType::USR_USER);
        $this->joinWithPdeProfilDef($queryBuilder);
        $queryBuilder->groupBy('user');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Ajout d'une jointure sur les filtres par population et applicatifs
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $userAlias
     */
    protected function joinWithPdeProfilDef(QueryBuilder $queryBuilder, $userAlias = 'usr')
    {
        $queryBuilder->leftJoin('ApiBundle:PusProfilUser', 'pus', 'WITH', $userAlias." = pus.pusUser");
        $queryBuilder->leftJoin('ApiBundle:ProProfil', 'pro', 'WITH', 'pro = pus.pusProfil');
        $queryBuilder->leftJoin('ApiBundle:PdeProfilDef', 'pde', 'WITH', 'pde.pdeId = pro.proId');
    }

    /**
     * Renvoit un utilisateur par son login ou le crée à la volée s'il n'existe pas
     *
     * @param string $login    Login de l'utilisateur
     * @param array  $defaults Valeurs par défaut du nouvel utilisateur
     * @return UsrUsers
     * @throws \Throwable
     */
    public function getOrCreateUserByLogin($login, $defaults = array())
    {
        $usr = $this->findOneBy(array('usrLogin' => $login));
        if (null == $usr) {
            if (!isset($defaults['usrTypeHabilitation'])) {
                throw new \BadMethodCallException(
                    "'usrTypeHabilitation' doit être renseigné dans les valeurs par défaut"
                );
            }

            $defaults += array(
                'usrOldPasswordList' => '',
                'usrRaison' => '',
                'usrConfidentialite' => '',
                'usrAdresseMail' => '',
                'usrCommentaires' => '',
                'usrNom' => '',
                'usrPrenom' => '',
                'usrAdressePost' => '',
                'usrTel' => '',
                'usrRsoc' => '',
                'usrAdresseMailCciAuto' => '',
                'usrBeginningDate' => new \DateTime,
                'usrEndingDate' => new \DateTime,
                'usrStatus' => '',
            );

            $usr = new UsrUsers();
            $usr->setUsrLogin($login);
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach ($defaults as $name => $value) {
                $accessor->setValue($usr, $name, $value);
            }
            $this->getEntityManager()->persist($usr);
            $this->getEntityManager()->flush($usr);
        }
        return $usr;
    }

    /**
     * Recherche par autocompletion sur les utilisateurs
     * @param $objParams
     * @param $searchable
     * @param $context
     * @return array|null
     */
    public function queryForAutocompleteUsers($objParams, $searchable, $context)
    {
        $table = 'ApiBundle:UsrUsers';
        $prefix = 'usr';
        $codeMain = ($objParams->main->code)? $objParams->main->code : null;
        $valueMain = ($objParams->main->value)? $objParams->main->value : null;
        $paramsFields = isset($objParams->fields)? $objParams->fields : null;
        $fieldsForSelect = $prefix . '.' . $codeMain . $context;
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQueryBuilder();
        $query->select($fieldsForSelect)
            ->from($table, $prefix);
        // Control Paramètre main
        if (!$codeMain || !$valueMain) {
            return null;
        }
        $query
            ->where(
                $query->expr()->like(
                    $prefix . '.' . $codeMain,
                    $query->expr()->literal('%' . $valueMain . '%')
                )
            );
        foreach ($searchable as $field) {
            if ($codeMain !== $field) {
                $query
                    ->orWhere(
                        $query->expr()->like(
                            $prefix . '.' . $field,
                            $query->expr()->literal('%' . $valueMain . '%')
                        )
                    );
            }
        }
        // Paramètre fields
        if (!is_null($paramsFields)) {
            if ($paramsFields && (0 < count($paramsFields))) {
                foreach ($paramsFields as $keyParamsField => $valuesParamsField) {
                    // Type Array
                    if (is_array($valuesParamsField) && count($valuesParamsField)>0) {
                        $query->andWhere($query->expr()->in($prefix.'.' . $keyParamsField, $valuesParamsField));
                    } elseif (is_string($valuesParamsField) && !is_null($valuesParamsField)) {
                        $query->andWhere($prefix.'.' . $keyParamsField . ' = :param_'.$keyParamsField)
                            ->setParameter('param_'.$keyParamsField, $valuesParamsField);
                    }
                }
            }
        }
        // Le code principal ne doit pas lister de données vides.
        $query->andWhere($prefix.'.' . $codeMain . ' != :code_main')
            ->setParameter('code_main', '');
        $query->groupBy('context');
        $query->orderBy('context', 'ASC');
        if (isset($objParams->start)) {
            $query->setFirstResult($objParams->start);
        }
        if (isset($objParams->limit)) {
            $query->setMaxResults($objParams->limit);
        }
        return $query
            ->getQuery()
            ->getArrayResult();
    }
}
