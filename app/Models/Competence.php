<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    use HasFactory;
    protected  $table = 'competences';
    protected $fillable = [
        'namecompetence',
        'description',
        'deletecompetence',
        'created_at',
        'updated_at',
    ];

    public function employees()
    {
        return $this->hasMany(Specialist::class);
    }
    public function specialists()
    {
        return $this->hasMany(Specialist::class);
    }
   
}
