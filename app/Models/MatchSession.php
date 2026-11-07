<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchSession extends Model
{
    protected $table = 'matches';

    protected $fillable = ['title', 'match_date', 'venue', 'opponent', 'formation', 'status', 'created_by'];

    // Tambahkan casts ini agar jam dan menit tidak berubah menjadi 00:00
    protected $casts = [
        'match_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'match_players', 'match_id', 'player_id')->withPivot('position')->withTimestamps();
    }
}