<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Chat;
use App\Models\City;
use App\Models\Distric;
use App\Models\Province;
use App\Models\Subdistric;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\MessageSent;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'photo_profile',
        'prov_id',
        'city_id',
        'dis_id',
        'subdis_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function province()
    {
        return $this->belongsTo(Province::class, 'prov_id','prov_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id','city_id');
    }

    public function district()
    {
        return $this->belongsTo(Distric::class, 'dis_id', 'dis_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistric::class, 'subdis_id', 'subdis_id');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

    public function sendNewMessageNotification(array $data): void
    {

        $this->notify(new MessageSent($data));
    }

    public function routeNotificationForOneSignal()
    {
        return ['tags' => ['key' => 'userId', 'relation' => '=', 'value' => (string) ($this->id)]];
    }
}
