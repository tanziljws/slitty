<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Galery;

class Foto extends Model
{
    protected $table = 'foto';
    public $timestamps = true;
    
    protected $fillable = [
        'galery_id', 'file', 'likes'
    ];

    public function galery()
    {
        return $this->belongsTo(Galery::class, 'galery_id');
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}