<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VectorDirection extends Model
{
    public const SUBTYPE_COLORS = [
        'SHOT' => [
            'ON_TARGET' => '#4ade80',
            'OFF_TARGET' => '#ef4444',
            'BLOCKED' => '#fbbf24',
            'GOAL' => '#3b82f6',
        ],
        'PASS' => [
            'SUCCESS' => '#22c55e',
            'KEY_PASS' => '#eab308',
            'UNSUCCESSFUL' => '#fb7185',
        ],
        'CROSS' => [
            'SUCCESS' => '#a855f7',
            'ASSIST' => '#06b6d4',
            'UNSUCCESSFUL' => '#f97316',
        ],
    ];

    public const ACTION_LABELS = [
        'SHOT' => 'Tembakan',
        'PASS' => 'Umpan',
        'CROSS' => 'Umpan silang',
    ];

    protected $fillable = ['match_id', 'player_id', 'analyst_id', 'action_type', 'subtype', 'start_x', 'start_y', 'end_x', 'end_y', 'input_time', 'match_minute'];

    public function getDisplayColorAttribute(): string
    {
        $action = strtoupper($this->action_type ?? '');
        $subtype = strtoupper($this->subtype ?? '');

        return self::SUBTYPE_COLORS[$action][$subtype]
            ?? (array_values(self::SUBTYPE_COLORS[$action] ?? ['#64748b'])[0]);
    }

    public function getDisplayLabelAttribute(): string
    {
        $action = strtoupper($this->action_type ?? '');
        $label = self::ACTION_LABELS[$action] ?? $action;
        $subtype = strtoupper($this->subtype ?? '');

        return $subtype === '' ? $label : $label . ' · ' . str_replace('_', ' ', $subtype);
    }
}