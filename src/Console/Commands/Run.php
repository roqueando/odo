<?php

namespace Odo\Console\Commands;

use Odo\Alabojuto;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\{
    InputInterface,
    InputOption
};

class Run extends Command
{
    protected function configure()
    {
        $this->setName('run')
             ->setDescription('Run Odo alabojuto (Supervisor)')
             ->setHelp("This command runs the Odo supervisor to listen messages")
             ->addOption(
                 'port', 
                 'p', 
                 InputOption::VALUE_OPTIONAL, 
                 'Alabojuto (supervisor) port', 
                 8000
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<question>ti nṣàn odo.<comment> The river start to flow </comment></question>");
        (new Alabojuto(port: $input->getOption('port')))->run();
        return 1;
    }
}
