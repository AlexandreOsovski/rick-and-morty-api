<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterModel extends Model
{
    use HasFactory;

    protected $table = 'characters';
    protected $fillable = [
        'character_api_id',
        'name',
        'status',
        'species',
        'type',
        'gender',
        'origin_id',
        'location_id',
        'image',
        'episode',
        'url',
        'created',
    ];

    protected $casts = [
        'episode' => 'array',
        'created' => 'datetime',
    ];

    public function origin()
    {
        return $this->belongsTo(LocationModel::class, 'origin_id');
    }

    public function location()
    {
        return $this->belongsTo(LocationModel::class, 'location_id');
    }
}
