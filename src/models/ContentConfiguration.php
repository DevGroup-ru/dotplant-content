<?php

namespace DotPlant\Content\models;

use DotPlant\Content\ContentModule;
use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use Yii;

class ContentConfiguration extends BaseConfigurationModel
{
    /**
     * @inheritdoc
     */
    public function getModuleClassName()
    {
        return ContentModule::className();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemsPerPage'], 'integer'],
            [['showHiddenInTree'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemsPerPage' => Yii::t('dotplant.content', 'Items Per Page'),
            'showHiddenInTree' => Yii::t('dotplant.content', 'Show Hidden Records In Tree'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function webApplicationAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function consoleApplicationAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function commonApplicationAttributes()
    {
        return [
            'components' => [
                'i18n' => [
                    'translations' => [
                        'dotplant.content' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'messages',
                        ]
                    ]
                ],
            ],
            'modules' => [
                'content' => [
                    'class' => ContentModule::class,
                    'itemsPerPage' => $this->itemsPerPage,
                    'showHiddenInTree' => (bool)$this->showHiddenInTree
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function appParams()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function aliases()
    {
        return [
            '@DotPlant/Content' => realpath(dirname(__DIR__)),
        ];
    }
}
