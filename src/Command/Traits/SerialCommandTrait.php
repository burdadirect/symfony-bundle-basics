<?php

namespace HBM\BasicsBundle\Command\Traits;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use HBM\BasicsBundle\Command\Enum\PostExecutionResult;
use HBM\BasicsBundle\Command\Enum\PreExecutionResult;
use HBM\BasicsBundle\Command\Interfaces\SerialCommandInterface;
use HBM\BasicsBundle\Entity\Interfaces\SettingInterface;
use Symfony\Component\Console\Command\Command;

trait SerialCommandTrait
{
    /* ABSTRACT FUNCTIONS */

    abstract protected function getStateSetting(bool $create = false): ?SettingInterface;

    abstract protected function updateStateSetting(SettingInterface $setting, string $state): void;

    /* STEERING FUNCTIONS */

    protected function isSerial(): bool
    {
        return true;
    }

    /* PRE- AND POST-EXECUTIONS */

    public function markAsBusy(AbstractExtendableCommand $command, ?int &$exitCode): PreExecutionResult
    {
        if (!$this->isSerial()) {
            return PreExecutionResult::SKIPPED;
        }

        // Find or create setting.
        $setting = $this->getStateSetting(true);

        // Check setting state.
        if ($setting && ($setting->getVarValueParsed() === SerialCommandInterface::STATE_IDLE)) {
            // Set setting value to "busy"
            $this->updateStateSetting($setting, date('Y-m-d H:i:s'));

            return PreExecutionResult::SUCCEEDED;
        }

        $command->getExtendedOutput()->writeln('<failure>Command is busy!</failure>');

        $exitCode = Command::INVALID;
        return PreExecutionResult::ABORTED;
    }

    public function markAsIdle(AbstractExtendableCommand $command, ?int &$exitCode): PostExecutionResult
    {
        if (!$this->isSerial()) {
            return PostExecutionResult::SKIPPED;
        }

        // Try setting value to "idle".
        if ($setting = $this->getStateSetting()) {
            $this->updateStateSetting($setting, SerialCommandInterface::STATE_IDLE);

            return PostExecutionResult::SUCCEEDED;
        }

        $command->getExtendedOutput()->writeln('<failure>Command cannot be set to idle!</failure>');

        $exitCode = Command::INVALID;
        return PostExecutionResult::FAILED;
    }

}
