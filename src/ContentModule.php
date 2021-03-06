<?php

namespace DotPlant\Content;

use yii\base\Module;
use Yii;

/**
 * Class ContentModule
 *
 * @package app\vendor\dotplant\content\src
 */
class ContentModule extends Module
{
    /**
     * @return self Module instance in application
     */
    public static function module()
    {
        $module = Yii::$app->getModule('content');
        if ($module === null) {
            $module = Yii::createObject(self::class, ['content']);
        }
        return $module;
    }
}