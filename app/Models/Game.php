<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Team;
use App\Models\League;
use App\Models\Game7m;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'gd',
        'gt',
        'cid',
        'tid',
        'taid',
        'thid',
        'mb',
        'hr',
        'ar',
        'sg',
        's',
        'hc',
        'ns',
        'tdid',
        'ml',
        'bir',
        'htb',
        'evid',
        'mid',
        'himg',
        'aimg',
        'ty',
        'cimg',
        'cimg2',
        'tseq',
        'dorder',
        'ctid',
        'ls_status',
        'display_lineup',
        'ft_show',
        'oo',
        'uo',
        'li_decimal',
        'li',
        'sp',
        'status7m',
        'ft_home_score',
        'ft_away_score',
        'ht_home_score',
        'ht_away_score',
    ];

    public function away_team(): BelongsTo {
        return $this->belongsTo(Team::class, 'taid');
    }

    public function home_team(): BelongsTo {
        return $this->belongsTo(Team::class, 'thid');
    }

    public function league(): BelongsTo {
        return $this->belongsTo(League::class, 'tid');
    }

    public function game7m(): BelongsTo {
        return $this->belongsTo(Game7m::class, 'game7m_id');
    }
}
