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

    public function getExtended()
    {
        return $this->hasOne(PageExtended::className(), ['model_id' => 'model_id', 'language_id' => 'language_id']);
    }

}
