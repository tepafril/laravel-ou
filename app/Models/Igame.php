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
        'hn',
        'an',
        'oo',
        'uo',
        'li'
    ];

    public $timestamps = true;
} 