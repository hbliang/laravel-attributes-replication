<?php

namespace Hbliang\AttributesReplication\Tests\Models;

use Hbliang\AttributesReplication\Contracts\AttributesReplicatable;
use Hbliang\AttributesReplication\Traits\HasAttributesReplication;
use Illuminate\Database\Eloquent\Model;

class Company extends Model implements AttributesReplicatable
{
    use HasAttributesReplication;
    protected $fillable = ['name', 'phone_number'];

    public static function registerAttributesReplication()
    {
        self::addAttributesReplication()
            ->map([
                'name' => 'company_name',
                'phone_number' => 'phone_number',
            ])
            ->relation('users')
            ->event('saved');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
