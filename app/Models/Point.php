<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $guarded = [];

    public function minisurvice()
    {
        return $this->belongsTo(Minisurvice::class,'minisurvice_id');
    }
}
