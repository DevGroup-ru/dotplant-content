<?php

namespace DotPlant\Content\actions;

use DevGroup\AdminUtils\actions\BaseAdminAction;
use DevGroup\Multilingual\behaviors\MultilingualActiveRecord;
use DevGroup\Multilingual\traits\MultilingualTrait;
use DevGroup\TagDependencyHelper\NamingHelper;
use DotPlant\EntityStructure\models\BaseStructure;
use DotPlant\EntityStructure\models\Entity;
use Yii;
use yii\caching\TagDependency;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ContentPublishAction
 *
 * @package DotPlant\Content\actions
 */
class ContentPublishAction extends BaseAdminAction
{
    /**
     * @param $id
     * @param $entity_id
     * @param string $returnUrl
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws \Exception
     * @throws bool
     */
    public function run($id, $entity_id, $returnUrl = '')
    {
        /** @var BaseStructure $entityClass */
        $entityClass = Entity::getEntityClassForId($entity_id);
        $permissions = $entityClass::getAccessRules();
        if (true === isset($permissions['publish']) && false === Yii::$app->user->can($permissions['publish'])) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        /**
         * @var BaseStructure|MultilingualActiveRecord|MultilingualTrait $model
         */
        $model = $entityClass::loadModel(
            $id,
            true,
            true,
            86400,
            new NotFoundHttpException(Yii::t('dotplant.content', 'Page not found!'))
        );
        $model->translations;
        $value = ($model->is_active == 1) ? 0 : 1;
        $model->translate(Yii::$app->multilingual->language_id)->is_active = $value;
        if (true === $model->save()) {
            TagDependency::invalidate($model->getTagDependencyCacheComponent(),
                [
                    NamingHelper::getCommonTag(BaseStructure::class),
                    NamingHelper::getCommonTag($entityClass)
                ]
            );
        }
        $returnUrl = empty($returnUrl) ? ['/structure/entity-manage'] : $returnUrl;
        return $this->controller->redirect($returnUrl);
    }
}