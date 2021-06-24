<?php

namespace App\Commands\Topics;

use Illuminate\Console\Scheduling\Schedule;
use Laracast\Laracast;
use LaravelZero\Framework\Commands\Command;

class AllTopics extends Command
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
