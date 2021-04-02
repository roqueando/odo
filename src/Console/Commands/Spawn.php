<?php

namespace Odo\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\{
    InputArgument,
    InputInterface,
    InputOption
};

class Spawn extends Command
{
    protected function configure()
    {
        $this->setName('spawn')
             ->setDescription('Spawn a service')
             ->setHelp("This command runs the Odo supervisor to listen messages")
             ->addArgument('service', InputArgument::REQUIRED, 'Service class to be spawned')
             ->addOption('port', 'p', InputOption::VALUE_REQUIRED, 'Service port')
             ->addOption('type', 't', InputOption::VALUE_REQUIRED, 'Service type [tcp | udp | http]', 'tcp');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<bg=yellow;fg=black>Spawning a service</>");
        $serviceName = str_replace('_', '\\', ucfirst($input->getArgument('service')));
        if(class_exists($serviceName)) {
            (new $serviceName(
                $input->getOption('port'), 
                $input->getOption('type')
            ))->run();
            return 1;
        } else {
            $output->writeln("<error>Unable to find that service</error>");
            return 0;
        }
    }
}
