<?php

namespace ExternalBundle\Domain\Synchronizer;

use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use Mmc\Processor\Component\AbstractProcessor;
use ExternalBundle\Domain\Import\Common\ImporterFactory;

class Synchronizer extends AbstractProcessor implements SynchronizerInterface
{
    protected $className;

    protected $importerFactory;

    protected $parentDependencies;

    protected $childrenDependencies;

    public function __construct(
        ImporterFactory $importerFactory
    ) {
        $this->importerFactory = $importerFactory;
        $this->parentDependencies = new \SplPriorityQueue();
        $this->childrenDependencies = new \SplPriorityQueue();
    }

    public function supports($request)
    {
        $request = $this->resolveRequest($request);

        if (!isset($request['class']) || $request['class'] != $this->getSynchronizedClassName()) {
            return false;
        }

        return true;
    }

    public function addParentDependency($optionKey, ImporterFactory $importerFactory, $priority = 0, $relatedKey = 'ids')
    {
        $this->parentDependencies->insert([
            'importerFactory' => $importerFactory,
            'optionKey' => $optionKey,
            'relatedKey' => $relatedKey,
        ], $priority);
    }

    public function addChildDependency($relatedKey, ImporterFactory $importerFactory, $priority = 0, $optionKey = 'ids')
    {
        $this->childrenDependencies->insert([
            'importerFactory' => $importerFactory,
            'optionKey' => $optionKey,
            'relatedKey' => $relatedKey,
        ], $priority);
    }

    protected function doProcess($request)
    {
        $request = $this->resolveRequest($request);

        $options = $request;
        unset($options['class']);
        unset($options['entity']);

        $options['progress'] = true;

        $this->prepare($options);

        $this->importDependencies($this->parentDependencies, $options);

        $res = $this->import($options);

        if (isset($options['ids']) && is_array($options['ids'])) {
            foreach ($options['ids'] as $id) {
                $opts = $options;
                $opts['ids'] = $id;
                $this->importDependencies($this->childrenDependencies, $opts);
            }
        }

        return $res;
    }

    protected function prepare($options)
    {

    }

    protected function importDependencies($dependencies, $options)
    {
        foreach ($dependencies as $dependency) {
            $importerFactory = $dependency['importerFactory'];
            $optionKey = $dependency['optionKey'];
            $relatedKey = $dependency['relatedKey'];

            $importOptions = [
                'progress' => true,
                'synchronization' => isset($options['synchronization']) ? $options['synchronization'] : null,
            ];
            if (isset($options[$optionKey]) && $options[$optionKey]) {
                $importOptions[$relatedKey] = $options[$optionKey];
            }
            $importerFactory->create($importOptions)->process();
        }
    }

    protected function import($options)
    {
        return $this->importerFactory->create($options)->process();
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
        $className = $this->importerFactory->getImportedClassName();
        if (!is_subclass_of($className, SynchronizableInterface::class)) {
            throw new \RuntimeException('The className has to implements SynchronizableInterface');
        }

        return $className;
    }
}
