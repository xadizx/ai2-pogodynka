<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{countryCode?}', name: 'app_weather', requirements: ['city' => '[a-zA-Z\s]+', 'countryCode' => '[a-zA-Z]{2}'])]
    public function city(string $city, ?string $countryCode, LocationRepository $locationRepository, MeasurementRepository $repository): Response
    {
        $location = $locationRepository->findByCityAndCountryCode($city, $countryCode);
        $measurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
