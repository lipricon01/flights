<?php

use Illuminate\Database\Seeder;

class DirectionsSeeder extends Seeder
{
    /**
     * Generate init values.
     * use once pls)
     *
     * @return void
     */
    public function run()
    {

        $directions = [
            'Almaty' => 'ALA',
            'Moscow' => 'MOW',
            'Astana' => 'TSE',
            'Saint Petersburg' => 'LED',
            'Shymkent' => 'CIT'
        ];

        $routes = [
            ['from' => 'ALA', 'to' => 'TSE'],
            ['from' => 'TSE', 'to' => 'ALA'],
            ['from' => 'ALA', 'to' => 'MOW'],
            ['from' => 'MOW', 'to' => 'ALA'],
            ['from' => 'ALA', 'to' => 'CIT'],
            ['from' => 'CIT', 'to' => 'ALA'],
            ['from' => 'MOW', 'to' => 'TSE'],
            ['from' => 'TSE', 'to' => 'MOW'],
            ['from' => 'TSE', 'to' => 'LED'],
            ['from' => 'LED', 'to' => 'TSE'],
        ];

        foreach ($directions as $city => $code) {
            $model = new \App\Direction();
            $model->title = $city;
            $model->code = $code;
            $model->save();
        }

        foreach ($routes as $index => $route) {
            $model = new \App\Route();
            $model->fly_from = (\App\Direction::where(['code' => $route['from']])->first())->id;
            $model->fly_to = (\App\Direction::where(['code' => $route['to']])->first())->id;
            $model->save();
        }

    }
}
