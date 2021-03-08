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

    protected $path;
    protected $extension;
    protected $minAge;
    protected $maxAge;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $path, string $extension, int $minAge, int $maxAge)
    {
        $this->path = $path;
        $this->extension = $extension;
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->extension === 'json') {
            MigrateDataJson::dispatch($this->path, $this->minAge, $this->maxAge);
        }

        if ($this->extension === 'csv') {
            // MigrateDataCsv::dispatch();
            // Which cleans and seperates the data, then simply calls the HandleUser job
        }

        if ($this->extension === 'xml') {
            // MigrateDataXml::dispatch();
            // Which cleans and seperates the data, then simply calls the HandleUser job
        }

        // You could put an error message here, 'No valid file given.'
    }
}
