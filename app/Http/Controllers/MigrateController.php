<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use pcrov\JsonReader\JsonReader;
use Carbon\Carbon;

class MigrateController extends Controller
{
    protected $count = 0;

    public function store()
    {
        $path = 'resources/opdracht/challenge.json';
        $minAge = 18;
        $maxAge = 65;

        $minDate = Carbon::today()->subYears($maxAge);
        $maxDate = Carbon::today()->subYears($minAge);

        $reader = new JsonReader();

        $reader->open(base_path($path));

        $reader->read();
        $reader->read();

        while ($reader->type() === JsonReader::OBJECT) {
            $data = $reader->value();

            $this->handle($data, $minDate, $maxDate);

            $reader->next();
        }
    
        $reader->close();

    }

    public function handle(array $data, Carbon $minDate, Carbon $maxDate)
    {
        $user = User::make([
            'name' => $data['name'],
            'address' => $data['address'],
            'checked' => $data['checked'],
            'description' => $data['description'],
            'interest' => $data['interest'],
            'email' => $data['email'],
            'account' => $data['account'],
        ]);

        $user->date_of_birth = $user->setDateOfBirth($data['date_of_birth']);

        if ($user->isOfRightAge($minDate, $maxDate)) {
            $user->save();

            $creditcard = $data['credit_card'];

            $expiration_date_array = explode('/', $creditcard['expirationDate']);

            $expiration_date = Carbon::createFromDate($expiration_date_array[1], $expiration_date_array[0], 1)->isoFormat('Y-M-D');

            $user->creditcard()->create([
                'type' => $creditcard['type'],
                'number' => $creditcard['number'],
                'name' => $creditcard['name'],
                'expiration_date' => $expiration_date,
            ]);
        }

        $this->count = $this->count + 1;

        if($this->count >= 100) {
            dd($user);
        }
    }
}
