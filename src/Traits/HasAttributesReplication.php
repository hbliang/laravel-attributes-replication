<?php

namespace Hbliang\AttributesReplication\Traits;

use Hbliang\AttributesReplication\Contracts\AttributesReplicatable;
use Hbliang\AttributesReplication\Replication;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasAttributesReplication
{
    protected static $_attributesReplications = [];

    public static function bootHasAttributesReplication()
    {
        if (!is_subclass_of(static::class, AttributesReplicatable::class)) {
            throw new \Exception(static::class . ' must implements AttributesReplicatable');
        }

        static::registerAttributesReplication();

        foreach (static::$_attributesReplications as $replication) {
            foreach ($replication->getEvents() as $event) {
                call_user_func([static::class, $event], function ($entity) use ($replication, $event) {
                    $data = null;
                    if (Str::endsWith($event, 'ing')) {
                        $data = $entity->attributesToArrayByMap($replication->getMap(), 'isDirty');
                    } else if (Str::endsWith($event, 'ed')) {
                        $data = $entity->attributesToArrayByMap($replication->getMap(), 'wasChanged');
                    }

                    if (!empty($data)) {
                        Collection::wrap($entity->getRelationValue($replication->getRelation()))->each(function ($relationValue) use ($data) {
                            $relationValue->fill($data);
                            $relationValue->save();
                        });
                    }
                });
            }
        }
    }

    public static function addAttributesReplication()
    {
        $replication = new Replication(static::class);

        static::$_attributesReplications[] = $replication;

        return $replication;
    }

    protected function attributesToArrayByMap($map, $checkFunc = null)
    {
        $data = [];
        foreach ($map as $from => $to) {
            if (!$checkFunc || call_user_func([$this, $checkFunc], $from)) {
                $data[$to] = $this->{$from};
            }
        }

        return $data;
    }
}
