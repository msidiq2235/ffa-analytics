<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Statistic extends Model
{
protected $fillable=['match_id','player_id','analyst_id','minute','input_time','shooting_on_target','goal','shots','shooting_off_target','penalty_goal','shot_on_target_alternate','assist','chance_created','successful_passes','successful_crosses','dribble','successful_dribble','unsuccessful_dribble','tackles','fouls_committed','interceptions','dribbled_past'];
}