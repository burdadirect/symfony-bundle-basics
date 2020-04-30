<?php

namespace HBM\BasicsBundle\Command;

use HBM\BasicsBundle\Entity\Interfaces\SettingInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractSerialCommand extends Command {

  public const STATE_IDLE = 'idle';
  public const STATE_BUSY = 'busy';

  protected $serialExecution  = TRUE;

  /**************************************************************************/
  /* HELPER                                                                 */
  /**************************************************************************/

  abstract protected function getStateSetting(bool $create = FALSE) : ?SettingInterface;
  abstract protected function updateStateSetting(SettingInterface $setting, string $state) : void;

  /**
   * Mark command as busy.
   *
   * @param OutputInterface $output
   *
   * @return bool
   */
  protected function markAsBusy(OutputInterface $output) : bool {
    if (!$this->serialExecution) { return TRUE; }

    // Find or create setting.
    $setting = $this->getStateSetting(TRUE);

    // Check setting state.
    if ($setting->getVarValueParsed() === self::STATE_IDLE) {
      // Set setting value to "busy"
      $this->updateStateSetting($setting, self::STATE_BUSY);

      return TRUE;
    }

    $output->writeln('<failure>Command is busy!</failure>');
    return FALSE;
  }

  /**
   * Mark command as idle.
   *
   * @return bool
   */
  protected function markAsIdle() : bool {
    if (!$this->serialExecution) { return TRUE; }

    // Find setting.
    $setting = $this->getStateSetting();
    if ($setting) {
      // Set setting value to "idle".
      $this->updateStateSetting($setting, self::STATE_IDLE);

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Add predefined styles.
   *
   * @param OutputInterface $output
   */
  protected function prettifyOutput(OutputInterface $output) : void {
    $style = new OutputFormatterStyle('cyan', NULL, ['bold']);
    $output->getFormatter()->setStyle('note', $style);

    $style = new OutputFormatterStyle('magenta', NULL, ['bold']);
    $output->getFormatter()->setStyle('section', $style);

    $style = new OutputFormatterStyle('yellow', NULL, ['bold']);
    $output->getFormatter()->setStyle('highlight', $style);

    $style = new OutputFormatterStyle('green', NULL, ['bold']);
    $output->getFormatter()->setStyle('success', $style);

    $style = new OutputFormatterStyle('red', NULL, ['bold']);
    $output->getFormatter()->setStyle('failure', $style);
  }

  /**************************************************************************/
  /* EXECUTION                                                              */
  /**************************************************************************/

  abstract protected function executeLogic(InputInterface $input, OutputInterface $output);

  /**
   * (non-PHPdoc)
   * @inheritdoc
   * @see \Symfony\Component\Console\Command\Command::execute()
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->prettifyOutput($output);


    // Mark as busy
    if (!$this->markAsBusy($output)) {
      return NULL;
    }


    // Execution logic
    $result = $this->executeLogic($input, $output);


    // Mark as idle
    if ($result !== FALSE) {
      $this->markAsIdle();
    }

    // Return result code
    return $result;
  }

}
