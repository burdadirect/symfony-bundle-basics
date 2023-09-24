<?php

namespace HBM\BasicsBundle\Command;

use HBM\BasicsBundle\Entity\Interfaces\SettingInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractSerialCommand extends Command
{
    public const STATE_IDLE        = 'idle';
    public const STATE_INTERRUPTED = 'interrupted';

    /* ABSTRACT FUNCTIONS */

    abstract protected function isSerial(): bool;

    abstract protected function getStateSetting(bool $create = false): ?SettingInterface;

    abstract protected function updateStateSetting(SettingInterface $setting, string $state): void;

    /* HELPER */

    /**
     * Mark command as busy.
     */
    protected function markAsBusy(OutputInterface $output): bool
    {
        if (!$this->isSerial()) {
            return true;
        }

        // Find or create setting.
        $setting = $this->getStateSetting(true);

        // Check setting state.
        if ($setting && ($setting->getVarValueParsed() === self::STATE_IDLE)) {
            // Set setting value to "busy"
            $this->updateStateSetting($setting, date('Y-m-d H:i:s'));

            return true;
        }

        $output->writeln('<failure>Command is busy!</failure>');

        return false;
    }

    /**
     * Mark command as idle.
     */
    protected function markAsIdle(): bool
    {
        if (!$this->isSerial()) {
            return true;
        }

        // Find setting.
        $setting = $this->getStateSetting();

        if ($setting) {
            // Set setting value to "idle".
            $this->updateStateSetting($setting, self::STATE_IDLE);

            return true;
        }

        return false;
    }

    /**
     * Add predefined styles.
     */
    protected function prettifyOutput(OutputInterface $output): void
    {
        // error, info, comment, questions are already defined by symfony.

        $style = new OutputFormatterStyle('cyan', null, ['bold']);
        $output->getFormatter()->setStyle('note', $style);

        $style = new OutputFormatterStyle('magenta', null, ['bold']);
        $output->getFormatter()->setStyle('section', $style);

        $style = new OutputFormatterStyle('yellow', null, ['bold']);
        $output->getFormatter()->setStyle('highlight', $style);

        $style = new OutputFormatterStyle('green', null, ['bold']);
        $output->getFormatter()->setStyle('success', $style);

        $style = new OutputFormatterStyle('red', null, ['bold']);
        $output->getFormatter()->setStyle('failure', $style);
    }

    protected function htmlifyOutput(string $message): string
    {
        $replacements = [
          '<failure>'    => '<strong style="color:#FF0000;">',
          '</failure>'   => '</strong>',
          '<success>'    => '<strong style="color:#008811;">',
          '</success>'   => '</strong>',
          '<section>'    => '<strong style="color:#8844AA;">',
          '</section>'   => '</strong>',
          '<highlight>'  => '<strong style="color:#FFAA00">',
          '</highlight>' => '</strong>',
          '<note>'       => '<strong style="color:#6699EE;">',
          '</note>'      => '</strong>',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }

    /* EXECUTION */

    abstract protected function executeLogic(InputInterface $input, OutputInterface $output): int;

    /**
     * (non-PHPdoc)
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->prettifyOutput($output);

        // Mark as busy
        if (!$this->markAsBusy($output)) {
            return Command::FAILURE;
        }

        // Execution logic
        $result = $this->executeLogic($input, $output);

        // Mark as idle
        if ($result === 0) {
            $this->markAsIdle();
        }

        // Return result code
        return $result;
    }
}
