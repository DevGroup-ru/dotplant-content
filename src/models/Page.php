<?php

namespace DotPlant\Content\models;

use DevGroup\DataStructure\behaviors\HasProperties;
use DevGroup\DataStructure\behaviors\PackedJsonAttributes;
use DevGroup\DataStructure\traits\PropertiesTrait;
use DevGroup\Entity\traits\BaseActionsInfoTrait;
use DevGroup\Multilingual\behaviors\MultilingualActiveRecord;
use DevGroup\Multilingual\traits\MultilingualTrait;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use DotPlant\Content\interfaces\PageInterface;
use DotPlant\Monster\Universal\EntityTrait;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%content_page}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property array $content
 * @property array $providers
 * @property integer $template_id
 * @property integer $layout_id
 */
class Page extends ActiveRecord implements PageInterface
{
    use BaseActionsInfoTrait;
    use EntityTrait;
    use MultilingualTrait;
    use PropertiesTrait;
    use TagDependencyTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'properties' => [
                'class' => HasProperties::class,
                'autoFetchProperties' => true,
            ],
            'cacheableActiveRecord' => [
                'class' => CacheableActiveRecord::class,
            ],
            'packedJsonAttributes' => [
                'class' => PackedJsonAttributes::class,
            ],
            'translations' => [
                'class' => MultilingualActiveRecord::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{content_page}}';
    }

    /**
     * @inheritdoc
     */
    public function getRules()
    {
        return ArrayHelper::merge(
            [
                [['template_id', 'layout_id'], 'integer'],
                [['name', 'slug'], 'required'],
                [['name', 'slug'], 'string'],
                [['content', 'providers',], 'safe',],
                [['slug'], 'string', 'max' => 80],
            ],
            $this->propertiesRules()
        );
    }

    /**
     * @inheritdoc
     */
    public function getAttributeLabels()
    {
        return [
            'id' => \Yii::t('dotplant.content', 'ID'),
            'name' => \Yii::t('dotplant.content', 'Name'),
            'slug' => \Yii::t('dotplant.content', 'Slug'),
            'content' => \Yii::t('dotplant.content', 'Content'),
            'providers' => \Yii::t('dotplant.content', 'Providers'),
            'template_id' => \Yii::t('dotplant.content', 'Template'),
            'layout_id' => \Yii::t('dotplant.content', 'Layout'),
        ];
    }
}
