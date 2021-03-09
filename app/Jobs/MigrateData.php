<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MigrateData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $path;
    protected string $extension;
    protected int $minimumAge;
    protected int $maximumAge;

    public function __construct(string $path, string $extension, int $minimumAge, int $maximumAge)
    {
        $this->path = $path;
        $this->extension = $extension;
        $this->minimumAge = $minimumAge;
        $this->maximumAge = $maximumAge;
    }

    public function handle()
    {
        if ($this->extension === 'json') {
            MigrateDataJson::dispatch($this->path, $this->minimumAge, $this->maximumAge);
        }

        if ($this->extension === 'csv') {
            // MigrateDataCsv::dispatch();
            // Which cleans and seperates the data, then calls the HandleUser job
        }

        if ($this->extension === 'xml') {
            // MigrateDataXml::dispatch();
            // Which cleans and seperates the data, then calls the HandleUser job
        }

        // You could throw an exception here, no valid file given or let the job finish without doing anything
    }
}
