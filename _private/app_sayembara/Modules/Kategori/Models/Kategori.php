<?php

namespace Modules\Kategori\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mod_kategori';

    protected $fillable = ['kategori', 'url', 'status', 'created_by', 'updated_by'];

    function rel_soal()
    {
        return $this->hasMany('Modules\Soal\Models\Soal', 'kategori_id','id')->inRandomOrder();
    }
}
