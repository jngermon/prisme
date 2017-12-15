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
        shell_exec($command);
    }

    public function stop($pid, $command)
    {
        $res = shell_exec('ps -o pid,args');

        $find = false;
        foreach (explode("\n", $res) as $line) {
            if ($line = sprintf(" %d php %s", $pid, $command)) {
                $find = true;
                break;
            }
        }

        if ($find) {
            shell_exec('kill -9 '.$pid);
        }
    }
}
