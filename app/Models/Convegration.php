<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convegration extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'label','last_massege_id','type'];
    public function participents()
    {
        return $this->belongsToMany(User::class,'participents','convegration_id', 'user_id')
        ->withPivot(['joined_at','role']);
    }
    public function messages()
    {
        return $this->hasMany(Messege::class,'convegration_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function lastmessage()
    {
        return $this->belongsTo(Messege::class,'last_massege_id','id')
        ->withDefault();
    }



}
