<?php namespace Magestead\Command;

use Magestead\Helper\Config;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PhpspecCommand extends Command
{
    protected $_projectPath;

    protected function configure()
    {
        $this->_projectPath = getcwd();

        $this->setName("phpspec");
        $this->setDescription("Run PHPSpec against your project");
        $this->addArgument('option', InputArgument::OPTIONAL, 'Add options to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Running PHPSpec</info>');
        $option = $input->getArgument('option');


        $command = $this->getCommand(new Config($output), $option);
        if ($command) {
            $passedCommand = "vagrant ssh -c '". $command ."'";
            return new ProcessCommand($passedCommand, $this->_projectPath, $output);
        }

        return $output->writeln('<error>Command not available for this application</error>');
    }

    protected function getCommand(Config $config, $option)
    {
        $type = $config->type;
        switch ($type) {
            case 'magento':
                return "cd /var/www;bin/phpspec $option";
                break;
        }

        return false;
    }

}
