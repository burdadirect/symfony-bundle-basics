<?php

namespace HBM\BasicsBundle\Output;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class JournaledConsoleOutput extends ConsoleOutput
{
    private string $journalPath;

    public function __construct(OutputInterface $output, string $journalPath)
    {
        parent::__construct($output->getVerbosity(), $output->isDecorated(), $output->getFormatter());
        $this->setJournalPath($journalPath);
    }

    public function setJournalPath(string $journalPath): void
    {
        $this->journalPath = str_replace(':', '-', $journalPath);
        $this->createJournalDirectory(dirname($this->journalPath));
    }

    public function getJournalPath(): string
    {
        return $this->journalPath;
    }

    private function createJournalDirectory(string $dir): void
    {
        if (!is_dir($dir) && !mkdir($dir) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }

    protected function doWrite(string $message, bool $newline): void
    {
        parent::doWrite($message, $newline);

        $journalMessage = $this->removeAnsiEscapeSequences($message);

        if ($newline) {
            $journalMessage .= PHP_EOL;
        }

        file_put_contents($this->getJournalPath(), $journalMessage, FILE_APPEND);
    }

    protected function removeAnsiEscapeSequences(string $subject): string
    {
        $subject = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', '', $subject);
        $subject = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', '', $subject);

        return preg_replace('/[\x03|\x1a]/', '', $subject);
    }

    public function markAsUsed(): void
    {
        $this->write('');
    }

    public function hasBeenUsed(): bool
    {
        return is_file($this->getJournalPath());
    }
}
