<?php

namespace HBM\BasicsBundle\Command\Traits;

use HBM\BasicsBundle\Command\AbstractExtendableCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

trait StyledCommandTrait
{
    public function styleOutput(AbstractExtendableCommand $command, ?int &$exitCode): bool
    {
        // error, info, comment, questions are already defined by symfony.

        $output = $command->getExtendedOutput();

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

        return true;
    }

    public function htmlifyOutput(string $message): string
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

}
