<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchArchive extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $guarded = [] ;

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
