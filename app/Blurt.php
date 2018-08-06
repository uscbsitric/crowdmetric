<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blurt extends Model
{
    protected $fillable = ['input_string'];
    
    public function secondKeys()
    {
        return $this->hasMany('App\Secondkey');
    }
}
