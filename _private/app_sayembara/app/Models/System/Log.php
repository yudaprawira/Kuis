<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['activity', 'link', 'created_by', 'updated_by'];
}
