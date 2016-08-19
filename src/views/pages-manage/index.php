<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var  \DotPlant\EntityStructure\models\BaseStructure $searchModel
 * @var int $parentId
 */
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\helpers\Html;
use devgroup\JsTreeWidget\widgets\TreeWidget;
use \devgroup\JsTreeWidget\helpers\ContextMenuHelper;
use DevGroup\AdminUtils\Helper;
use \DevGroup\AdminUtils\columns\ActionColumn;

$this->title = Yii::t('dotplant.content', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
$buttons = Html::a(
    Icon::show('plus') . '&nbsp'
    . Yii::t('dotplant.content', 'New page'),
    ['/content/pages-manage/edit', 'parent_id' => $parentId, 'returnUrl' => Helper::returnUrl()],
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
            'treeDataRoute' => ['/content/pages-manage/get-tree', 'selected_id' => $parentId],
            'reorderAction' => ['/content/pages-manage/tree-reorder'],
            'changeParentAction' => ['/content/pages-manage/tree-parent'],
            'treeType' => TreeWidget::TREE_TYPE_ADJACENCY,
            'contextMenuItems' => [
                'open' => [
                    'label' => 'Open',
                    'action' => ContextMenuHelper::actionUrl(
                        ['/content/pages-manage/index'],
                        ['parent_id', 'context_id', 'id']
                    ),
                ],
                'edit' => [
                    'label' => 'Edit',
                    'action' => ContextMenuHelper::actionUrl(
                        ['/content/pages-manage/edit']
                    ),
                ]
            ],
        ]) ?>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="indreams-pages__list-pages box box-solid">
            <div class="box-header with-border clearfix">
                <h3 class="box-title pull-left">
                    <?= Yii::t('dotplant.content', 'Pages list') ?>
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
                        'label' => Yii::t('dotplant.entity.structure', 'Name'),
                        'options' => [
                            'width' => '20%',
                        ],
                    ],
                    [
                        'attribute' => 'title',
                        'label' => Yii::t('entity', 'Title'),
                        'options' => [
                            'width' => '20%',
                        ],
                    ],
                    [
                        'attribute' => 'slug',
                        'label' => Yii::t('entity', 'Last url part'),
                        'options' => [
                            'width' => '15%',
                        ],
                    ],
                    [
                        'attribute' => 'is_active',
                        'label' => Yii::t('dotplant.content', 'Active'),
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
                        'label' => Yii::t('dotplant.content', 'Show deleted?'),
                        'value' => function ($model) {
                            return $model->isDeleted() === true ? Yii::t('dotplant.content', 'Deleted') : Yii::t('dotplant.content', 'Active');
                        },
                        'filter' => [
                            Yii::t('dotplant.content', 'Show only active'),
                            Yii::t('dotplant.content', 'Show only deleted')
                        ],
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'id' => null,
                            'prompt' => Yii::t('dotplant.content', 'Show all')
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
                                    'url' => '/content/pages-manage/edit',
                                    'icon' => 'pencil',
                                    'class' => 'btn-primary',
                                    'label' => Yii::t('dotplant.content', 'Edit'),
                                ],
                            ];

                            if ($model->isDeleted() === false) {
                                $result['delete'] = [
                                    'url' => '/content/pages-manage/delete',
                                    'visible' => false,
                                    'icon' => 'trash-o',
                                    'class' => 'btn-warning',
                                    'label' => Yii::t('dotplant.content', 'Delete'),
                                    'options' => [
                                        'data-action' => 'delete',
                                        'data-method' => 'post',
                                    ],
                                ];
                            } else {
                                $result['restore'] = [
                                    'url' => '/content/pages-manage/restore',
                                    'icon' => 'undo',
                                    'class' => 'btn-info',
                                    'label' => Yii::t('dotplant.content', 'Restore'),
                                ];
                                $result['delete'] = [
                                    'url' => '/content/pages-manage/delete',
                                    'urlAppend' => ['hard' => 1],
                                    'icon' => 'trash-o',
                                    'class' => 'btn-danger',
                                    'label' => Yii::t('dotplant.content', 'Delete'),
                                    'options' => [
                                        'data-action' => 'delete',
                                        'data-method' => 'post',
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
