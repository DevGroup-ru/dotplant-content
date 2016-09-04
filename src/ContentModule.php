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
     * Count items per page
     *
     * @var int
     */
    public $itemsPerPage = 10;

    /**
     * Show hidden records in tree
     *
     * @var bool
     */
    public $showHiddenInTree = false;

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