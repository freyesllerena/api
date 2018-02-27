<?php

namespace ApiBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ModuleVoter extends Voter
{

    protected $modules;

    const VIEW = 'view';
    const WRITE = 'write';
    const DELETE = 'delete';

    /**
     * @param $modules
     */
    public function __construct($modules)
    {
        $this->modules = $modules;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::WRITE, self::DELETE))) {
            return false;
        }

        if (!in_array($subject, $this->modules)) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        print 'voteOnAttribute';
        // TODO: Implement voteOnAttribute() method.
    }
}