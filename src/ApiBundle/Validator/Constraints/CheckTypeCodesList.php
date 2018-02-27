<?php

namespace ApiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CheckTypeCodesList extends Constraint
{
    protected $type;

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'validator_type_codes_list';
    }

    public function getType()
    {
        return $this->type;
    }
}
