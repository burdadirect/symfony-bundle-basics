<?php

namespace HBM\BasicsBundle\Command\Traits;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
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

    /* INTERFACE IMPLEMENTATIONS */

    public function markAsBusy(AbstractExtendableCommand $command, ?int &$exitCode): bool
    {
        if (!$this->isSerial()) {
            return true;
        }

        // Find or create setting.
        $setting = $this->getStateSetting(true);

        // Check setting state.
        if ($setting && ($setting->getVarValueParsed() === SerialCommandInterface::STATE_IDLE)) {
            // Set setting value to "busy"
            $this->updateStateSetting($setting, date('Y-m-d H:i:s'));

            return true;
        }

        $command->getExtendedOutput()->writeln('<failure>Command is busy!</failure>');

        $exitCode = Command::INVALID;

        return false;
    }

    public function markAsIdle(AbstractExtendableCommand $command, int &$exitCode): bool
    {
        if (!$this->isSerial()) {
            return true;
        }

        // Try setting value to "idle".
        if ($setting = $this->getStateSetting()) {
            $this->updateStateSetting($setting, SerialCommandInterface::STATE_IDLE);

            return true;
        }

        $command->getExtendedOutput()->writeln('<failure>Command cannot be set to idle!</failure>');

        $exitCode = Command::INVALID;

        return false;
    }

}
