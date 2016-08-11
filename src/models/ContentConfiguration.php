<?php

namespace DotPlant\Content\models;

use app\vendor\dotplant\content\src\ContentModule;
use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use DotPlant\Content\controllers\PagesManageController;
use Yii;

class ContentConfiguration extends BaseConfigurationModel
{
    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $attributes = [
            'itemsPerPage',
        ];

        parent::__construct($attributes, $config);
        /** @var ContentModule $module */
        $module = ContentModule::module();
        $this->itemsPerPage = $module->itemsPerPage;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemsPerPage'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemsPerPage' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Items Per Page'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function webApplicationAttributes()
    {
        return [
            'controllerMap' => [
                'pages-manage' => PagesManageController::class,
            ]
        ];
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
                        ContentModule::TRANSLATION_CATEGORY => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'messages',
                        ]
                    ]
                ],
            ],
            'modules' => [
                'contentEntity' => [
                    'class' => ContentModule::class,
                    'itemsPerPage' => $this->itemsPerPage,
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
            '@DotPlant/Content' =>  realpath(dirname(__DIR__)),
        ];
    }
}
