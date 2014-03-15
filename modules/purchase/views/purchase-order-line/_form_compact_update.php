<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

use yii\web\JsExpression;
use kartik\widgets\DatePicker;
use kartik\widgets\Typeahead;
use kartik\widgets\Select2;

//suppress reload of existing asstes in main window
$this->assetBundles['yii\web\YiiAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\validators\ValidationAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\widgets\ActiveFormAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\web\JqueryAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\bootstrap\BootstrapAsset'] = new yii\web\AssetBundle;
$this->assetBundles['kartik\widgets\DatePickerAsset'] = new yii\web\AssetBundle;
$this->assetBundles['kartik\widgets\Typeahead1Asset'] = new yii\web\AssetBundle;
$this->assetBundles['kartik\widgets\Typeahead2Asset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\bootstrap\Select2Asset'] = new yii\web\AssetBundle;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderLine $model
 * @var yii\widgets\ActiveForm $form
 */

$script = <<<SKRIPT

$('.addprlineupdate').on('click',function(event){
  $('#PurchaseRequestUpdateForm').ajaxSubmit(
  {
    type : "POST",
    success: function(){
      $('#applicationModal').modal('hide');
      window.doOnFilterStart(1,'');      
    }
  });
  event.preventDefault();
});

SKRIPT;

$this->registerJs($script);

?>

<div class="purchase-order-line-form">

	<?php $form = ActiveForm::begin([
		'id' => 'PurchaseRequestUpdateForm',
		'action' => Url::to(['/purchase/purchase-order-line/update','id'=>$model->id]),
    'ajaxVar' => new JsExpression("function ($form) { return false; }")
	]); ?>


<div class="row">
   <div class="col-md-4">
    
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

$url = Url::to(['/parties/contact/jsonlist']);

$usercontactid = $model->purchaseordergroup_id;
  
$fInitSelection = <<< SCRIPT
  function (element, callback) {
    $.ajax("$url&id=$usercontactid", {
        dataType: "json"
    }).done(function(data) { callback(data.results); });
  }
SCRIPT;

?>

    <?= $form->field($model, 'purchaseordergroup_id')->widget(Select2::classname(),[
          'size' => 'lg',
          'pluginOptions'=>[
            'minimumInputLength' => 3,
            'ajax' => [
              'url' => Url::to(['/parties/contact/jsonlist']),
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>

    <?= Html::activeHiddenInput($model,'purchaseorder_id',['value'=>$purchaseorder_id]); ?>
  
  </div>
  <div class="col-md-6">
      <?= Html::activeHiddenInput($model,'article_id'); ?>
      <?= $form->field($model, 'article')->widget(Typeahead::classname(),[
            'options' => ['placeholder' => \Yii::t('app','Product')],
            'dataset' => [
              [
                'remote' => \Yii::$app->controller->createUrl('/article/article/jsonlist') . '&search=%QUERY',
                'limit' => 10,
                'template' => '<p class="repo-language">{{value}}</p>' .
                  '<p class="repo-name">{{organisationName}} ({{price}})</p>',
                'engine' => 'Hogan'
              ]
            ]
      ]); ?>
  </div>
  <div class="col-md-2">
    <div class="form-group">
      <?= $form->field($model, 'date_delivery')->widget(DatePicker::classname(), [
        'model' => $model,
        'attribute' => 'date_delivery',
        'value' => date('d-M-Y', strtotime('+7 days')),
        'options' => ['placeholder' => \Yii::t('app','Delivery Date')],
        'pluginOptions' => [
          'format' => 'yyyy-mm-dd',
          'todayHighlight' => true,
          'numberOfMonths' => 2
        ]
      ]);?>
    </div>
  </div>
</div>

    

<div class="row">
  <div class="col-xs-4">

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

$url = Url::to(['/parties/party/jsonlist']);

$fInitSelection = <<< SCRIPT
  function (element, callback) {
    var id=$(element).val();
    if (id!=="") {
      $.ajax("$url&id="+id, {
        dataType: "json"
      }).done(function(data) { callback(data.results); });
    }
  }
SCRIPT;

?>

    <?= $form->field($model, 'party_id')->widget(Select2::classname(),[
          'options' => ['placeholder' => \Yii::t('app','Select supplier ...')],
          'pluginOptions'=>[
            'minimumInputLength' => 3,
            'ajax' => [
              'url' => Url::to(['/parties/party/jsonlist']),
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>
  
  </div>
  <div class="col-xs-2">
      <?= $form->field($model, 'order_amount',['template'=>'<label>&nbsp;</label>{input}{hint}{error}'])->textInput(['placeholder'=>\Yii::t('app','Quantity')]) ?>        
  </div>
  <div class="col-xs-2">
      <?= $form->field($model, 'order_uom',['template'=>'<label>&nbsp;</label>{input}{hint}{error}'])->dropDownList($model->UomPropertiesOptions,['placeholder'=>\Yii::t('app','UoM')]) ?>
  </div>
  <div class="col-xs-2">
    <?= $form->field($model, 'order_price',['template'=>'<label>&nbsp;</label>{input}{hint}{error}'])->textInput(['placeholder'=>\Yii::t('app','Price')]) ?>
  </div>
  <div class="col-xs-2">
    <?= $form->field($model, 'order_currency',[
          'template'=>'<label>&nbsp;</label>{input}{hint}{error}',
          'addon' => [
            'append' => [
              'content' => Html::submitButton(\Yii::t('app','Update'), ['class' => 'btn btn-info addprlineupdate']),
              'asButton' => true
            ]
        ]])->dropDownList($model->CurPropertiesOptions,['placeholder'=>\Yii::t('app','CUR')]) ?>
  </div>
</div>

	<?php ActiveForm::end(); ?>

</div>

<hr>
