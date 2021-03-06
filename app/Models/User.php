<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function creditcard()
    {
        return $this->hasOne(Creditcard::class);
    }

    public function setDateOfBirth($date) // Unfortunatly I wrote this in PHP 7.4 :(. In 8 I could have done string|null
    {
        $date_of_birth = null;

        if ($date !== null) {
            $carbon = new Carbon(str_replace('/', '-', $date));

            $date_of_birth = $carbon->isoFormat('Y-M-D');
        }

        return $date_of_birth;
    }

    public function isOfRightAge(Carbon $minDate, Carbon $maxDate)
    {
        $date_of_birth = $this->attributes['date_of_birth'];

        return (($date_of_birth > $minDate && $date_of_birth < $maxDate) || $date_of_birth === null);
    }
   
}
