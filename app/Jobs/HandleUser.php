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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HandleUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    protected Carbon $minimalBirthDate;
    protected Carbon $maximalBirthDate;
    protected User $user;

    public function __construct(array $data, Carbon $minimalBirthDate, Carbon $maximalBirthDate)
    {
        $this->data = $data;
        $this->minimalBirthDate = $minimalBirthDate;
        $this->maximalBirthDate = $maximalBirthDate;
    }

    public function handle()
    { 
        $userValidator = Validator::make($this->data, [
            'name' => 'required|string',
            'address' => 'required|string',
            'checked' => 'required|boolean',
            'description' => 'required|string',
            'interest' => 'string|nullable',
            'email' => 'required|email',
            'account' => 'required|numeric',
            'date_of_birth' => 'string|nullable',
            'credit_card' => 'required|array',
        ]);

        $creditcardValidator = Validator::make($this->data['credit_card'], [
            'type' => 'required|string',
            'number' => 'required|numeric',
            'name' => 'required|string',
            'expirationDate' => 'required|string'
        ]);

        if ($userValidator->fails() || $creditcardValidator->fails()) {
            return;
        }

        $this->user = User::make([
            'name' => $this->data['name'],
            'address' => $this->data['address'],
            'checked' => $this->data['checked'],
            'description' => $this->data['description'],
            'interest' => $this->data['interest'],
            'email' => $this->data['email'],
            'account' => $this->data['account'],
        ]);

        $this->user->setDateOfBirth($this->data['date_of_birth']);

        if (!$this->user->isOfRightAge($this->minimalBirthDate, $this->maximalBirthDate)) {
            return;
        }

        DB::transaction(function () {
            $this->user->save();

            $creditcard = $this->data['credit_card'];

            $expirationDateArray = explode('/', $creditcard['expirationDate']);

            $expirationDate = Carbon::createFromDate($expirationDateArray[1], $expirationDateArray[0], 1)->isoFormat('Y-M-D');

            $this->user->creditcard()->create([
            'type' => $creditcard['type'],
            'number' => $creditcard['number'],
            'name' => $creditcard['name'],
            'expiration_date' => $expirationDate,
            ]);
        });
    }
}
