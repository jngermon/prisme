<?php

namespace ExternalBundle\Domain\Synchronizer;

use Ddeboer\DataImport\Result;
use ExternalBundle\Domain\Import\Common\ImporterFactory;
use ExternalBundle\Domain\Import\Common\SynchronizableInterface;
use Mmc\Processor\Component\AbstractProcessor;

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

        $importers = [];
        $this->addDependencyImporters($importers, $this->parentDependencies, $options);

        $importers[] = $this->createImporter($options);

        if (isset($options['ids']) && is_array($options['ids'])) {
            foreach ($options['ids'] as $id) {
                $opts = $options;
                $opts['ids'] = $id;
                $this->addDependencyImporters($importers, $this->childrenDependencies, $opts);
            }
        } elseif (isset($options['larp_id'])) {
            $opts = $options;
            $opts['larp_id'] = $options['larp_id'];
            $this->addDependencyImporters($importers, $this->childrenDependencies, $opts);
        }

        foreach ($importers as $importer) {
            $importer->init();
        }

        $startTime = new \DateTime();
        $totalCount = 0;
        $exceptions = new \SplObjectStorage();
        foreach ($importers as $importer) {
            $res = $importer->process();

            $totalCount += $res->getTotalProcessedCount();
            $exceptions->addAll($res->getExceptions());
        }
        $endTime = new \DateTime();

        return new Result('Synchronizer', $startTime, $endTime, $totalCount, $exceptions);
    }

    protected function prepare($options)
    {

    }

    protected function addDependencyImporters(&$importers, $dependencies, $options)
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
            } elseif (isset($options['larp_id'])) {
                $importOptions['larp_id'] = $options['larp_id'];
            }

            $importers[] = $importerFactory->create($importOptions);
        }

        return $importers;
    }

    protected function createImporter($options)
    {
        return $this->importerFactory->create($options);
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
