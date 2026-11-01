<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id', 'name', 'popular_name', 'birth_place_date', 'nik', 
        'jersey_number', 'age_group', 'gender', 'country', 'dominant_foot', 
        'height_cm', 'weight_kg', 'address', 'province', 'phone', 'email', 
        'status', 'position', 'second_club'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}