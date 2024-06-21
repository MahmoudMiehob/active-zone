<?php

namespace App\Models;

use App\Models\Survice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subsurvice extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'imagepath' => 'array',
    ];

    public function survice()
    {
        return $this->belongsTo(Survice::class,'survice_id');
    }

    public function minisurvice()
    {
        return $this->hasMany(Minisurvice::class);
    }
}
