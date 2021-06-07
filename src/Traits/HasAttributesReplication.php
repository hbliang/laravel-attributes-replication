<?php

namespace Hbliang\AttributesReplication\Traits;

use Hbliang\AttributesReplication\Contracts\AttributesReplicatable;
use Hbliang\AttributesReplication\Replication;
use Hbliang\AttributesReplication\Helper;
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
                    if ($replication->isPassive()) {
                        $relationValue = $entity->getRelationValue($replication->getRelation());
                        $entity->fill(Helper::attributesToArrayByMap($relationValue, $replication->getMap()));
                        $entity->save();
                    } else {
                        $data = null;
                        if (Str::endsWith($event, 'ing')) {
                            $data = Helper::attributesToArrayByMap($entity, $replication->getMap(), 'isDirty');
                        } else if (Str::endsWith($event, 'ed')) {
                            $data = Helper::attributesToArrayByMap($entity, $replication->getMap(), 'wasChanged');
                        }

                        if (!empty($data)) {
                            Collection::wrap($entity->getRelationValue($replication->getRelation()))->each(function ($relationValue) use ($data) {
                                $relationValue->fill($data);
                                $relationValue->save();
                            });
                        }
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
}
