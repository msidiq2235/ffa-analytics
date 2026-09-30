<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VectorDirection extends Model
{
protected $fillable = ['match_id', 'player_id', 'analyst_id', 'action_type', 'start_x', 'start_y', 'end_x', 'end_y'];
}