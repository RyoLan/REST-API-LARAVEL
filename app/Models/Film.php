<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aktor;
use App\Models\Kategori;
use App\Models\Genre;

class Film extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'deskripsi', 'foto', 'url_vidio', 'id_kategori'];

    public function kategori (){
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function genre (){
        return $this->belongsToMany(Genre::class,'genre_film', 'id_film', 'id_genre');
    }

    public function aktor (){
        return $this->belongsToMany(Aktor::class,'aktor_film', 'id_film', 'id_aktor');
    }
}
