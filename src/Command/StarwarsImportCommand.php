<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Swapi;


class StarwarsImportCommand extends Command
{
	protected static $defaultName = 'starwars:import';
	protected static $defaultDescription = 'Retrieves the custom data from the Star Wars API';

	private $swapi;

	public function __construct(Swapi $swapi)
	{
		$this->swapi = $swapi;

		parent::__construct();
	}

    protected function configure(): void
    {
        $this
			->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->swapi->getSwapiData();

		if($result['status'] == 'OK') {
			$io->success('Data from SWAPI successfully exported!');
		} else {
			$io->error('Something failed when retrieving the data from SWAPI');
		}

        return Command::SUCCESS;
    }
}
