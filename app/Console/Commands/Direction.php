<?php

namespace App\Console\Commands;

use App\Flight;
use App\Route;
use Illuminate\Console\Command;

class Direction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'direction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get or Update Flights data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = Route::all();
        foreach ($routes as $index => $route) {
            (new Flight)->getFlights($route);
        }
    }

}
