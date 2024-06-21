<?php

namespace App\Models;

use App\Models\User;
use App\Models\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $hidden = ['provider_id'];


    protected $guarded = [] ;
    public $timestamps = true;

    public function minisurvice()
    {
        return $this->belongsTo(Minisurvice::class,'minisurvice_id');
    }
    public function region()
    {
        return $this->belongsTo(Region::class,'region_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }
}
