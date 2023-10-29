<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sr-seed {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = $this->argument('filename');
        $prefixFn = "tns" . date('Y_m_d_His_');
        $this->call('make:seed', ['name' => $prefixFn . $filename]);
        return Command::SUCCESS;
    }
}
