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
        try {
            foreach ($routes as $index => $route) {
                (new Flight)->getFlights($route);
            }
        } catch (\Exception $exception) {
            $this->error('Something gone wrong');
            $this->error($exception->getMessage());
            return 2;
        }
        $this->info('Flights have been updated');
        return 0;
    }

}
