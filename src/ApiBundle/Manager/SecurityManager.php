<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\UsrUsers;
use ApiBundle\Enum\EnumLabelTypeHabilitationType;
use ApiBundle\Repository\ConConfigRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Sécurité
 *
 * @package ApiBundle\Manager
 */
class SecurityManager
{
    /**
     * @var \ArrayAccess|ConConfigRepositoryInterface
     */
    protected $configuration;

    /**
     * @var array Configuration des droits des profils
     */
    protected $profilesRights;

    /**
     * SecurityManager constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->configuration = $container->get('api.repository.config');
        $this->profilesRights = $container->getParameter('authorizations')['profiles_rights'];
    }

    /**
     * Renvoie les droits d'un utilisateur
     *
     * @param UsrUsers $usr
     *
     * @return array
     */
    public function getUserAuthorizations(UsrUsers $usr)
    {
        // Droits au niveau de la configuration de l'instance
        $instanceRights = $this->configuration->getInstanceAuthorizations();

        // Droits au niveau du profil
        $profileRights = [];
        if (isset($this->profilesRights[$usr->getUsrTypeHabilitation()])) {
            $profileRights = $this->profilesRights[$usr->getUsrTypeHabilitation()] + $instanceRights;
        } elseif (EnumLabelTypeHabilitationType::LEADER_EXPERT_HABILITATION == $usr->getUsrTypeHabilitation()) {
            $profileRights = $this->mergeHabilitationsProfile([
                    $this->profilesRights[EnumLabelTypeHabilitationType::LEADER_HABILITATION],
                    $this->profilesRights[EnumLabelTypeHabilitationType::EXPERT_HABILITATION]
                ]) + $instanceRights;
        }

        // Droits au niveau utilisateur
        $userRights = $usr->getUsrAuthorizations() + $instanceRights + $profileRights;
        // Ajout des droits du profil qui ne sont pas définis sur l'instance
        $instanceRights += $profileRights;
        $authorizations = [];
        foreach (array_keys($userRights) as $habilitation) {
            $dec = $instanceRights[$habilitation] & $userRights[$habilitation] & $profileRights[$habilitation];
            $authorizations[$habilitation] = $this->convertHabilitationToPermissions($habilitation, $dec);
        }
        ksort($authorizations);
        return $authorizations;
    }

    /**
     * Retourne la combinaison des habilitations de plusieurs profils
     *
     * @param array $profiles
     *
     * @return mixed
     */
    private function mergeHabilitationsProfile($profiles = [])
    {
        $results = array_shift($profiles);
        foreach ($profiles as $authorizations) {
            foreach ($authorizations as $habi => $permission) {
                if (isset($results[$habi])) {
                    $results[$habi] = $permission | $results[$habi];
                } else {
                    $results[$habi] = $permission;
                }
            }
        }
        return $results;
    }

    /**
     * Fait la conversion en droits RWD ou true ou false en fonction de l'habilitation
     *
     * @param $habilitation
     * @param $value
     *
     * @return array|bool
     */
    public function convertHabilitationToPermissions($habilitation, $value)
    {
        if ('right' == substr($habilitation, 0, 5)) {
            return $this->decimalToPermissions($value);
        } elseif ('access' == substr($habilitation, 0, 6)) {
            return (bool)$value;
        } else {
            return $value;
        }
    }

    /**
     * Convertit un décimal en permissions R, W, D
     *
     * @param $decimal
     *
     * @return array
     */
    protected function decimalToPermissions($decimal)
    {
        // Convertit en binaire et complète jusqu'à 3 zéros à gauche
        $binary = str_pad(decbin($decimal), 3, '0', STR_PAD_LEFT);

        return array(
            'R' => (bool)$binary[2],
            'W' => (bool)$binary[1],
            'D' => (bool)$binary[0]
        );
    }
}
