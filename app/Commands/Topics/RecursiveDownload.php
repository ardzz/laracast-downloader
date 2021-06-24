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
        $quality = $this->choice("What quality episode do you want?", [
            '1080p', '540p',
            '720p', '360p',
            '240p'
        ], '540p');
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
                Authentication::make()->login();
                $episodes = Laracast::series()->getDetails($series['slug'])->episodes();
                $pathDownload = env('LARACAST_PATH_DOWNLOAD') . $topic['name'] . '/' . $series['title'] . '/';

                foreach ($episodes as $episode){
                    $parsedEpisode = EpisodesParser::direct($episode);
                    $this->info($parsedEpisode->metaData()->title());
                    $videoCollection = collect(Vimeo::make($parsedEpisode->metaData()->vimeoId())->progressive()->videos());
                    $video = $videoCollection->where('quality', '=', $quality);
                    if ($video){
                        $video_name_with_quality = $pathDownload . "[{$quality}]" . $parsedEpisode->metaData()->title(). '.mp4';
                        $video_name = $pathDownload .  $parsedEpisode->metaData()->title(). '.mp4';
                        if (!Storage::exists($topic['name'] . '/' . $series['title']."/[{$quality}]" . $parsedEpisode->metaData()->title(). '.mp4') &&
                            !Storage::exists($topic['name'] . '/' . $series['title']. '/'.$parsedEpisode->metaData()->title().'.mp4')){
                            $this->info('Duration :' . $parsedEpisode->metaData()->duration());
                            $this->warn('Downloading episode ...');
                            //print_r($video->flatten()->toArray());
                            $this->startDownload($video->flatten()->toArray()[4], $video_name);
                            //print_r($pathDownload . $parsedEpisode->metaData()->title(). '.mp4');
                        }else{
                            $this->error('skipped');
                        }
                    }else{
                        $this->error('Quality ('.$quality.') not found!');
                    }
                    $this->newLine(2);
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
