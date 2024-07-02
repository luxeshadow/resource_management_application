<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typeprojet extends Model
{
    use HasFactory;
    protected $fillable = [
        'nametypeprojet',
        'description',
        'deletetypeprojet',
        'created_at',
        'updated_at',
    ];
}
