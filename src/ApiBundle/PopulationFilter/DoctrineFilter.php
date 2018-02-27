<?php
/**
 * Created by PhpStorm.
 * User: mmorel
 * Date: 15/04/2016
 * Time: 11:20
 */

namespace ApiBundle\PopulationFilter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Class DoctrineFilter
 * @package ApiBundle\PopulationFilter
 */
class DoctrineFilter extends SQLFilter
{

    const TEMP_TABLE_IFP_PATTERN = 'tmp_habi_iin_%s';

    /**
     * @var string
     */
    protected $username;

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Gets the SQL query part to add to a query.
     *
     * @inheritdoc
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $entity, $tableAlias)
    {
        if ($entity->getName() == 'ApiBundle\Entity\IfpIndexfichePaperless' &&
            $this->username
        ) {
            return $tableAlias. '.ifp_id IN (SELECT * FROM `tmp_habi_ifp_'.$this->username.'`)';
        }

        return '';
    }
}
