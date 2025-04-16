<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class FirstRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:first-run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = text('Qual é o seu nome?');
        $this->info('Olá '.$name);

    }
}
