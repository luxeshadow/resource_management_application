<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;
    protected  $table = 'sectors';
    protected $fillable = [
        'namesector',
        'description',
        'deletesector',
        'created_at',
        'updated_at',
    ];

    public function employees()
    {
        return $this->hasMany(Task::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
