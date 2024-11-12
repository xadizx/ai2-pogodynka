<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Measurement;

class MeasurementTest extends TestCase
{

    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['0.5', 32.9],
            ['-40', -40],
            ['37', 98.6],
            ['-273.15', -459.67],
            ['20', 68],
            ['30', 86],
            ['50', 122],
        ];
    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);

        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit());
    }
}
