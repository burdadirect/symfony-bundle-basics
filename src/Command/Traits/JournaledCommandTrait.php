<?php

namespace HBM\BasicsBundle\Command\Traits;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Enum\PostExecutionResult;
use HBM\BasicsBundle\Command\Enum\PreExecutionResult;
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

    /* PRE- AND POST-EXECUTIONS */

    public function startJournaledConsoleOutput(AbstractExtendableCommand $command, ?int &$exitCode): PreExecutionResult
    {
        if (!$this->isJournaled()) {
            return PreExecutionResult::SKIPPED;
        }

        $journaledOutput = new JournaledConsoleOutput($command->getExtendedOutput(), $this->getJournalPath());
        $command->setExtendedOutput($journaledOutput);

        $this->journalStopwatch = new Stopwatch();
        $this->journalStopwatch->start($command->getName(), 'Command execution');

        return PreExecutionResult::SUCCEEDED;
    }

    public function stopJournaledConsoleOutput(AbstractExtendableCommand $command, ?int &$exitCode): PostExecutionResult
    {
        if (!$this->isJournaled()) {
            return PostExecutionResult::SKIPPED;
        }

        $output = $command->getExtendedOutput();

        if (!$output instanceof JournaledConsoleOutput) {
            return PostExecutionResult::FAILED;
        }

        // Clean up empty logs, if necessary.
        if (!$this->keepEmptyJournals() && $output->isEmpty()) {
            $output->deleteJournal();
        }

        // Disable journaling, if journaling file is empty.
        $output->writeToFile($this->keepEmptyJournals() || !$output->isEmpty());
        $output->writeln($this->journalStopwatch->stop($command->getName()));

        return PostExecutionResult::SUCCEEDED;
    }
}
