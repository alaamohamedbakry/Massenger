<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Participent extends Pivot
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'participents';
    protected $casts =[
     'joined_at' => 'datetime'
    ];
    public function convegration(){
        return $this->belongsTo(Convegration::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
