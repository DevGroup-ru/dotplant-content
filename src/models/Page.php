<?php

namespace DotPlant\Content\models;

use DotPlant\Content\actions\ContentPublishAction;
use DotPlant\Content\ContentModule;
use DevGroup\Entity\traits\BaseActionsInfoTrait;
use DevGroup\Entity\traits\EntityTrait;
use DevGroup\Entity\traits\SoftDeleteTrait;
use DotPlant\EntityStructure\models\BaseStructure;
use DotPlant\Monster\Universal\MonsterEntityTrait;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class Page
 *
 * @property PageExtended[] $extended
 * @property array[] $content
 * @property array[] $providers
 * @property integer $template_id
 * @property integer $layout_id
 *
 * @package DotPlant\Content\models
 */
class Page extends BaseStructure
{
    use EntityTrait;
    use BaseActionsInfoTrait;
    use SoftDeleteTrait;
    use MonsterEntityTrait;

    const TRANSLATION_CATEGORY = 'dotplant.content';

    /**
     * @inheritdoc
     */
    protected static $tablePrefix = 'dotplant_page';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'multilingual' => [
                    'translationModelClass' => PageStructureTranslation::class,
                ],
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getTranslationModelClassName()
    {
        return PageStructureTranslation::class;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function advancedTranslatableAttributes()
    {
        $result = array_keys(PageExtended::getTableSchema()->columns);
        $result[] = 'content';
        $result[] = 'providers';
        return $result;
    }

    /**
     * @inheritdoc
     */
    public static function getAccessRules()
    {
        return [
            'view' => 'dotplant-content-view',
            'edit' => 'dotplant-content-edit',
            'delete' => 'dotplant-content-delete',
            'publish' => 'dotplant-content-publish',
        ];
    }

    /**
     * @inheritdoc
     */
    protected static $injectionActions = [
        'publish' => [
            'class' => ContentPublishAction::class
        ],
    ];

    /**
     * @inheritdoc
     */
    public function getEditPageTitle()
    {
        return (true === $this->getIsNewRecord())
            ? Yii::t('dotplant.content', 'New page')
            : Yii::t('dotplant.content', 'Edit {title}', ['title' => $this->name]);
    }

    /**
     * @inheritdoc
     */
    public static function getModuleBreadCrumbs()
    {
        return [
            [
                'url' => ['/structure/entity-manage/index'],
                'label' => Yii::t('dotplant.content', 'Pages management')
            ]
        ];
    }

    /**
     * @return array
     */
    public function additionalGridButtons()
    {
        return [
            'publish' => [
                'url' => '/structure/entity-manage/publish',
                'icon' => ($this->is_active == 1) ? 'eye-slash' : 'eye',
                'class' => ($this->is_active == 1) ? 'btn-warning' : 'btn-primary',
                'label' => Yii::t('dotplant.content', ($this->is_active == 1) ? 'Unpublish' : 'Publish'),
                'attrs' => ['entity_id']
            ]
        ];
    }

    /**
     * @return bool
     */
    public function saveMonsterContent()
    {
        return $this->defaultTranslation->save() && $this->save();
    }
}
