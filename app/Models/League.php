<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'traditional_chinese', 'simplified_chinese', 'english', 'traditional_chinese_short', 'simplified_chinese_short', 'english_short', 'league7m_id'];
}
