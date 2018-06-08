<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tymon\JWTAuth\Commands\JWTGenerateCommand;

class JWTGenerateFix extends JWTGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwtFix:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $key = $this->getRandomKey();

        $path = config_path('jwt.php');

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $this->laravel['config']['jwt.secret'], $key, file_get_contents($path)
            ));
        }

        $this->laravel['config']['jwt.secret'] = $key;

        $this->info("jwt-auth secret [$key] set successfully.");
    }

    protected function getOptions()
    {
        return [];
    }
}
