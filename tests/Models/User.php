<?php

namespace Hbliang\AttributesReplication\Tests\Models;

use Hbliang\AttributesReplication\Contracts\AttributesReplicatable;
use Hbliang\AttributesReplication\Traits\HasAttributesReplication;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AttributesReplicatable
{
    use HasAttributesReplication;

    protected $fillable = ['name', 'company', 'phone_number'];

    public static function registerAttributesReplication()
    {
        self::addAttributesReplication()
            ->map([
                'company' => 'company',
                'name' => 'user_name',
                'phone_number' => 'number',
            ])
            ->relation('phone')
            ->event('saved');
    }

    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }
}
