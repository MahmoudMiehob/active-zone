<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['name' , 'value'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
