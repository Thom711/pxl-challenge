<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use pcrov\JsonReader\JsonReader;

class MigrateDataJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    protected $minDate;
    protected $maxDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $path, int $minAge, int $maxAge)
    {
        $this->path = $path;
        $this->minDate = Carbon::today()->subYears($maxAge);
        $this->maxDate = Carbon::today()->subYears($minAge);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reader = new JsonReader();

        $reader->open(base_path($this->path));

        $reader->read();

        $reader->read();

        while ($reader->type() === JsonReader::OBJECT) {
            $data = $reader->value();

            HandleUser::dispatch($data, $this->minDate, $this->maxDate);

            $reader->next();
        }
    
        $reader->close();
    }
}
