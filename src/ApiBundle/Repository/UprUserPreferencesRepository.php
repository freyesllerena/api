<?php
namespace ApiBundle\Repository;

use ApiBundle\Entity\UsrUsers;

class UprUserPreferencesRepository extends BaseRepository
{
    /**
     * Renvoit les préférences d'un utilisateur
     *
     * @param UsrUsers $user
     * @return array
     */
    public function getPreferencesByUserAndDevice(UsrUsers $user, $device)
    {
        $results = array(
            'dashboard' => array(),
            'plandeclassement' => array(),
            'mesdossiers' => array(),
            'completudesAvec' => array(),
            'completudesSans' => array(),
            'thematiques' => array(),
            'docavance' => array(),
        );

        $dql = 'SELECT upr.uprType, upr.uprData FROM ApiBundle:UprUserPreferences upr INDEX BY upr.uprType'.
        ' WHERE upr.uprUser = :user AND upr.uprDevice = :device';
        $query = $this->_em->createQuery($dql);
        $rows = $query->execute(array(
            'user' => $user,
            'device' => $device,
        ));
        foreach ($rows as $row) {
            $results[$row['uprType']] = $row['uprData'];
        }

        return $results;
    }
}
