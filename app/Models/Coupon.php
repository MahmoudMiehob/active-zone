<?php

namespace App\Models;

use id;
use App\Models\Region;
use App\Models\Minisurvice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['provider_id'];

    public $timestamps = true;
    public function region()
    {
        return $this->belongsTo(Region::class,'region_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }


    public function minisurvice()
    {
        return $this->belongsTo(Minisurvice::class,'minisurvice_id');
    }
}
