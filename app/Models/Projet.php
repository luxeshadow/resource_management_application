<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;
    protected  $table = 'projets';
    
    protected $fillable = [
        'nomprojet',
        'typeprojet',
        'description',
        'nomclient',
        'email',
        'telephone',
        'status',
        
        
    ];
    public function employees()
    {
        return $this->hasMany(Assignation::class);
    }
    public function assignations()
    {
        return $this->hasMany(Assignation::class);
    }

}
