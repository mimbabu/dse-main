<?php

namespace App\Console\Commands;


use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\LatestSharePrice;

class StoreLatestSharePriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:lsp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store Latest Share Price Command';

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
        error_log('hello');
        return 0;
    }
}