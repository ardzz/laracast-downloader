<?php

namespace App\Commands\Topics;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laracast\Cores\Http\Download;
use Laracast\Cores\Parser\EpisodesParser;
use Laracast\Cores\Services\Laracast\Authentication;
use Laracast\Cores\Services\Vimeo\Vimeo;
use Laracast\Laracast;
use LaravelZero\Framework\Commands\Command;

class RecursiveDownload extends Command
{
    use Download;
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'topics-download';

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
        $topics = Laracast::topics()->getAll();

        foreach ($topics as $topic){
            Storage::makeDirectory($topic['name']);
            $this->line('Topics name   : ' . $topic['name']);
            $this->line('Episode count : ' . $topic['episode_count']);
            $this->line('Series count  : ' . $topic['series_count']);
            $serieses = Laracast::topics()->getDetails($topic['slug'])->series();
            $series_count = 1;
            $this->warn('---------------------------------------');

            foreach ($serieses as $series){
                $this->comment('['.$series_count.'/'.count($serieses).'] ' . $series['title']);
                $this->line('Episode count    : ' . $series['episodeCount']);
                $this->line('Difficulty level : ' . $series['difficultyLevel']);
                $this->line('Time             : ' . $series['runTime']);
                $this->warn('---------------------------------------');
                Storage::makeDirectory($topic['name'] . '/' . $series['title']);
                Authentication::make()->login()->isLoginSuccess();
                $episodes = Laracast::series()->getDetails($series['slug'])->episodes();
                $pathDownload = env('LARACAST_PATH_DOWNLOAD') . $topic['name'] . '/' . $series['title'] . '/';

                $index_episode = 1;
                foreach ($episodes as $episode) {
                    $parsedEpisode = Laracast::episodes()->getDetails($series['slug'], $index_episode);
                    $this->info($parsedEpisode->metaData()->title());
                    $link = $parsedEpisode->getDirectLink();
                    if ($link){
                        $filename = $parsedEpisode->metaData()->title(). '.mp4';
                        $path = $pathDownload . $filename;
                        if (!Storage::exists($path)){
                            $this->info('Duration : ' . $parsedEpisode->metaData()->duration());
                            $this->warn('Downloading episode ...');
                            try {
                                $this->startDownload($link, $path);
                            }
                            catch (\Exception|\Throwable $exception){
                                $this->error('download failed, reason : ' . $exception->getMessage());
                            }
                        }else{
                            $this->error('skipped');
                        }
                    }else{
                        $this->error('skipped');
                    }
                    $this->newLine(2);
                    $index_episode++;
                }

                $this->warn('---------------------------------------');
                $series_count++;
            }

            $this->newLine(2);
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
