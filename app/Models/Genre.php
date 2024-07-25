<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Film;

class Genre extends Model
{
    use HasFactory;

    public function film(){
        return $this->belongsToMany(Film::class , 'genre_film','id_genre','id_film');
    }

}
