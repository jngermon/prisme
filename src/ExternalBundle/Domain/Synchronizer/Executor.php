<?php

namespace ExternalBundle\Domain\Synchronizer;

use Ramsey\Uuid\Uuid;

class Executor
{
    protected $console;

    protected $logFile;

    public function __construct(
        $console = '/srv/bin/console',
        $logFile = '/dev/null'
    ) {
        $this->console = $console;
        $this->logFile = $logFile;
    }

    public function run()
    {
        $command = $this->console;
        $command .= ' external:synchronize --continue';
        $command .= ' --uuid '.Uuid::uuid4();
        $command .= ' >> '.$this->logFile;
        $command .= ' &'; //Important
        exec($command);
    }
}
