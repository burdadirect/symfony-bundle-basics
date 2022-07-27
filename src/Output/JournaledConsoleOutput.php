<?php

namespace HBM\BasicsBundle\Output;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class JournaledConsoleOutput extends ConsoleOutput {

  private ?string $journalPath;

  private ?string $lineFirst = null;

  private ?string $lineLast = null;

  /**
   * JournaledConsoleOutput constructor.
   *
   * @param OutputInterface $output
   * @param string|null $journalPath
   */
  public function __construct(OutputInterface $output, string $journalPath = null) {
    parent::__construct($output->getVerbosity(), $output->isDecorated(), $output->getFormatter());
    $this->journalPath = $journalPath;
  }

  /**
   * Set journalPath.
   *
   * @param string|null $journalPath
   *
   * @return self
   */
  public function setJournalPath(string $journalPath = null): self {
    $this->journalPath = str_replace(':', '-', $journalPath);

    if (!mkdir($concurrentDirectory = dirname($this->journalPath)) && !is_dir($concurrentDirectory)) {
      throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }

    return $this;
  }

  /**
   * Get journalPath.
   *
   * @return string|null
   */
  public function getJournalPath(): ?string {
    return $this->journalPath;
  }

  /**
   * @param string $message
   * @param bool $newline
   *
   * @return void
   */
  protected function doWrite($message, $newline): void {
    parent::doWrite($message, $newline);

    $journalMessage = $this->removeAnsiEscapeSequences($message);
    if ($newline) {
      if ($this->lineFirst === null) {
        $this->lineFirst = $message;
      }
      $this->lineLast = $message;

      $journalMessage .= PHP_EOL;
    }

    file_put_contents($this->getJournalPath(), $journalMessage, FILE_APPEND);
  }

  /**
   * @param $subject
   *
   * @return string|string[]|null
   */
  protected function removeAnsiEscapeSequences($subject): string {
    $subject = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', '', $subject);
    $subject = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', '', $subject);
    $subject = preg_replace('/[\x03|\x1a]/', '', $subject);

    return $subject;
  }

  /**
   * @return bool
   */
  public function cleanUp(): bool {
    if ($this->lineFirst === $this->lineLast) {
      unlink($this->getJournalPath());
      return true;
    }

    return false;
  }

}
