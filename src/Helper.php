<?php

namespace Hbliang\AttributesReplication;

use Illuminate\Database\Eloquent\Model;

class Helper
{
    public static function attributesToArrayByMap(Model $model, $map, $checkFunc = null)
    {
        $data = [];
        foreach ($map as $from => $to) {
            if (!$checkFunc || call_user_func([$model, $checkFunc], $from)) {
                $data[$to] = $model->getAttribute($from);
            }
        }

        return $data;
    }

    public static function extraAttributes(Model $model, callable $fn)
    {
        return call_user_func($fn, $model);
    }
}
