<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'level';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];
    
    
}
