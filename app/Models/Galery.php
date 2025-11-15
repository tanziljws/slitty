<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Kategori;
use App\Models\Foto;

class Galery extends Model
{
    protected $table = 'galery';
    public $timestamps = false;
    
    protected $fillable = [
        'post_id', 'position', 'status'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function fotos()
    {
        return $this->hasMany(Foto::class, 'galery_id');
    }
    
    // Helper untuk mengakses kategori melalui post
    public function kategori()
    {
        return $this->hasOneThrough(Kategori::class, Post::class, 'id', 'id', 'post_id', 'kategori_id');
    }
}
