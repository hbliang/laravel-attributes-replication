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
                        $relationValue = $replication->findPassiveModel(Collection::wrap($relationValue));
                        if ($relationValue) {
                            if ($replication->isForceFill()) {
                                $entity->forceFill(Helper::attributesToArrayByMap($relationValue, $replication->getMap()));
                            } else {
                                $entity->fill(Helper::attributesToArrayByMap($relationValue, $replication->getMap()));
                            }

                            $entity->save();
                        }

                    } else {
                        $data = [];
                        if (Str::endsWith($event, 'ing')) {
                            $data = Helper::attributesToArrayByMap($entity, $replication->getMap(), 'isDirty');
                        } else if (Str::endsWith($event, 'ed')) {
                            $data = Helper::attributesToArrayByMap($entity, $replication->getMap(), 'wasChanged');
                        }

                        $data = array_merge($data, Helper::extraAttributes($entity, $replication->getExtra()));

                        if (!empty($data)) {
                            Collection::wrap($entity->getRelationValue($replication->getRelation()))->filter(function($relationValue) use($replication) {
                                if (!$relationValue) {
                                    return false;
                                }

                                $filterRelation = $replication->getFilterRelation();

                                if (is_bool($filterRelation)) {
                                    return $filterRelation;
                                }

                                if (is_callable($filterRelation)) {
                                    return call_user_func($filterRelation, $relationValue);
                                }

                                return true;
                            })->each(function ($relationValue) use ($replication, $data) {
                                if ($replication->isForceFill()) {
                                    $relationValue->forceFill($data);
                                } else {
                                    $relationValue->fill($data);
                                }
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
