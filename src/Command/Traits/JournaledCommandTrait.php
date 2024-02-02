<?php

namespace HBM\BasicsBundle\Command\Traits;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Output\JournaledConsoleOutput;
use Symfony\Component\Stopwatch\Stopwatch;

trait JournaledCommandTrait
{
    private Stopwatch $journalStopwatch;

    /* ABSTRACT FUNCTIONS */

    abstract protected function getJournalPath(): string;

    /* STEERING FUNCTIONS */

    protected function isJournaled(): bool
    {
        return true;
    }

    protected function keepEmptyJournals(): bool
    {
        return false;
    }

    /* INTERFACE IMPLEMENTATIONS */

    public function startJournaledConsoleOutput(AbstractExtendableCommand $command, ?int &$exitCode): bool
    {
        if (!$this->isJournaled()) {
            return true;
        }

        $journaledOutput = new JournaledConsoleOutput($command->getExtendedOutput(), $this->getJournalPath());
        $command->setExtendedOutput($journaledOutput);

        $this->journalStopwatch = new Stopwatch();
        $this->journalStopwatch->start($command->getName(), 'Command execution');

        return true;
    }

    public function stopJournaledConsoleOutput(AbstractExtendableCommand $command, int &$exitCode): bool
    {
        if (!$this->isJournaled()) {
            return true;
        }

        $output = $command->getExtendedOutput();

        if (!$output instanceof JournaledConsoleOutput) {
            return true;
        }

        if ($this->keepEmptyJournals()) {
            $output->markAsUsed();
        }

        if ($output->hasBeenUsed()) {
            $output->writeln($this->journalStopwatch->stop($command->getName()));
        }

        return true;
    }
}
