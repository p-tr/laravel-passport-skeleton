<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install/Reset Application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // create application key
        $this->call('key:generate');

        // install passport keys
        $this->call('passport:keys');

        // run migrations
        $this->call('migrate:fresh', [
            '--seed' => true,
        ]);

        // print informations
    }
}
