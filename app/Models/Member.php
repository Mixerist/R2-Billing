<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $primaryKey = 'mUserNo';

    protected $table = 'Member';

    public $timestamps = false;
}
