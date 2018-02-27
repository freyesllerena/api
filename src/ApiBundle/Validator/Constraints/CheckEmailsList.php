<?php

namespace ApiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CheckEmailsList extends Constraint
{
    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'validator_emails_list';
    }
}
