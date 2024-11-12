<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:location',
    description: 'Add a short description for your command',
)]
class WeatherLocationCommand extends Command
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherUtil $weatherUtil,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Location ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('id');
        $location = $this->locationRepository->find($locationId);
        $forecasts = $this->weatherUtil->getWeatherForLocation($location);
        foreach ($forecasts as $forecast) {
            $forecastDate = $forecast->getDate();
            $io->section(sprintf('Forecast for %s on %s:', $location->getCity(), $forecast->getDate()->format('Y-m-d')));
            $io->text([
                'Temperature: ' . $forecast->getCelsius() . ' C',
                'Humidity: ' . $forecast->getHumidity() . '%'
            ]);
        }
        return Command::SUCCESS;
    }
}
