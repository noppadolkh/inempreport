<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArriveCheck extends Model
{
    use HasFactory;
    protected $table = 'arrive_report';
    
    public function leaveDoc(){
        return $this->hasOne(Phone::class, 'arrive_report_id', 'id');
    }
}
