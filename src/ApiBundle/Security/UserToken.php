<?php

namespace ApiBundle\Security;

use ApiBundle\Entity\UsrUsers;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class UserToken extends AbstractToken
{

    /**
     * @param array|\string[]|\Symfony\Component\Security\Core\Role\RoleInterface[] $user
     * @param array $attributes    Valeurs par défaut
     */
    public function __construct($user, array $attributes = array())
    {
        $attributes = $attributes + array(
            'credentials' => array(),
            'roles'       => array(),
            'pac'         => null,
            'profil'      => null,
            'numinstance' => null,
        );

        parent::__construct(array_filter($attributes['roles']));
        $this->setUser($user);
        $this->setAttribute('pac', $attributes['pac']);
        $this->setAttribute('profil', $attributes['profil']);
        $this->setAttribute('numinstance', $attributes['numinstance']);
    }

    public function getUsername()
    {
        $user = $this->getUser();
        if ($user instanceof UsrUsers) {
            return $user->getUsrLogin();
        }
        return $user;
    }

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getProfil()
    {
        return $this->getAttribute('profil');
    }

    /**
     * @return string
     */
    public function getPac()
    {
        return $this->getAttribute('pac');
    }

    /**
     * @return string
     */
    public function getNumInstance()
    {
        return $this->getAttribute('numinstance');
    }
}


