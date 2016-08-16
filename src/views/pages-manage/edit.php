<?php

/**
 * @var $this \yii\web\View
 * @var \DotPlant\EntityStructure\models\BaseStructure $model
 */
use app\vendor\dotplant\content\src\ContentModule;
use kartik\switchinput\SwitchInput;
use DevGroup\Multilingual\models\Context;
use dmstr\widgets\Alert;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\web\View;

$this->title = empty($model->id)
    ? Yii::t(ContentModule::TRANSLATION_CATEGORY, 'New page')
    : Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Edit page #{id}', ['id' => $model->id]);

$this->params['breadcrumbs'][] = [
    'url' => ['/pages-manage/index'],
    'label' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Pages management')
];
$this->params['breadcrumbs'][] = $this->title;
$contexts = ArrayHelper::map(Context::find()->all(), 'id', 'name');
$url = Url::to(['/pages-manage/autocomplete']);
$getContextUrl = Url::to(['/pages-manage/get-context-id']);
$missingText = Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Missing parameter {param}', ['param' => 'getContextUrl']);
$js = <<<JS
    window.DPContent = {
        getContextUrl: '$getContextUrl',
        missingText: '$missingText'
    };
JS;
$this->registerJs($js, View::POS_HEAD);

$form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'page-form',
//    'options' => [
//        'enctype' => 'multipart/form-data'
//    ]
]);
?>
<?= Alert::widget() ?>
<?= $form->field($model, 'entity_id', ['template' => '{input}'])->hiddenInput(['value' => $model->entity_id]) ?>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#page-data" data-toggle="tab" aria-expanded="true">
                    <?= Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Main options') ?>
                </a>
            </li>
            <?php if (false === $model->isNewRecord) : ?>
                <li class="">
                    <a href="#page-properties" data-toggle="tab" aria-expanded="false">
                        <?= Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Page properties') ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="page-data">
                <div class="col-sm-12 col-md-6">
                    <?= $form->field($model, 'parent_id')->widget(Select2::class, [
                        'initValueText' => (null === $model->parent)
                            ? Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Search for a parent ...')
                            : $model->parent->name,
                        'options' => [
                            'placeholder' => Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Search for a parent ...')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                'delay' => '400',
                                'error' => new JsExpression('function(error) {alert(error.responseText);}'),
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(parent) { return parent.text; }'),
                            'templateSelection' => new JsExpression('function (parent) { return parent.text; }'),
                        ],
                        'pluginEvents' => [
                            "change" => "$.select2Change",
                        ]
                    ]) ?>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="row">
                        <div class="col-sm-4">
                            <?= $form->field($model, 'expand_in_tree')->widget(SwitchInput::class) ?>
                        </div>
                        <div class="col-sm-4">
                            <?php if (null !== $model->parent) {
                                foreach ($contexts as $id => $name) {
                                    if ($id != $model->parent->context_id) {
                                        $cDDOptions['options'][$id] = ['disabled' => true];
                                    }
                                }
                            } else {
                                $cDDOptions = [];
                            } ?>
                            <?= $form->field($model, 'context_id')->dropDownList($contexts, $cDDOptions) ?>
                        </div>
                        <div class="col-sm-4">
                            <?= $form->field($model, 'sort_order') ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?= DevGroup\Multilingual\widgets\MultilingualFormTabs::widget([
                            'model' => $model,
                            'childView' => '@DotPlant/Content/views/pages-manage/multilingual-part.php',
                            'form' => $form,
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="page-properties">
                <?= \DevGroup\DataStructure\widgets\PropertiesForm::widget([
                    'model' => $model,
                    'form' => $form,
                ]) ?>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="btn-group pull-right" role="group" aria-label="Edit buttons">
                        <button type="submit" class="btn btn-success pull-right">
                            <?= Yii::t(ContentModule::TRANSLATION_CATEGORY, 'Save') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $form::end(); ?>