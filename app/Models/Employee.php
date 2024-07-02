<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected  $table = 'employees';

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'deletemployee',
        'profile',
        'disponibilite'
    ];


    public function projets()
    {
        return $this->hasMany(Assignation::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'employe_id');
    }

    public function specialists()
    {
        return $this->hasMany(Specialist::class, 'employe_id');
    }
    public function sectors()
    {
        return $this->belongsToMany(Sector::class, 'tasks', 'employe_id', 'sector_id');
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'specialists', 'employe_id', 'competence_id');
    }

    public function assignations()
    {
        return $this->hasMany(Assignation::class, 'employe_id');
    }
 
}
