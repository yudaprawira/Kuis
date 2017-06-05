<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['name', 'url', 'icon', 'description', 'status', 'created_by', 'updated_by'];
    
}
