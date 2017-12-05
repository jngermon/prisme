<?php

namespace ExternalBundle\Domain\User\Connect;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckExternalCredentials extends Constraint
{
    public $message = 'external.user.connect.bad_credentials';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
