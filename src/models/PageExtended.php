<?php

namespace DotPlant\Content\models;

use DevGroup\DataStructure\behaviors\PackedJsonAttributes;
use yii;

class PageExtended extends yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%dotplant_page_ext}}';
    }

    public function behaviors()
    {
        return [
            'PackedJsonAttributes' => [
                'class' => PackedJsonAttributes::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'packed_json_content',
                    'packed_json_providers',
                ],
                'default',
                'value' => '[]',
            ]
        ];
    }
}
