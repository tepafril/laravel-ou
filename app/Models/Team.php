<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Team7m;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'traditional_chinese', 'simplified_chinese', 'english', 'team7m_id'];

    public function team7m(): BelongsTo {
        return $this->belongsTo(Team7m::class, 'team7m_id');
    }
}
