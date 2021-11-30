<?php

namespace Hbliang\AttributesReplication\Tests\Models;

use Hbliang\AttributesReplication\Contracts\AttributesReplicatable;
use Hbliang\AttributesReplication\Traits\HasAttributesReplication;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AttributesReplicatable
{
    use HasAttributesReplication;

    protected $fillable = ['name', 'company_name', 'phone_number'];

    public static function registerAttributesReplication()
    {
        self::addAttributesReplication()
            ->map([
                'company_name' => 'company_name',
                // 'name' => 'user_name',
                'phone_number' => 'number',
            ])
            ->extra(function(User $user) {
                return [
                    'user_name' => $user->name,
                ];
            })
            ->relation('phone')
            ->event('saved');

        self::addAttributesReplication()
            ->passive()
            ->map([
                'name' => 'company_name',
                'phone_number' => 'phone_number',
                'link' => 'company_link',
            ])
            ->forceFill()
            ->relation('company')
            ->event('created');
    }

    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
