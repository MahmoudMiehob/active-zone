<?php

namespace App\Models;

use Filament\Panel;
use App\Models\Role;
use App\Models\Coupon;
use App\Models\Minisurvice;
use App\Models\Reservation;
use App\Models\SearchArchive;
use App\Models\QuestionAnswer;
use App\Models\ApplicationRating;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use App\Http\Controllers\Api\UploadImageTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use UploadImageTrait;


    protected $fillable = [
        'name',
        'email',
        'temp_email',
        'password',
        'birthday',
        'sex',
        'points',
        'image',
        'phone',
        'postal_code',
        'temp_phone',
        'temp_postal_code',
        'otp',
        'otp_expires_at',
        'theme',
        'theme_color',
        'role_id',
        'survice_id'
    ];

    public function generateOtp()
    {
        $this->otp = rand(100000, 999999);
        $this->otp_expires_at = now()->addMinutes(5);
        $this->save();
    }

    public function clearOtp()
    {
        $this->otp = null;
        $this->otp_expires_at = null;
        $this->save();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin' && $this->role_id == 2) {
            return true;
        }

        if ($panel->getId() === 'superadmin' && $this->role_id == 3) {
            return true;
        }

        return false;
    }

    protected $hidden = [
        'password',
        'remember_token',
        'theme',
        'theme_color',
        'role_id',
        'created_at',
        'updated_at',
        'survice_id'
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function searcharchive()
    {
        return $this->hasMany(SearchArchive::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function questionanswer()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    public function applicationrating()
    {
        return $this->hasMany(ApplicationRating::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function minisurvices()
    {
        return $this->belongsToMany(Minisurvice::class, 'minisurvices_users', 'user_id', 'minisurvice_id');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
