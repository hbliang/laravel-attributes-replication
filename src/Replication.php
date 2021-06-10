<?php

namespace Hbliang\AttributesReplication;

use Illuminate\Support\Collection;

class Replication
{
    protected $model;
    protected $relation;
    protected $force = false;
    protected $map = [];
    protected $events = [];
    protected $passive = false;
    protected $findPassiveModel;

    public function __construct($model)
    {
        $this->model = $model;
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

    public function isForce()
    {
        return $this->force;
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

    public function forece($force = true)
    {
        $this->force = $force;
        return $this;
    }

    public function event(...$events)
    {
        $this->events = $events;
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
