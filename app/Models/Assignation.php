<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignation extends Model
{
    use HasFactory;
    use HasFactory;
    protected  $table = 'assignations';
    
    protected $fillable = [
        'projet_id',
        'employe_id',
        'date_fin',
           
    ];
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function employe()
    {
        return $this->belongsTo(Employee::class);
    }

}
