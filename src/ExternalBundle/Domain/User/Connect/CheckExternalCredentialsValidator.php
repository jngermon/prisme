<?php

namespace ExternalBundle\Domain\User\Connect;

use Doctrine\ORM\EntityRepository;
use ExternalBundle\Domain\Import\Person\ImporterFactory;
use ExternalBundle\Domain\User\Provider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckExternalCredentialsValidator extends ConstraintValidator
{
    protected $provider;

    protected $repository;

    protected $importerFactory;

    public function __construct(
        Provider $provider,
        EntityRepository $repository,
        ImporterFactory $importerFactory
    ) {
        $this->provider = $provider;
        $this->repository = $repository;
        $this->importerFactory = $importerFactory;
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
            return ;
        }

        $person = $this->repository->findOneByExternalId($externalUser['idu']);

        if (!$person) {
            $this->importerFactory->create(['ids' => [$externalUser['idu']]])->process();
            $person = $this->repository->findOneByExternalId($externalUser['idu']);
        }

        if (!$person) {
            $this->context->buildViolation($constraint->messagePersonUnknown)
                ->addViolation();
        }

        $value->setPerson($person);
    }
}
