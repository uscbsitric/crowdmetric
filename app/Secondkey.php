<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secondkey extends Model
{
    protected $fillable = ['secret_number', 'user_id', 'blurt_id'];
}
