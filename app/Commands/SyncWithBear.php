<?php

namespace App\Commands;

use App\Services\StubService;
use BearSync\BearNote;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class SyncWithBear extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'bear:sync';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates a new post';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $noteTitle = $this->ask('What´s the title of the note?');
        $note = BearNote::whereTitle($noteTitle)->first();

        if ($note) {
            $this->info('Found note...');

            $stub = new StubService(
                base_path('post.stub'),
                getcwd() . '/source/_posts/' . Str::slug($note->title, '-') . '.md'
            );

            $stub->render([
                ':POST_TITLE:' => $note->title,
                ':POST_DATE:' => now()->format('Y-m-d'),
                ':POST_CONTENT:' => $note->content,
            ]);

            $this->info('Created post on jigsaw!');
        } else {
            $this->error('Could not create post.');
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
