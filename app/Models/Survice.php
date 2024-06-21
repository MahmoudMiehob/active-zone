<?php

namespace App\Models;

use App\Models\Subsurvice;
use App\Models\Minisurvice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survice extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['name_ar','name_en','imagepath'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function subsurvice()
    {
        return $this->hasMany(Subsurvice::class);
    }
    public function minisurvice()
    {
        return $this->hasMany(Minisurvice::class);
    }

}
