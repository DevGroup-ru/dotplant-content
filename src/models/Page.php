<?php

namespace DotPlant\Content\models;

use app\vendor\dotplant\content\src\ContentModule;
use DevGroup\Entity\traits\BaseActionsInfoTrait;
use DevGroup\Entity\traits\EntityTrait;
use DevGroup\Entity\traits\SoftDeleteTrait;
use DotPlant\EntityStructure\models\BaseStructure;

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

    /**
     * @inheritdoc
     */
    protected static $tablePrefix = 'content_page';

    /**
     * @inheritdoc
     */
    protected static function getPageSize()
    {
        return ContentModule::module()->itemsPerPage;
    }


}
