<?php

namespace HBM\BasicsBundle\Command;

use HBM\BasicsBundle\Traits\ServiceDependencies\ParameterBagDependencyTrait;
use HBM\BasicsBundle\Traits\ServiceDependencies\TwigEnvironmentDependencyTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(self::NAME, 'Dump maintenance page to public folder.')]
class DumpMaintenancePageCommand extends AbstractExtendableCommand
{
    use ParameterBagDependencyTrait;
    use TwigEnvironmentDependencyTrait;

    public const string NAME = 'hbm:dump:maintenance';

    protected function configure(): void
    {
        $this->addOption('template', null, InputOption::VALUE_REQUIRED, 'Use this template.', '@HBMBasics/maintenance.html.twig');
        $this->addOption('filename', null, InputOption::VALUE_REQUIRED, 'Use this filename.', 'maintenance.html');
        $this->addOption('folder', null, InputOption::VALUE_REQUIRED, 'Use this folder as public folder.', '/public/');
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    protected function executeLogic(InputInterface $input, OutputInterface $output): int
    {
        $template = $input->getOption('template');
        $filename = $input->getOption('filename');
        $folder   = $input->getOption('folder');

        $folderPublic = $this->pb->get('kernel.project_dir') . $folder;
        $content      = $this->twig->render($template);
        file_put_contents($folderPublic . $filename, $content);

        return Command::SUCCESS;
    }

}
