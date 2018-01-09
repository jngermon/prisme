<?php

namespace AppBundle\Domain\CharacterDataDefinition\Viewer;

use AppBundle\Entity\Character;
use AppBundle\Entity\CharacterDataDefinition;
use Mmc\Processor\Component\Processor;
use Mmc\Processor\Component\ProcessorTrait;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseViewer implements Processor
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

    abstract protected function doProcess($request);

    protected function createOptionResolver()
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['character', 'definition']);

        $resolver->setAllowedTypes('character', Character::class);
        $resolver->setAllowedTypes('definition', CharacterDataDefinition::class);

        return $resolver;
    }
}
