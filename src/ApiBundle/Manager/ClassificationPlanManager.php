<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\DicDictionnaire;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Filtres applicatifs
 *
 * @package ApiBundle\Manager
 */
class ClassificationPlanManager implements ClassificationPlanManagerInterface
{

    /**
     * @var TypeManager
     */
    protected $typeManager;

    /**
     * ClassificationPlanManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->typeManager = $container->get('api.manager.type');
    }

    /**
     * Construit un plan de classement complet
     *
     * @param string $device
     *
     * @return mixed
     */
    public function buildCompleteClassificationPlan(
        $device = DicDictionnaire::DEFAULT_DIC_DEVICE
    ) {
        return $this->typeManager->getTree($device);
    }

    /**
     * Applique un filtre applicatif sur un plan de classement
     *
     * @param array $classificationPlan
     * @param array $filters
     *
     * @return array
     */
    public function filterClassificationPlanByApplication(array $classificationPlan, array $filters)
    {
        $result = array();
        foreach ($classificationPlan as $node) {
            if (isset($node->children)) {
                $children = $this->filterClassificationPlanByApplication($node->children, $filters);
                if ($children) {
                    $node->children = $children;
                    $result[] = $node;
                }
            } else {
                if (in_array($node->id, $filters)) {
                    $result[] = $node;
                }
            }
        }

        return $result;
    }

    /**
     * Filtre un plan de classement par niveau d'abonnement
     *
     * @param array $classificationPlan
     * @param int $membershipLevel
     *
     * @return array
     */
    public function filterClassificationPlanByMembershipLevel(array $classificationPlan, $membershipLevel)
    {
        $results = array();
        foreach ($classificationPlan as $node) {
            if (isset($node->children)) {
                $node->children = $this->filterClassificationPlanByMembershipLevel($node->children, $membershipLevel);
                if ($node->children) {
                    $results[] = $node;
                }
            } else {
                if ($node->subscription <= $membershipLevel) {
                    $results[] = $node;
                }
            }
        }
        return $results;

    }
}
