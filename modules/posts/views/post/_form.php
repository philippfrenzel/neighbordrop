<?php

use yii\widgets\ActiveForm;
use yii\helpers\Json;
use yii\web\JsExpression;

use philippfrenzel\yiiwymeditor\yiiwymeditor;

use kartik\helpers\Html;
use yii\helpers\Url;

use kartik\widgets\Select2;
use kartik\widgets\DatePicker;

use app\modules\categories\models\Categories;
use app\modules\workflow\models\Workflow;

/**
 * @var yii\base\View $this
 * @var app\modules\posts\models\Post $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="post-form">

  <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(array('maxlength' => 128)); ?>

    <?= $form->field($model, 'time_create')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => \Yii::t('app','Publish Date')],
        'pluginOptions' => [
          'autoclose' => true,
          'format'=> 'yyyy-mm-dd',
          'todayHighlight' => true,
          'numberOfMonths' => 2
        ]
    ]);?>

  <?php

$pinterest = <<< SCRIPT
{instanceReady: function() {
  this.dataProcessor.htmlFilter.addRules({
      elements: {
          img: function( el ) {
              if ( !el.attributes.class )
                el.attributes.class = 'img-responsive';
              if(el.attributes.alt == 'pinterest') {
                var fragment = CKEDITOR.htmlParser.fragment.fromHtml( '<div class="pinterest-image">'+el.getOuterHtml()+'</div>' );
                el.replaceWith(fragment);
              }
          }
      }
  });          
}}
SCRIPT;

  ?>

    <?= yiiwymeditor::widget(array(
      'model'=>$model,
      'attribute'=>'content',
      'clientOptions'=>array(
        'on' => new JsExpression($pinterest),
        'toolbar' => 'basic',
        'height' => '200px',
        'filebrowserBrowseUrl' => Url::to(array('/pages/page/filemanager')),
        'filebrowserImageBrowseUrl' => Url::to(array('/pages/page/filemanager','mode'=>'image')),
      ),
      'inputOptions'=>array(
        'size'=>'2',
      )
    ));?>

    <?= $form->field($model,'status')->dropDownList(Workflow::getStatusOptions()); ?>

    <?php

$dataExp = <<< SCRIPT
  function (term, page) {
    return {
      search: term, // search term
    };
  }
SCRIPT;

$dataResults = <<< SCRIPT
  function (data, page) {
    return {
      results: data.results
    };
  }
SCRIPT;

$createSearchChoice = <<< SCRIPT
function(term, data) {
    if ($(data).filter(function() {
      return this.text.localeCompare(term) === 0;
    }).length === 0) {
      return {
        id: term,
        text: term
      };
    }
 }
SCRIPT;

$tagValues = explode(',', $model->tags);
$initTagList = [];
foreach($tagValues AS $tmptag){
  $initTagList[] = ['id'=>$tmptag, 'text'=>$tmptag];
}

$jsonTags = Json::encode($initTagList);

$tagInitSelection = <<< SCRIPT
function (element, callback) {
  var obj= $jsonTags;
  callback(obj);
}
SCRIPT;

$tagurl = Url::to(['/tags/default/jsonlist']);

?>

    <?= $form->field($model, 'tags')->widget(Select2::classname(),[
          'options' => ['placeholder' => \Yii::t('app','add tags ...')],
          'addon' => [
            'prepend'=>[
              'content' => Html::icon('globe')
            ]
          ],
          'pluginOptions'=>[
            'tags' => true,
            'tokenSeparators' => [","],
            'multiple' => true,
            'allowClear' => true,
            'createSearchChoice' => new JsExpression($createSearchChoice),
            'initSelection' => new JsExpression($tagInitSelection),
            'ajax' => [
              'url' => $tagurl,
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ]
          ]
    ]); ?>

    <?php 
    echo $form->field($model, 'categories_id')->widget(Select2::classname(), [
      'data' => array_merge(["" => ""], Categories::getOptions(Workflow::MODULE_BLOG)),
      'options' => ['placeholder' => 'Select a categorie ...'],
      'pluginOptions' => [
          'allowClear' => true
      ],
    ]);
    ?>

    <div class="form-group">
      <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', array('class' => 'btn btn-primary')); ?>
    </div>

  <?php ActiveForm::end(); ?>

</div>
