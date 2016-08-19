<?php

namespace DotPlant\Content\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class DotPlantContentAsset
 *
 * @package DotPlant\Content\assets
 */
class DotPlantContentAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@DotPlant/Content/assets/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/dp-content.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        JqueryAsset::class,
    ];
}