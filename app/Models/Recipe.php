<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['titulo','foto','pasos'];
    public function user()
   {
       return $this->belongsTo(\App\Models\User::class);
   }
}
