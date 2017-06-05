<?php

namespace Modules\Soal\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mod_hasil';

    function member()
    {
        return $this->hasOne('Modules\Membership\Models\Membership', 'id', 'member_id');
    }
}
