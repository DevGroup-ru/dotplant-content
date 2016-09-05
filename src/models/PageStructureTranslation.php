<?php

namespace DotPlant\Content\models;

use DotPlant\EntityStructure\models\StructureTranslation;
use yii\helpers\ArrayHelper;
use yii2tech\ar\role\RoleBehavior;

class PageStructureTranslation extends StructureTranslation
{
    public function behaviors()
    {
        return ArrayHelper::merge(

            parent::behaviors(),
            [
                'roleBehavior' => [
                    'class' => RoleBehavior::className(),
                    'roleRelation' => 'extended',
                ],
            ]
        );
    }

    /**
     * Modifies base query to include extended relation to reduce total queries count
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        $query = parent::find();
        $query->joinWith('extended');
        return $query;
    }

    public function getExtended()
    {
        return $this->hasOne(PageExtended::className(), ['model_id' => 'model_id', 'language_id' => 'language_id']);
    }

}
