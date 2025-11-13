<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    // Explicitly set the underlying table name
    protected $table = 'pages';

    // Allow mass assignment for typical columns (safe defaults)
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'published_at',
    ];
}
