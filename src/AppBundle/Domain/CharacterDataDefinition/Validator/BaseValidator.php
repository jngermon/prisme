<?php

namespace AppBundle\Domain\CharacterDataDefinition\Validator;

use AppBundle\Entity\Character;
use AppBundle\Entity\CharacterDataDefinition;
use Mmc\Processor\Component\Processor;
use Mmc\Processor\Component\ProcessorTrait;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Context\ExecutionContext;

abstract class BaseValidator implements Processor
{
    use ProcessorTrait;

    public function supports($request)
    {
        if (!is_array($request)) {
            return false;
        }

        try {
            $request = $this->createOptionResolver()->resolve($request);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    protected function doProcess($request)
    {
        $character = $request['character'];
        $definition = $request['definition'];
        $context = $request['context'];

        $value = $character->getData($definition->getName());
        $constraints = $this->getConstraints($definition);

        $violations = $context->getValidator()->validate($value, $constraints);

        foreach ($violations as $violation) {

            $v = $this->cloneViolationWithPropertyPath(
                $violation,
                $context->getPropertyPath('datas['.$definition->getName().']')
            );

            $context->getViolations()->add($v);
        }
    }

    protected function getConstraints(CharacterDataDefinition $definition)
    {
        $constraints = [];
        if ($definition->getMin() > 0) {
            $constraints[] = new Required();
        }

        return $constraints;
    }

    protected function createOptionResolver()
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['character', 'definition', 'context']);

        $resolver->setAllowedTypes('character', Character::class);
        $resolver->setAllowedTypes('definition', CharacterDataDefinition::class);
        $resolver->setAllowedTypes('context', ExecutionContext::class);

        return $resolver;
    }

    protected function cloneViolationWithPropertyPath($violation, $propertyPath)
    {
        return new ConstraintViolation(
            $violation->getMessage(),
            $violation->getMessageTemplate(),
            $violation->getParameters(),
            $violation->getRoot(),
            $propertyPath,
            $violation->getInvalidValue(),
            $violation->getPlural(),
            $violation->getCode(),
            $violation->getConstraint(),
            $violation->getCause()
        );
    }
}
