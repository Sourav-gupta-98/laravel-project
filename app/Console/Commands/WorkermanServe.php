<?php

namespace App\Console\Commands;

use App\WebSockets\WorkermanServer;
use Illuminate\Console\Command;

class WorkermanServe extends Command
{
    protected $signature = 'workerman:serve';
    protected $description = 'Start Workerman WebSocket server';

    public function handle()
    {
        (new WorkermanServer())->start();
    }
}
