<?php

namespace DotPlant\Content\controllers;

use DotPlant\Content\ContentModule;
use DevGroup\AdminUtils\controllers\BaseController;
use devgroup\JsTreeWidget\actions\AdjacencyList\TreeNodesReorderAction;
use DotPlant\Content\assets\DotPlantContentAsset;
use DotPlant\Content\models\Page;
use DotPlant\EntityStructure\actions\BaseEntityAutocompleteAction;
use DotPlant\EntityStructure\actions\BaseEntityDeleteAction;
use DotPlant\EntityStructure\actions\BaseEntityEditAction;
use DotPlant\EntityStructure\actions\BaseEntityListAction;
use DotPlant\EntityStructure\actions\BaseEntityRestoreAction;
use DotPlant\EntityStructure\actions\BaseEntityTreeAction;
use DotPlant\EntityStructure\actions\BaseEntityTreeMoveAction;
use DotPlant\EntityStructure\models\BaseStructure;
use DotPlant\EntityStructure\StructureModule;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'get-tree', 'edit', 'autocomplete', 'get-context-id'],
                        'allow' => true,
                        'roles' => ['dotplant-content-view'],
                    ],
                    [
                        'actions' => ['edit', 'tree-reorder', 'tree-parent'],
                        'allow' => true,
                        'roles' => ['dotplant-content-edit', 'dotplant-content-publish'],
                    ],
                    [
                        'actions' => ['delete', 'restore'],
                        'allow' => true,
                        'roles' => ['dotplant-content-delete'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['*'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ]
        ];
    }

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
                'viewFile' => '@DotPlant/Content/views/pages-manage/edit',
                'permission' => 'dotplant-content-edit'
            ],
            'autocomplete' => [
                'class' => BaseEntityAutocompleteAction::class,
                'entityClass' => Page::class,
            ],
            'delete' => [
                'class' => BaseEntityDeleteAction::class,
                'entityClass' => Page::class,
            ],
            'restore' => [
                'class' => BaseEntityRestoreAction::class,
                'entityClass' => Page::class,
            ],
            'get-tree' => [
                'class' => BaseEntityTreeAction::class,
                'className' => Page::class,
                'showHiddenInTree' => ContentModule::module()->showHiddenInTree,
            ],
            'tree-reorder' => [
                'class' => TreeNodesReorderAction::class,
                'className' => Page::class,
            ],
            'tree-parent' => [
                'class' => BaseEntityTreeMoveAction::class,
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
                Yii::t('dotplant.entity.structure', 'Page not found')
            );
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $structureId = Yii::$app->request->post('structure_id');
        return BaseStructure::find()->select('context_id')->where(['id' => $structureId])->scalar();
    }
}
