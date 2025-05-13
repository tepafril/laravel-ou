<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Igame extends Model
{
    use HasFactory;
    
    protected $table = 'igames';
    
    protected $fillable = [
        'id',
        'gt',
        'f20',
        'f20a',
        'f20b',
        'st',
        'fths',
        'ftas',
        'ln',
        'lns',
        'hn',
        'an',
        'oo',
        'uo',
        'li',
        'is_wn',
        'is_ov',
        'handi'
    ];

    public $timestamps = true;
} 