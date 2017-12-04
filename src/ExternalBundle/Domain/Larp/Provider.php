<?php

namespace ExternalBundle\Domain\Larp;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class Provider
{
    protected $connection;

    protected $larps = null;

    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
    }

    public function getAll()
    {
        if ($this->larps === null) {
            $this->larps = $this->loadAll();
        }

        return $this->larps;
    }

    protected function loadAll()
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('gn')
            ->orderBy('date_deb', 'desc')
            ;

        return $queryBuilder->execute()->fetchAll();
    }
}
