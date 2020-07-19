<?php

namespace App\Command;

use JMS\Serializer\Serializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportAsteroids
 */
class ImportAsteroids extends Command
{
    protected static $defaultName = 'app:import-asteroids';

    public function __construct()
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn't work in this case
        // because configure() needs the properties set in this constructor

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import all asteroids.')
            ->setHelp('This command will import all asteroids from Neo - Browse')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Import has been started.',
            '========================',
        ]);

        return Command::SUCCESS;
    }
}
