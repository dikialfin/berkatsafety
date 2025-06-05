<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HitLambda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lambda:hit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To keep lambda warm.';

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
     * @return int
     */
    public function handle()
    {
        Log::info("initiate hit lambda prod");
        $response = Http::get('https://n47xec5kukaax54bihdh4bc5pq0zgoso.lambda-url.ap-southeast-1.on.aws');

        if ($response->successful()) {
            Log::info("success hit lambda prod");
            $this->info('Endpoint hit successfully.');
        } else {
            Log::info("error hit lambda prod");
            $this->error('Failed to hit the endpoint.');
        }

        Log::info("initiate hit lambda dev");
        $response = Http::get('https://yfvrjwnbvzgjsmfgtzwf7bgmqi0fvbsc.lambda-url.ap-southeast-1.on.aws');

        if ($response->successful()) {
            Log::info("success hit lambda dev");
            $this->info('Endpoint hit successfully.');
        } else {
            Log::info("error hit lambda dev");
            $this->error('Failed to hit the endpoint.');
        }

        return 0;
    }
}
