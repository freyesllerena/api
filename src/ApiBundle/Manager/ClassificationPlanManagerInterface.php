<?php

namespace ApiBundle\Manager;

interface ClassificationPlanManagerInterface
{

    /**
     * Construit un plan de classement complet
     *
     * @param $device
     * @return array
     */
    public function buildCompleteClassificationPlan($device);

    /**
     * Applique un filtre applicatif sur un plan de classement
     *
     * @param array $classificationPlan
     * @param array $filters
     *
     * @return array
     */
    public function filterClassificationPlanByApplication(array $classificationPlan, array $filters);

    /**
     * Filtre un plan de classement par niveau d'abonnement
     *
     * @param array $classificationPlan
     * @param int $membershipLevel
     *
     * @return array
     */
    public function filterClassificationPlanByMembershipLevel(array $classificationPlan, $membershipLevel);
}
