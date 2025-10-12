<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breeding extends Model
{
    use HasFactory;

    protected $fillable = [
        'male_id', 'female_id', 'date_mise_bas', 'taille_portee', 'nb_morts', 'reussite'
    ];

    public function male()
    {
        return $this->belongsTo(\App\Models\Animal::class, 'male_id');
    }

    public function female()
    {
        return $this->belongsTo(\App\Models\Animal::class, 'female_id');
    }
}
