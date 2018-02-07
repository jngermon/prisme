<?php

namespace AppBundle\Doctrine\Listeners;

use AppBundle\Domain\CharacterDataDefinition\Transformer\Transformer;
use AppBundle\Entity\Character;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class CharacterListener
{
    protected $transformer;

    public function __construct(Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $object = $event->getObject();

        if (!$object instanceof Character) {
            return;
        }

        $this->transform($object);
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $object = $event->getObject();

        if (!$object instanceof Character) {
            return;
        }

        $this->transform($object);
    }

    public function postLoad(LifecycleEventArgs $event)
    {
        $object = $event->getObject();

        if (!$object instanceof Character) {
            return;
        }

        $this->reverseTransform($object);
    }

    protected function transform(Character $character)
    {
        $definitions = $character->getLarp()->getCharacterDataDefinitions()->toArray();

        usort($definitions, function ($d1, $d2) {
            return $d1->getTransformerPriority() < $d2->getTransformerPriority();
        });

        foreach ($definitions as $definition) {
            if ($definition->getDefault() !== null) {
                $value = $character->getData($definition->getName());
                $value = $this->transformer->transform($value, $definition, $character);
                $character->setData($definition->getName(), $value);
            }
        }
    }

    protected function reverseTransform(Character $character)
    {
        $definitions = $character->getLarp()->getCharacterDataDefinitions()->toArray();

        usort($definitions, function ($d1, $d2) {
            return $d1->getTransformerPriority() > $d2->getTransformerPriority();
        });

        foreach ($definitions as $definition) {
            if ($definition->getDefault() !== null) {
                $value = $character->getData($definition->getName());
                $value = $this->transformer->reverseTransform($value, $definition, $character);
                $character->setData($definition->getName(), $value);
            }
        }
    }
}