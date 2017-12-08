<?php

namespace ExternalBundle\Domain\Mapping;

use Doctrine\Common\Annotations\AnnotationReader;
use ExternalBundle\Annotations\External as ExternalAnnotation;

class ExternalPropertiesProvider
{
    public function getExternalPropertiesFor($entity)
    {
        $entityClass = get_class($entity);

        $reader = new AnnotationReader();
        $reflectionObj = new \ReflectionObject(new $entityClass);

        $externalProperties = [];
        foreach ($reflectionObj->getProperties() as $property) {
            $isExternal = $reader->getPropertyAnnotation($property, ExternalAnnotation::class);
            if ($isExternal) {
                $externalProperties[] = $property->getName();
            }
        }

        return $externalProperties;
    }
}
