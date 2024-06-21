<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationRating extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $guarded = [] ;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
