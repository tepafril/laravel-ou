<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Team7m;
use App\Models\League7m;

class Game7m extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'gd', 'gt', 'f1', 'f4', 'f5', 'f6', 'f7', 'f8', 'taid', 'thid', 'f11', 'f12', 'f13', 'f14',
        'f15', 'tid', 'f17', 'f18', 'f19', 'f20', 'mid', 'isrun',
        'ah1_0',
        'ah1_1',
        'ah1_2',
        'ah1_3',
        'ah1_4',
        'ah2_0',
        'ah2_1',
        'ah2_2',
        'ah2_3',
        'ah2_4',
        'hda1_0',
        'hda1_1',
        'hda1_2',
        'hda1_3',
        'hda2_0',
        'hda2_1',
        'hda2_2',
        'hda2_3',
        'ou1_0',
        'ou1_1',
        'ou1_2',
        'ou1_3',
        'ou2_0',
        'ou2_1',
        'ou2_2',
        'ou2_3',
        'status',
        'ft_home_score',
        'ft_away_score',
        'ht_home_score',
        'ht_away_score'
    ];


    public function away_team(): BelongsTo {
        return $this->belongsTo(Team7m::class, 'taid');
    }

    public function home_team(): BelongsTo {
        return $this->belongsTo(Team7m::class, 'thid');
    }

    public function league(): BelongsTo {
        return $this->belongsTo(League7m::class, 'tid');
    }

    public function game(): BelongsTo {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
