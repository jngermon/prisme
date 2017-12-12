<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Reader\CountableReader;
use Ddeboer\DataImport\Writer;
use Doctrine\ORM\EntityManager;
use ExternalBundle\Entity\ImportationProgress;
use Ddeboer\DataImport\Writer\FlushableWriter;

class ImportationProgressWriter implements Writer, InitiableWriter, FlushableWriter
{
    protected $progress;

    protected $reader;

    protected $total;

    public function __construct(
        EntityManager $em,
        CountableReader $reader,
        $uuid = null,
        $name = null
    ) {
        $this->em = $em;
        $this->reader = $reader;

        $this->progress = new ImportationProgress();
        $this->progress->setUuid($uuid);
        $this->progress->setName($name);
    }

    public function init()
    {
        $this->progress->setTotal($this->getTotal());

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
