<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['question','answer','provider_id'];
    public $timestamps = true;

    protected $hidden = ['provider_id'];

    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }

}


