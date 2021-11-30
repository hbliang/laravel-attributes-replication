<?php

namespace Hbliang\AttributesReplication;

use Illuminate\Support\Collection;

class Replication
{
    protected $model;
    protected $relation;
    protected $forceFill = false;
    protected $map = [];
    protected $extra;
    protected $events = [];
    protected $passive = false;
    protected $findPassiveModel;

    public function __construct($model)
    {
        $this->model = $model;
        $this->extra = function() {
            return [];
        };
        $this->setFindFirstPassiveModel();
        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getRelation()
    {
        return $this->relation;
    }

    public function isPassive()
    {
        return $this->passive;
    }

    public function isForceFill()
    {
        return $this->forceFill;
    }

    public function findPassiveModel(Collection $collection)
    {
        return call_user_func($this->findPassiveModel, $collection);
    }

    public function model($model)
    {
        $this->model = $model;
        return $this;
    }

    public function relation($relation)
    {
        $this->relation = $relation;
        return $this;
    }

    public function map(array $map)
    {
        $this->map = $map;
        return $this;
    }

    public function forceFill($forceFill = true)
    {
        $this->forceFill = $forceFill;
        return $this;
    }

    public function event(...$events)
    {
        $this->events = $events;
        return $this;
    }

    public function extra(callable $fn)
    {
        $this->extra = $fn;
        return $this;
    }

    public function passive()
    {
        $this->passive = true;
        return $this;
    }

    public function setFindPassiveModel(\Closure $callable)
    {
        $this->findPassiveModel = $callable;
        return $this;
    }

    public function setFindFirstPassiveModel()
    {
        $this->findPassiveModel = function (Collection $collection) {
            return $collection->first();
        };

        return $this;
    }

    public function setFindLastPassiveModel()
    {
        $this->findPassiveModel = function (Collection $collection) {
            return $collection->last();
        };

        return $this;
    }
}
