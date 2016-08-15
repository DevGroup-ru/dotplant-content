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
use DevGroup\DataStructure\Properties\Module;
use \DevGroup\AdminUtils\columns\ActionColumn;

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
                        'attribute' => 'is_deleted',
                        'label' => Module::t('app', 'Show deleted?'),
                        'value' => function ($model) {
                            return $model->isDeleted() === true ? Module::t('app', 'Deleted') : Module::t('app', 'Active');
                        },
                        'filter' => [
                            Module::t('app', 'Show only active'),
                            Module::t('app', 'Show only deleted')
                        ],
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'id' => null,
                            'prompt' => Module::t('app', 'Show all')
                        ]
                    ],
                    [
                        'class' => ActionColumn::class,
                        'options' => [
                            'width' => '120px',
                        ],
                        'buttons' => function ($model, $key, $index, $column) {
                            $result = [
                                [
                                    'url' => '/pages-manage/edit',
                                    'icon' => 'pencil',
                                    'class' => 'btn-primary',
                                    'label' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Edit'),
                                ],
                            ];

                            if ($model->isDeleted() === false) {
                                $result['delete'] = [
                                    'url' => '/pages-manage/delete',
                                    'visible' => false,
                                    'icon' => 'trash-o',
                                    'class' => 'btn-warning',
                                    'label' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Delete'),
                                    'options' => [
                                        'data-action' => 'delete',
                                    ],
                                ];
                            } else {
                                $result['restore'] = [
                                    'url' => '/pages-manage/restore',
                                    'icon' => 'undo',
                                    'class' => 'btn-info',
                                    'label' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Restore'),
                                ];
                                $result['delete'] = [
                                    'url' => '/pages-manage/delete',
                                    'urlAppend' => ['hard' => 1],
                                    'icon' => 'trash-o',
                                    'class' => 'btn-danger',
                                    'label' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Delete'),
                                    'options' => [
                                        'data-action' => 'delete',
                                    ],
                                ];
                            }

                            return $result;
                        }
                    ]
                ],
            ]);
            ?>
        </div>
    </div>
</div>
