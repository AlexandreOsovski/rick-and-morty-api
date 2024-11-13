<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisodeModel extends Model
{
    use HasFactory;

    protected $table = 'episodes';
    protected $fillable = [
        'name',
        'air_date',
        'episode',
        'characters',
        'url',
        'created',
    ];

    protected $casts = [
        'created' => 'datetime',
        'characters' => 'array'
    ];
}
