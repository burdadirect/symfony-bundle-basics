<?php

namespace HBM\BasicsBundle\Command;

use HBM\BasicsBundle\Entity\Interfaces\SettingInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Dumper\YamlDumper;
use Symfony\Component\Yaml\Yaml;

class TranslationConvertPhpToYaml extends Command {

  public const NAME = 'hbm:translation:convert:phpToYaml';

  protected function configure() : void {
    $this
      ->setName(self::NAME)
      ->setDescription('Translate php translations to yaml')
      ->addArgument('file-input', InputArgument::REQUIRED, 'The file of the php translations.')
      ->addArgument('file-output', InputArgument::REQUIRED, 'The file to output the yaml translations.')
      ->addOption('grouped', NULL, InputOption::VALUE_NONE, 'Should the translation keys be grouped?')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) : int {
    $fileInput = $input->getArgument('file-input');

    $translations = require_once $fileInput;

    $translationsRegrouped = [];
    foreach ($translations as $key => $value) {
      // Unify value.
      $value = trim($value);

      // Handle id keys.
      if ($input->getOption('grouped') && (strpos($key, ' ') === FALSE)) {
        $keyParts = explode('.', $key);
        $translationsRegrouped = $this->insertIntoArray($translationsRegrouped, $keyParts, $value);
      } else {
        $translationsRegrouped[$key] = $value;
      }
    }

    $yaml = Yaml::dump($translationsRegrouped, 6, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
    $yaml = str_replace(": |\n", ": >-\n", $yaml);

    if ($fileOutput = $input->getArgument('file-output')) {
      file_put_contents($fileOutput, $yaml);
    } else {
      $output->writeln($yaml);
    }

    return 0;
  }

  protected function insertIntoArray(array $array, array $keys, $value) : array {
    if (count($keys) === 0) {
      $array[] = $value;
      return $array;
    }

    if (count($keys) === 1) {
      $key = array_shift($keys);
      $array[$key] = $value;
      return $array;
    }

    $key = array_shift($keys);

    $subarray = $array[$key] ?? [];
    if (!is_array($subarray)) {
      $subarray = [$subarray];
    }

    $array[$key] = $this->insertIntoArray($subarray, $keys, $value);

    return $array;
  }

}
