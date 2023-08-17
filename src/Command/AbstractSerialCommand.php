<?php

namespace HBM\BasicsBundle\Command;

use HBM\BasicsBundle\Entity\Interfaces\SettingInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractSerialCommand extends Command {

  public const STATE_IDLE = 'idle';
  public const STATE_INTERRUPTED = 'interrupted';

  /****************************************************************************/
  /* ABSTRACT FUNCTIONS                                                       */
  /****************************************************************************/

  /**
   * @return bool
   */
  abstract protected function isSerial(): bool;

  /**
   * @param bool $create
   *
   * @return SettingInterface|null
   */
  abstract protected function getStateSetting(bool $create = FALSE) : ?SettingInterface;

  /**
   * @param SettingInterface $setting
   * @param string $state
   *
   * @return void
   */
  abstract protected function updateStateSetting(SettingInterface $setting, string $state) : void;

  /****************************************************************************/
  /* HELPER                                                                   */
  /****************************************************************************/

  /**
   * Mark command as busy.
   *
   * @param OutputInterface $output
   *
   * @return bool
   */
  protected function markAsBusy(OutputInterface $output) : bool {
    if (!$this->isSerial()) { return TRUE; }

    // Find or create setting.
    $setting = $this->getStateSetting(TRUE);

    // Check setting state.
    if ($setting && ($setting->getVarValueParsed() === self::STATE_IDLE)) {
      // Set setting value to "busy"
      $this->updateStateSetting($setting, date('Y-m-d H:i:s'));

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
    if (!$this->isSerial()) { return TRUE; }

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
    // error, info, comment, questions are already defined by symfony.

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

  /**
   * @param string $message
   *
   * @return string
   */
  protected function htmlifyOutput(string $message): string {
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

  /**************************************************************************/
  /* EXECUTION                                                              */
  /**************************************************************************/

  abstract protected function executeLogic(InputInterface $input, OutputInterface $output) : int;

  /**
   * (non-PHPdoc)
   * @inheritdoc
   * @see \Symfony\Component\Console\Command\Command::execute()
   */
  protected function execute(InputInterface $input, OutputInterface $output): int {
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
