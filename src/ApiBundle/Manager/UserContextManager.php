<?php

namespace ApiBundle\Manager;

use ApiBundle\Entity\DicDictionnaire;
use ApiBundle\Entity\IfpIndexfichePaperless;
use ApiBundle\Entity\UsrUsers;
use ApiBundle\Security\UserToken;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserContextManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UserToken
     */
    private $userToken;

    /**
     * @var ConfigManager
     */
    private $configManager;

    /**
     * @var MetadataManager
     */
    private $metadataManager;

    /**
     * @var SecurityManager
     */
    private $securityManager;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->userToken = $container->get('security.token_storage')->getToken();
        $this->configManager = $this->container->get('api.manager.config');
        $this->metadataManager = $this->container->get('api.manager.metadata');
        $this->securityManager = $this->container->get('api.manager.security');
    }

    /**
     * Récupération du UserContext en 4 étapes
     *
     * @param string $device Le support voulu
     *
     * @return array
     */
    public function retrieveUserContext($device = DicDictionnaire::DEFAULT_DIC_DEVICE)
    {
        return [
            'userProfile' => $this->constructUserProfile(),
            'appData' => $this->constructAppData($device),
            'mapping' => $this->constructMapping($instances),
            'instance' => $this->constructInfoInstance($instances)
        ];
    }

    /**
     * Récupère le profil de l'utilisateur
     *
     * @return array
     */
    private function constructUserProfile()
    {
        /** @var UsrUsers $user */
        $user = $this->userToken->getUser();
        return [
            'id' => $user->getUsrLogin(),
            'lastname' => $user->getUsrNom(),
            'firstname' => $user->getUsrPrenom(),
            'profile' => $user->getUsrTypeHabilitation(),
            'rights' => $this->getUserPermissions($user)
        ];
    }

    /**
     * Récupère les données nécessaires au fonctionnement de l'application
     *
     * @param string $device Le support voulu
     *
     * @return mixed
     */
    private function constructAppData($device)
    {
        $appData['version'] = $this->configManager
            ->getParameter('version');

        $langs = [];
        $languages = $this->container->get('api.manager.dictionnaire')
            ->retrieveAvailableLanguageList(
                $device
            );

        foreach ($languages as $label => $code) {
            $langs[] = [
                'code' => $code,
                'label' => $label,
                'selected' => $code == $this->userToken->getUser()->getUsrLangue()
            ];
        }

        $appData['langs'] = $langs;
        $appData['pdc'] = $this->container->get('api.manager.user')
            ->getFilteredClassificationPlanForCurrentUser($device);
        $appData['metaData'] = $this->metadataManager->getListFieldsAndPropertiesDocument()['fields'];
        $appData['dashboard'] = $this->container->getParameter('user_preferences')['defaults']['dashboard'];
        $appData['preferences'] = $this->container->get('doctrine')
            ->getRepository('ApiBundle:UprUserPreferences')->getPreferencesByUserAndDevice(
                $this->userToken->getUser(),
                $device
            );
        return $appData;
    }

    /**
     * Récupération des données du mapping (traduction des données non traduites dans les tables iin et ifp)
     *
     * @param array $instances Un tableau dans lequel on récupère les codes pac et traductions de l'instance
     *
     * @return array
     */
    private function constructMapping(&$instances = [])
    {
        $mappings = [];
        $mappingList = $this->container->get('api.manager.referential')->retrieveAllList();
        foreach ($mappingList as $values) {
            $codeClient = $values['ridIdCodeClient'];
            if ('CodeClient' == $values['ridType']) {
                $instances[$values['ridCode']] = $values['ridLibelle'];
            } else {
                $mappings[$codeClient][IfpIndexfichePaperless::IFP_PREFIX . $values['ridType']][$values['ridCode']] =
                    $values['ridLibelle'];
            }
        }

        return $mappings;
    }

    /**
     * Récupération des codes pac de l'instance à partir de la config
     *
     * @param array $instances Un tableau de codes pac et traductions de l'instance
     *
     * @return mixed
     */
    private function constructInfoInstance($instances = [])
    {
        $multiPac = $this->configManager->getParameter('multi_pac');
        $instance['multiPac'] = $multiPac == 'Y' ? true : false;

        $instance['pacList'] = [];
        $pacList = $this->configManager->getParameter('pac');
        if ($pacList != '') {
            $pacList = explode(';', $pacList);
            foreach ($pacList as $value) {
                $instance['pacList'][] = [
                    'code' => $value,
                    'label' => isset($instances[$value]) ? $instances[$value] : '',
                    'selected' => $value == $this->userToken->getPac()
                ];
            }
        }
        return $instance;
    }

    /**
     * Retourne les droits (permissions et accès) des utilisateurs
     *
     * @param UsrUsers $user
     *
     * @return array
     */
    private function getUserPermissions(UsrUsers $user)
    {
        $userHabilitations = [];
        $authorizations = $this->securityManager->getUserAuthorizations($user);
        foreach ($authorizations as $habilitation => $permissions) {
            if ('right' == substr($habilitation, 0, 5)) {
                $userHabilitations['user_rights'][$habilitation] = $permissions;
            } elseif ('access' == substr($habilitation, 0, 6)) {
                $userHabilitations['user_access'][$habilitation] = $permissions;
            }
        }
        return $userHabilitations;
    }
}
