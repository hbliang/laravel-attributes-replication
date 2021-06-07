<?php

namespace Hbliang\AttributesReplication\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = ['number', 'user_name', 'company_name'];
}
