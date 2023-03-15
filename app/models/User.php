<?php

namespace App\models;

use System\database\orm\Model;

class User extends Model
{
    protected $table = 'users';
    protected array $fillable = ['username'];
}