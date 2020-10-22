<?php

namespace Hbliang\AttributesReplication;

use Illuminate\Support\Arr;

class Replication
{
    protected $model;
    protected $relation;
    protected $force = false;
    protected $map = [];
    protected $events = [];

    public function __construct($model)
    {
        $this->model = $model;
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

    public function isForce()
    {
        return $this->force;
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
}
