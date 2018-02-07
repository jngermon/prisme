<?php

namespace AppBundle\Domain\CharacterDataDefinition\Transformer;

use AppBundle\Entity\Character;
use AppBundle\Entity\CharacterDataDefinition;
use Mmc\Processor\Component\Processor;
use Mmc\Processor\Component\ProcessorTrait;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseTransformer implements Processor
{
    use ProcessorTrait;

    public function supports($request)
    {
        if (!is_array($request)) {
            return false;
        }

        try {
            $request = $this->createOptionResolver(isset($request['reverse']) ? $request['reverse'] : false)->resolve($request);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    abstract protected function doProcess($request);

    protected function createOptionResolver($reverse = false)
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['value', 'definition', 'character']);
        $resolver->setDefaults([
            'reverse' => false,
        ]);

        $resolver->setAllowedTypes('definition', CharacterDataDefinition::class);
        $resolver->setAllowedTypes('character', Character::class);
        $resolver->setAllowedTypes('reverse', 'boolean');

        return $resolver;
    }
}
