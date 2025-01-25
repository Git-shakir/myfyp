<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Firebase\AnimalDataController; // Adjust if necessary

class StartFirebaseListener extends Command
{
    protected $signature = 'firebase:poll';
    protected $description = 'Start polling Firebase for UID logs';

    private $controller;

    public function __construct(AnimalDataController $controller)
    {
        parent::__construct();
        $this->controller = $controller;
    }

    public function handle()
    {
        $this->info('Starting Firebase polling...');
        $this->controller->startListeningForLogs();
    }
}
