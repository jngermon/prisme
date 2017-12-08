<?php

namespace ExternalBundle\Domain\Synchronizer;

use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use Mmc\Processor\Component\AbstractProcessor;
use ExternalBundle\Domain\Import\Common\Importer;

class Synchronizer extends AbstractProcessor implements SynchronizerInterface
{
    protected $className;

    protected $importer;

    public function __construct(
        Importer $importer
    ) {
        $this->importer = $importer;
    }

    public function getMappings($request)
    {
        if (!$this->supports($request)) {
            return null;
        }

        return $this->importer->getMappings();
    }

    public function supports($request)
    {
        $request = $this->resolveRequest($request);

        if (!isset($request['class']) || $request['class'] != $this->getSynchronizedClassName()) {
            return false;
        }

        return true;
    }

    protected function doProcess($request)
    {
        $request = $this->resolveRequest($request);

        $options = $request;
        unset($options['class']);
        unset($options['entity']);

        $this->prepare($options);

        return $this->importer->import($options);
    }

    protected function prepare($options)
    {

    }

    protected function resolveRequest($request)
    {
        if (!is_array($request)) {
            $request = ['entity' => $request];
        }

        $className = $this->getSynchronizedClassName();
        if (isset($request['entity']) && $request['entity'] instanceof $className && $request['entity']->getExternalId()) {
            $request['class'] = $className;
            $request['ids'] = [$request['entity']->getExternalId()];
        }

        return $request;
    }

    protected function getSynchronizedClassName()
    {
        $className = $this->importer->getImportedClassName();
        if (!is_subclass_of($className, SynchronizableInterface::class)) {
            throw new \RuntimeException('The className has to implements SynchronizableInterface');
        }

        return $className;
    }
}
