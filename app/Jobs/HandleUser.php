<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class HandleUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $minDate;
    protected $maxDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, Carbon $minDate, Carbon $maxDate)
    {
        $this->data = $data;
        $this->minDate = $minDate;
        $this->maxDate = $maxDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::make([
            'name' => $this->data['name'],
            'address' => $this->data['address'],
            'checked' => $this->data['checked'],
            'description' => $this->data['description'],
            'interest' => $this->data['interest'],
            'email' => $this->data['email'],
            'account' => $this->data['account'],
        ]);

        $user->setDateOfBirth($this->data['date_of_birth']);

        if ($user->isOfRightAge($this->minDate, $this->maxDate)) {
            $user->save();

            $creditcard = $this->data['credit_card'];

            $expiration_date_array = explode('/', $creditcard['expirationDate']);

            $expiration_date = Carbon::createFromDate($expiration_date_array[1], $expiration_date_array[0], 1)->isoFormat('Y-M-D');

            $user->creditcard()->create([
                'type' => $creditcard['type'],
                'number' => $creditcard['number'],
                'name' => $creditcard['name'],
                'expiration_date' => $expiration_date,
            ]);
        }
    }
}
