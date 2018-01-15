<?php

namespace AppBundle\Domain\LarpParameters\Normalizer;

use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

abstract class BaseDenormalizer implements DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if ($this->serializer === null) {
            throw new BadMethodCallException('Please set a serializer before calling denormalize()!');
        }
        if (!is_array($data)) {
            throw new InvalidArgumentException('Data expected to be an array, '.gettype($data).' given.');
        }
        if (!$this->supportsDenormalization($data, $class)) {
            throw new InvalidArgumentException('Unsupported class: '.$class);
        }
        return $data;
    }
    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === $this->getSupportedClassname();
    }

    abstract protected function getSupportedClassname();
}
