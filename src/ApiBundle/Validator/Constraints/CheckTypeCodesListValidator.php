<?php

namespace ApiBundle\Validator\Constraints;

use ApiBundle\Entity\TypType;
use ApiBundle\Manager\TypeManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckTypeCodesListValidator extends ConstraintValidator
{
    /**
     * @var TypeManager
     */
    protected $typeManager;

    public function __construct(TypeManager $typeManager)
    {
        $this->typeManager = $typeManager;
    }

    /**
     * Valide la contrainte de tiroirs qu'ils soient individuels, collectifs ou les deux
     *
     * @param mixed $typValues
     *
     * @param Constraint $constraint
     * @inheritdoc
     */
    public function validate($typValues, Constraint $constraint)
    {
        // Validation
        if (false == $typValues) {
            $this->context->buildViolation(TypType::ERR_LIST_EMPTY)->addViolation();
        }
        // Vérification
        $result = array_diff(
            $typValues,
            $this->typeManager->getListDrawersType($constraint->getType())
        );
        if (count($result)) {
            if (TypType::TYP_INDIVIDUAL == $constraint->getType()) {
                $message = TypType::ERR_IS_NOT_INDIVIDUAL;
            } elseif (TypType::TYP_COLLECTIVE == $constraint->getType()) {
                $message = TypType::ERR_IS_NOT_COLLECTIVE;
            } else {
                $message = TypType::ERR_DOES_NOT_EXIST;
            }
            // Pour chaque code non reconnu, on ajoute un message
            foreach ($result as $code) {
                $this->context->buildViolation($message)
                    ->setParameter('{{ value }}', $code)
                    ->addViolation();
            }
        }
    }
}
