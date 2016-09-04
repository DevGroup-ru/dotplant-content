<?php

namespace DotPlant\Content\models;

use DotPlant\Content\ContentModule;
use DevGroup\Entity\traits\BaseActionsInfoTrait;
use DevGroup\Entity\traits\EntityTrait;
use DevGroup\Entity\traits\SoftDeleteTrait;
use DotPlant\EntityStructure\models\BaseStructure;
use DotPlant\Monster\Universal\MonsterContentTrait;
use DotPlant\Monster\Universal\MonsterEntityTrait;
use yii\helpers\ArrayHelper;
use yii2tech\ar\role\RoleBehavior;

/**
 * Class Page
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

}
