<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['username', 'email', 'status', 'level_id', 'password', 'created_by', 'updated_by'];
    
}
