<?php

namespace Hbliang\AttributesReplication\Tests\Models;

use Hbliang\AttributesReplication\Contracts\AttributesReplicatable;
use Hbliang\AttributesReplication\Traits\HasAttributesReplication;
use Illuminate\Database\Eloquent\Model;

class Company extends Model implements AttributesReplicatable
{
    use HasAttributesReplication;
    protected $fillable = ['name', 'phone_number', 'link'];

    public static function registerAttributesReplication()
    {
        self::addAttributesReplication()
            ->map([
                'name' => 'company_name',
                'phone_number' => 'phone_number',
            ])
            ->forceFill()
            ->relation('users')
            ->event('saved');

        self::addAttributesReplication()
            ->map([
                'link' => 'company_link',
            ])
            ->forceFill()
            ->relation('users')
            ->filterRelation(function(User $user) {
                if ($user->name === 'TEST') {
                    return true;
                }
                return false;
            })
            ->event('saved');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
