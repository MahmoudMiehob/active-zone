<?php

namespace App\Models;

use App\Models\User;
use App\Models\point;
use App\Models\Coupon;
use App\Models\Survice;
use App\Models\Subsurvice;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Minisurvice extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $guarded = [];

    protected $casts = [
        'imagepath' => 'array',
        'location' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function survice()
    {
        return $this->belongsTo(Survice::class,'survice_id');
    }

    public function subsurvice()
    {
        return $this->belongsTo(Subsurvice::class,'subsurvice_id');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'subsurvices_users', 'subsurvice_id', 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id');
    }

    public function points()
    {
        return $this->hasMany(point::class);
    }

}
