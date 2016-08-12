<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var  \DotPlant\EntityStructure\models\BaseStructure $searchModel
 * @var int $parentId
 */
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\helpers\Html;
use app\vendor\dotplant\content\src\ContentModule;
use devgroup\JsTreeWidget\widgets\TreeWidget;
use \devgroup\JsTreeWidget\helpers\ContextMenuHelper;
use DevGroup\AdminUtils\Helper;

$this->title = Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Pages');
$this->params['breadcrumbs'][] = $this->title;
$buttons = Html::a(
    Icon::show('plus') . '&nbsp'
    . Yii::t(ContentModule::TRANSLATION_CATEGORY, 'New page'),
    ['/pages-manage/edit', 'parent_id' => $parentId, 'returnUrl' => Helper::returnUrl()],
    [
        'class' => 'btn btn-success',
    ]);
$gridTpl = <<<HTML
<div class="box-body">
    {summary}
    {items}
</div>
<div class="box-footer">
    <div class="row list-bottom">
        <div class="col-sm-5">
            {pager}
        </div>
        <div class="col-sm-7">
            <div class="btn-group pull-right" style="margin: 20px 0;">
                $buttons
            </div>
        </div>
    </div>
</div>
HTML;
?>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <?= TreeWidget::widget([
            'treeDataRoute' => ['/pages-manage/get-tree', 'selected_id' => $parentId],
            'reorderAction' => ['/pages-manage/tree-reorder'],
            'changeParentAction' => ['/pages-manage/tree-parent'],
            'treeType' => TreeWidget::TREE_TYPE_ADJACENCY,
            'contextMenuItems' => [
                'open' => [
                    'label' => 'Open',
                    'action' => ContextMenuHelper::actionUrl(
                        ['/pages-manage/index'],
                        ['parent_id', 'context_id', 'id']
                    ),
                ],
                'edit' => [
                    'label' => 'Edit',
                    'action' => ContextMenuHelper::actionUrl(
                        ['/pages-manage/edit']
                    ),
                ]
            ],
        ]) ?>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="indreams-pages__list-pages box box-solid">
            <div class="box-header with-border clearfix">
                <h3 class="box-title pull-left">
                    <?= Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Pages list') ?>
                </h3>
            </div>
            <?php
            echo GridView::widget([
                'id' => 'pages-list',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => $gridTpl,
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover table-responsive',
                ],
                'columns' => [
                    [
                        'attribute' => 'name',
                        'options' => [
                            'width' => '20%',
                        ],
                    ],
                    [
                        'attribute' => 'title',
                        'options' => [
                            'width' => '20%',
                        ],
                    ],
                    [
                        'attribute' => 'h1',
                        'options' => [
                            'width' => '20%',
                        ],
                    ],
                    [
                        'attribute' => 'slug',
                        'options' => [
                            'width' => '15%',
                        ],
                    ],
                    [
                        'attribute' => 'is_active',
                        'label' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Active'),
                        'content' => function ($data) {
                            return Yii::$app->formatter->asBoolean($data->is_active);
                        },
                        'filter' => [
                            0 => Yii::$app->formatter->asBoolean(0),
                            1 => Yii::$app->formatter->asBoolean(1),
                        ],
                    ],
                    [
                        'class' => 'DevGroup\AdminUtils\columns\ActionColumn',
                        'options' => [
                            'width' => '95px',
                        ],
                        'buttons' => [
                            [
                                'url' => '/pages-manage/edit',
                                'icon' => 'pencil',
                                'class' => 'btn-primary',
                                'label' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Edit'),
                            ],
                            [
                                'url' => '/pages-manage/delete',
                                'icon' => 'trash-o',
                                'class' => 'btn-danger',
                                'label' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Delete'),
                            ],
                        ],
                    ]
                ],
            ]);
            ?>
        </div>
    </div>
</div>
