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

    protected string $path;
    protected Carbon $minimalBirthDate;
    protected Carbon $maximalBirthDate;

    public function __construct(string $path, int $minimumAge, int $maximumAge)
    {
        $this->path = $path;
        $this->minimalBirthDate = Carbon::today()->subYears($maximumAge); 
        $this->maximalBirthDate = Carbon::today()->subYears($minimumAge); 
    }

    public function handle()
    {
        // One idea is to handle this logic in it's own class. I was not comfortable enough in doing that to make it work
        
        $reader = new JsonReader();

        $reader->open(base_path($this->path));

        $reader->read();

        $reader->read();

        while ($reader->type() === JsonReader::OBJECT) {
            $data = $reader->value();

            HandleUser::dispatch($data, $this->minimalBirthDate, $this->maximalBirthDate);

            $reader->next();
        }
    
        $reader->close();
    }
}
