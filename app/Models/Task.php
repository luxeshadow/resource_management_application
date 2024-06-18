<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected  $table = 'tasks';
    
    protected $fillable = [
        'sector_id',
        'employe_id',
           
    ];
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function employe()
    {
        return $this->belongsTo(Employee::class);
    }

  
}
