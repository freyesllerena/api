<?php

namespace ApiBundle\Manager;

interface UserManagerInterface
{

    /**
     * Renvoit le plan de classement filtré pour l'utilisateur actuellement authentifié
     *
     * @param $device
     * @return array
     */
    public function getFilteredClassificationPlanForCurrentUser($device);

    /**
     * Renvoit les filtres par population pour l'utilisateur actuellement connecté
     */
    public function getPopulationFiltersForCurrentUser();
}
