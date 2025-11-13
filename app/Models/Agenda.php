<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agenda';

    protected $fillable = [
        'title',
        'description',
        'date_label',
        'time',
        'event_date',
        'status',
        'order'
    ];

    protected $casts = [
        'event_date' => 'date',
        'order' => 'integer',
    ];
}
