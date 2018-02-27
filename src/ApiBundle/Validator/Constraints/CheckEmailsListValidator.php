<?php

namespace ApiBundle\Validator\Constraints;

use ApiBundle\Entity\ComCompletude;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\ConstraintValidator;

class CheckEmailsListValidator extends ConstraintValidator
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $emailsList
     * @param Constraint $constraint
     * @inheritdoc
     */
    public function validate($emailValues, Constraint $constraint)
    {
        if ($emailValues != '') {
            $emailValues = explode(';', $emailValues);
            // Validateur
            $validator = $this->container->get('validator');
            $emailConstraint = new Email();
            // Contrôle que chaque email est valide
            foreach ($emailValues as $email) {
                if (0 !== count($validator->validate($email, $emailConstraint))) {
                    // Erreur de validation
                    $this->context->buildViolation(ComCompletude::ERR_EMAIL_INCORRECT)
                        ->setParameter('{{ value }}', $email)
                        ->addViolation();
                }
            }
        }
    }
}
