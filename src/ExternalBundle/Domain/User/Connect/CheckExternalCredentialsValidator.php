<?php

namespace ExternalBundle\Domain\User\Connect;

use ExternalBundle\Domain\User\Provider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckExternalCredentialsValidator extends ConstraintValidator
{
    protected $provider;

    public function __construct(
        Provider $provider
    ) {
        $this->provider = $provider;
    }

    public function validate($value, Constraint $constraint)
    {
        $externalUser = $this->provider->getUserByEmail($value->getEmail());

        if (!$externalUser) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return ;
        }


        $encrypt = sha1($externalUser['GDS'].$value->getPassword());

        if ($encrypt != $externalUser['pwd']) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

        $value->setExternalId($externalUser['idu']);
    }
}
