<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('get_class', [$this, 'getClass']),
        );
    }

    public function getClass($object, $short = true)
    {
        if (!is_object($object)) {
            return '';
        }

        if ($short) {
            return (new \ReflectionClass($object))->getShortName();
        }

        return (new \ReflectionClass($object))->getName();
    }

    public function getName()
    {
        return 'tintin';
    }
}
