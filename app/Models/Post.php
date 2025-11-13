<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Galery;

class Post extends Model
{
    protected $table = 'posts';
    public $timestamps = true;
    
    protected $fillable = [
        'judul', 'kategori_id', 'petugas_id', 'isi', 'status'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    public function galeries()
    {
        return $this->hasMany(Galery::class, 'post_id');
    }
}
