<?php

namespace App\Models;

use App\Models\Region;
use App\Models\Minisurvice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['name','code'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function minisurvices()
    {
        return $this->hasMany(Minisurvice::class);
    }
}
