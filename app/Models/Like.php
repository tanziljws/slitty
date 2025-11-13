<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'user_likes';
    
    public $timestamps = true;
    
    protected $fillable = [
        'user_id',
        'foto_id'
    ];

    public function foto()
    {
        return $this->belongsTo(Foto::class);
    }
}