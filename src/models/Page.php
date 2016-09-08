<?php

namespace DotPlant\Content\models;

use DotPlant\Content\ContentModule;
use DevGroup\Entity\traits\BaseActionsInfoTrait;
use DevGroup\Entity\traits\EntityTrait;
use DevGroup\Entity\traits\SoftDeleteTrait;
use DotPlant\EntityStructure\models\BaseStructure;
use DotPlant\Monster\Universal\MonsterEntityTrait;
use yii\helpers\ArrayHelper;

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

    public function getTranslationModelClassName()
    {
        return PageStructureTranslation::class;
    }
    /**
     * @inheritdoc
     */
    protected static function getPageSize()
    {
        return ContentModule::module()->itemsPerPage;
    }

    public function advancedTranslatableAttributes()
    {
        $result = array_keys(PageExtended::getTableSchema()->columns);
        $result[] = 'content';
        $result[] = 'providers';
        return $result;
    }

    public function saveMonsterContent()
    {
        return $this->defaultTranslation->save() && $this->save();
    }

}
