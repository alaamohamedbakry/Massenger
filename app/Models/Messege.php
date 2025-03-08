<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Messege extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['convegration_id', 'user_id', 'body','type'];
    protected $table = 'masseges';
    public function convegration()
    {
        return $this->belongsTo(Convegration::class,'convegration_id','id');
    }
    Public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->withDefault([
            'name'=>__('Rentar')
        ]);
    }
    public function recipients(){
        return $this->belongsToMany(User::class,'recipients')->withPivot(['read_at','deleted_at']);
    }


}
