<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Measurement;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Service\WeatherUtil;
use Symfony\Component\HttpFoundation\Response;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        WeatherUtil $util,
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        if ($format === 'csv') {

            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }

            $csv = "city,country,date,celsius,fahrenheit\n";
            $csv .= implode(
                "\n",
                array_map(fn(Measurement $f) => sprintf(
                    "%s,%s,%s,%s,%s",
                    $city,
                    $country,
                    $f->getDate()->format('Y-m-d'),
                    $f->getCelsius(),
                    $f->getFahrenheit(),
                ), $measurements)
            );

            return new Response($csv, 200, [
                'Content-Type' => 'text/plain',
            ]);
        }

        if ($twig) {
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }


        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Measurement $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),

        ]);
    }
}
