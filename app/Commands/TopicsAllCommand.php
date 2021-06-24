<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use Laracast\Cores\Services\Laracast\Authentication;
use Laracast\Laracast;
use Laracast\LaracastConfig;
use LaravelZero\Framework\Commands\Command;

class TopicsAllCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'topics-all';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topics = collect(Laracast::topics()->getAll())->map(function ($row){
            return collect($row)->forget(['path', 'thumbnail', 'series_count', 'theme'])->all();
        });
        $this->table([
            'Name', 'Episode Count', 'Slug'
        ], $topics->all());
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
