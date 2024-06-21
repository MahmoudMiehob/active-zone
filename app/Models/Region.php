<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\Country;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;

    protected $guarded = [] ;
    public $timestamps = true;

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }
    public function minisurvice()
    {
        return $this->hasMany(Minisurvice::class);
    }



}
