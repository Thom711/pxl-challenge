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

    protected array $data;
    protected Carbon $minimalBirthDate;
    protected Carbon $maximalBirthDate;

    public function __construct(array $data, Carbon $minimalBirthDate, Carbon $maximalBirthDate)
    {
        $this->data = $data;
        $this->minimalBirthDate = $minimalBirthDate;
        $this->maximalBirthDate = $maximalBirthDate;
    }

    public function handle()
    { 
        // valideer input

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

        if (!$user->isOfRightAge($this->minimalBirthDate, $this->maximalBirthDate)) {
            return;
        }

        // db transaction

        $user->save();

        $creditcard = $this->data['credit_card'];

        $expirationDateArray = explode('/', $creditcard['expirationDate']);

        $expirationDate = Carbon::createFromDate($expirationDateArray[1], $expirationDateArray[0], 1)->isoFormat('Y-M-D');

        $user->creditcard()->create([
            'type' => $creditcard['type'],
            'number' => $creditcard['number'],
            'name' => $creditcard['name'],
            'expiration_date' => $expirationDate,
        ]);
    }
}
