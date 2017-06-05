<?php

namespace Modules\Soal\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mod_soal';

    protected $fillable = ['soal', 'kategori_id', 'pilihan', 'status', 'created_by', 'updated_by'];

    function rel_kategori()
    {
        return $this->hasOne('Modules\Kategori\Models\Kategori', 'id', 'kategori_id');
    }
}
