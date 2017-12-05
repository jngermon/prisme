<?php

namespace ExternalBundle\Domain\User;

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

    public function getUserByEmail($email)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ;

        return $queryBuilder->execute()->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $queryBuilder = new QueryBuilder($this->connection);
        $queryBuilder
            ->select('*')
            ->from('users')
            ->where('idu = :id')
            ->setParameter('id', $id)
            ;

        return $queryBuilder->execute()->fetch(\PDO::FETCH_ASSOC);
    }
}
