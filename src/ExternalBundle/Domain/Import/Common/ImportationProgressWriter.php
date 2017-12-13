<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Exception\ValidationException;
use Ddeboer\DataImport\Reader\CountableReader;
use Ddeboer\DataImport\Result;
use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Writer\FlushableWriter;
use Doctrine\ORM\EntityManager;
use ExternalBundle\Entity\ImportationProgress;

class ImportationProgressWriter implements Writer, InitiableWriter, FinalizableWriter, FlushableWriter
{
    protected $progress;

    protected $reader;

    protected $total;

    public function __construct(
        EntityManager $em,
        CountableReader $reader
    ) {
        $this->em = $em;
        $this->reader = $reader;
    }

    public function init(ImportationProgress $progress)
    {
        $this->progress = $progress;
        $this->progress->setTotal($this->getTotal());

        $this->persist();
        $this->flush();
    }

    public function finalize(Result $result)
    {
        $txt = '';
        foreach ($result->getExceptions() as $exception) {
            if ($exception instanceof ValidationException) {
                foreach ($exception->getViolations() as $violation) {
                    $item = $violation->getRoot();
                    if (isset($item['externalId'])) {
                        $txt .= sprintf('ExternalId : %d - ', $item['externalId']);
                    }
                    $txt .= sprintf("%s : %s\n", $violation->getPropertyPath(), $violation->getMessage());
                }
            } else {
                $txt .= sprintf("%s\n", $exception->getMessage());
            }
        }
        $this->progress->setExceptions($txt);

        $this->persist();
        $this->flush();
    }

    public function prepare()
    {
        $this->progress->setTotal($this->getTotal());
        $this->progress->setProgressing(true);

        $this->persist();
        $this->flush();
    }

    public function writeItem(array $item)
    {
        $this->progress->setProgress($this->progress->getProgress() + 1);
        $this->progress->setProgressing(true);

        $this->persist();
    }

    /**
     * {@inheritdoc}
     */
    public function finish()
    {
        $this->progress->setProgressing(false);

        $this->persist();
        $this->flush();
    }

    protected function getTotal()
    {
        if ($this->total == null) {
            $this->total = $this->reader->count();
        }

        return $this->total;
    }

    public function persist()
    {
        $this->em->persist($this->progress);
    }

    /**
     * Flush and clear the entity manager
     */
    public function flush()
    {
        $this->em->flush();
    }
}
