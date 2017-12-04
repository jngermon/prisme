<?php

namespace ExternalBundle\Domain\Import\Common;

use Ddeboer\DataImport\Reader\CountableReader;
use Pagerfanta\Pagerfanta;

class PagerfantaReader implements CountableReader
{
    private $pager;

    private $iterator;

    private $currentPage;

    public function __construct(Pagerfanta $pager)
    {
        $this->pager = $pager;
        $this->rewind();
    }

    public function getFields()
    {
        return $this->current();
    }

    public function current()
    {
        return $this->getIterator()->current();
    }

    public function next()
    {
        ++$this->key;
        $this->getIterator()->next();

        if (!$this->iterator->valid()) {
            ++$this->currentPage;
            if ($this->currentPage <= $this->pager->getNbPages()) {
                $this->pager->setCurrentPage($this->pager->getNextPage());
            }

            $this->iterator = $this->pager->getIterator();
        }
    }

    public function key()
    {
        return $this->key;
    }

    public function valid()
    {
        return $this->currentPage <= $this->pager->getNbPages()
            && $this->getIterator()->valid();
    }

    public function rewind()
    {
        $this->currentPage = 1;
        $this->pager->setCurrentPage($this->currentPage);
        $this->iterator = null;
        $this->key = 0;
    }

    public function count()
    {
        return $this->pager->count();
    }

    private function getIterator()
    {
        if (null === $this->iterator) {
            $this->iterator = $this->pager->getIterator();
        }

        return $this->iterator;
    }
}
