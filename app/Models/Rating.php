<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;


    public $timestamps = true;
    protected $guarded = [] ;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function minisurvice()
    {
        return $this->belongsTo(Minisurvice::class,'minisurvice_id');
    }

}
