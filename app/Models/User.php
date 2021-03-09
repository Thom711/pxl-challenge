<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'address',
        'checked',
        'description',
        'interest',
        'date_of_birth',
        'email',
        'account',
        'creditcard_id',
    ];

    protected $hidden = [];

    protected $casts = [];

    public function creditcard(): HasOne
    {
        return $this->hasOne(Creditcard::class);
    }

    public function setDateOfBirth(?string $date) 
    {
        $date_of_birth = null;

        if ($date !== null) {
            $carbon = new Carbon(str_replace('/', '-', $date));

            $date_of_birth = $carbon->isoFormat('Y-M-D');
        }

        $this->date_of_birth = $date_of_birth;
    }

    public function isOfRightAge(Carbon $minDate, Carbon $maxDate): bool
    {
        $date_of_birth = $this->attributes['date_of_birth'];

        return (($date_of_birth > $minDate && $date_of_birth < $maxDate) || $date_of_birth === null);
    } 

    public function storeCreditcard(array $creditcard)
    {
        $expirationDateArray = explode('/', $creditcard['expirationDate']);

        $expirationDate = Carbon::createFromDate($expirationDateArray[1], $expirationDateArray[0], 1)->isoFormat('Y-M-D');

        $this->creditcard()->create([
        'type' => $creditcard['type'],
        'number' => $creditcard['number'],
        'name' => $creditcard['name'],
        'expiration_date' => $expirationDate,
        ]);
    }
}