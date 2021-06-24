<?php

namespace App\Commands\Topics;

use ErrorException;
use Illuminate\Console\Scheduling\Schedule;
use Laracast\Laracast;
use LaravelZero\Framework\Commands\Command;

class Get extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'topics-get {slug : Slug of topic}';

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
        try{
            $series = Laracast::topics()->getDetails($this->argument('slug'))->seriesWithCollection()->map(function ($row){
                return collect($row)->only([
                    'title', 'slug',
                    'episodeCount', 'runTime'
                ])->toArray();
            });
            $this->table([
                'Title', 'Slug',
                'Episode Count', 'Run Time'
            ], $series);
        }
        catch (ErrorException $exception){
            $this->error("Topics with slug (". $this->argument('slug') .") doesn't exist!");
        }
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
