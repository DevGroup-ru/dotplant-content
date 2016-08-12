<?php

namespace DotPlant\Content\controllers;

use DevGroup\AdminUtils\controllers\BaseController;
use devgroup\JsTreeWidget\actions\AdjacencyList\TreeNodeMoveAction;
use devgroup\JsTreeWidget\actions\AdjacencyList\TreeNodesReorderAction;
use DotPlant\Content\assets\DotPlantContentAsset;
use DotPlant\Content\models\Page;
use DotPlant\EntityStructure\actions\BaseEntityAutocompleteAction;
use DotPlant\EntityStructure\actions\BaseEntityEditAction;
use DotPlant\EntityStructure\actions\BaseEntityListAction;
use DotPlant\EntityStructure\actions\BaseEntityTreeAction;
use DotPlant\EntityStructure\models\BaseStructure;
use DotPlant\EntityStructure\StructureModule;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class PagesManageController
 *
 * @package DotPlant\Content\controllers
 */
class PagesManageController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        DotPlantContentAsset::register($this->view);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => BaseEntityListAction::class,
                'entityClass' => Page::class,
                'viewFile' => '@DotPlant/Content/views/pages-manage/index'
            ],
            'edit' => [
                'class' => BaseEntityEditAction::class,
                'entityClass' => Page::class,
                'viewFile' => '@DotPlant/Content/views/pages-manage/edit'
            ],
            'autocomplete' => [
                'class' => BaseEntityAutocompleteAction::class
            ],
            'get-tree' => [
                'class' => BaseEntityTreeAction::class,
                'className' => Page::class,
            ],
            'tree-reorder' => [
                'class' => TreeNodesReorderAction::class,
                'className' => Page::class,
            ],
            'tree-parent' => [
                'class' => TreeNodeMoveAction::class,
                'className' => Page::class,
                'saveAttributes' => ['parent_id', 'context_id']
            ],
        ];
    }

    /**
     * Returns context_id according to given Entity id
     *
     * @return false|null|string
     * @throws NotFoundHttpException
     */
    public function actionGetContextId()
    {
        if (false === Yii::$app->request->isAjax) {
            throw new NotFoundHttpException(
                Yii::t(StructureModule::TRANSLATION_CATEGORY, 'Page not found')
            );
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $structureId = Yii::$app->request->post('structure_id');
        return BaseStructure::find()->select('context_id')->where(['id' => $structureId])->scalar();

    }
}