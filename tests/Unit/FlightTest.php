<?php

namespace Tests\Unit;

use App\CheckFlight;
use App\Console\Commands\CheckFlights;
use App\Flight;
use App\Helpers\FormatterHelper;
use App\Helpers\HttpClientHelper;
use Psr\Http\Message\ResponseInterface;
use Tests\TestCase;

class FlightTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetMinimal()
    {
        $testArray = ['2019-01-01' => [
            0 => (object)[
                'price' => 12,
                'booking_token' => 'qwe'
            ],
            1 => (object)[
                'price' => 999,
                'booking_token' => 'asd'
            ]]
        ];

        $response = (new Flight())->getCheapestFlights($testArray);
        $value = $response['2019-01-01']['price'];
        $this->assertTrue($value === 12);
    }


    public function testFlightsFromAlaToMow()
    {
        $from = 'ALA';
        $to = 'MOW';

        $response = (new HttpClientHelper(1))->getFlights($from,$to);
        $body = FormatterHelper::formatBody($response);
        
        $cityFrom = ($body->data)[0]->cityFrom;
        $cityTo = ($body->data)[0]->cityTo;
        
        $this->assertTrue($cityFrom === 'Almaty');
        $this->assertTrue($cityTo === 'Moscow');
    }

    public function testCreateCheckFLight()
    {
        $data = [
            'route_id' => 99,
            'booking_token' => 'qwertyuio',
        ];

        $check = new HttpClientHelper($data['route_id']);
        $checkFlight = $check->addFlightToCheck($data['booking_token']);
        $this->assertTrue($checkFlight);
        CheckFlight::where(['route_id' => 99])->each(function ($flight){
            $flight->delete();
        });

    }
}
