<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    use HasFactory;
    protected  $table = 'specialists';
    
    protected $fillable = [
        'employe_id',
        'competence_id',
        'created_at',
        'updated_at'   
    ];
    public function competence()
    {
        return $this->belongsTo(Competence::class);
    }

    public function employe()
    {
        return $this->belongsTo(Employee::class);
    }
}
