<?php

namespace app\vendor\dotplant\content\src;

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

    const TRANSLATION_CATEGORY = 'dotplant.content';

    /**
     * @return self Module instance in application
     */
    public static function module()
    {
        $module = Yii::$app->getModule('contentEntity');
        if ($module === null) {
            $module = new self('contentEntity');
        }
        return $module;
    }
}