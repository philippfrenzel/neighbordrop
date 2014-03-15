<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\ActiveForm;

use kartik\widgets\Select2;

//suppress reload of existing asstes in main window
$this->assetBundles['yii\web\YiiAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\validators\ValidationAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\widgets\ActiveFormAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\web\JqueryAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\bootstrap\BootstrapAsset'] = new yii\web\AssetBundle;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\Party $model
 * @var yii\widgets\ActiveForm $form
 */
$script = <<<SKRIPT

$('#submitPartyCreate').on('click',function(event){
  $('#PartyCreateForm').ajaxSubmit(
  {
    type : "POST",
    success: function(data){
      $('#dmpaper-party_id').val(data.model.id);
      $('#select2-chosen-1').html(data.model.organisationName);
      $('#applicationModal').modal('hide');
    }
  });
  event.preventDefault();
});

SKRIPT;

$this->registerJs($script);

?>

<div class="alert alert-info">
  
  <h5><?= Html::Icon('exclamation-sign'); ?> <?= \Yii::t('app','You are going to create a new supplier!'); ?></h5>

</div>


<div class="party-form">

	<?php $form = ActiveForm::begin([
    'id' => 'PartyCreateForm',
    'action' => Url::to(['/purchase/default/createparty']),
    'ajaxVar' => new JsExpression("function ($form) { return false; }")
  ]); ?>

		<?= $form->field($model, 'organisationName')->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'taxNumber')->textInput(['maxlength' => 100]) ?>

  <div class="row">     
    <div class="col-md-6">
      <?= $form->field($model, 'email',[
            'addon' => ['prepend' => ['content'=>'@']]
          ])->textInput(['maxlength' => 200]) ?>
    </div>
    <div class="col-md-6">
      <?= $form->field($model, 'fax', [
            'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-print"></i>']]
          ])->textInput(['maxlength' => 100]) ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?= $form->field($model, 'mobile', [
            'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]
          ])->textInput(['maxlength' => 100]) ?>
    </div>    
  </div>

  <div class="row">
    <div class="col-md-5">
      <?= $form->field($model, 'addressLine')->textInput(['maxlength' => 200]) ?>
    </div>
    <div class="col-md-2">
      <?= $form->field($model, 'postCode')->textInput(['maxlength' => 100]) ?>
    </div>
    <div class="col-md-5">
      <?= $form->field($model, 'cityName')->textInput(['maxlength' => 100]) ?>
    </div>
  </div>

  

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

$url = Url::to(['/parties/country/jsonlist']);

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

		<?= $form->field($model, 'countryCode')->widget(Select2::classname(),[
          'modal' => true,
          'pluginOptions'=>[
            'allowClear' => true,
            'minimumInputLength' => 2,
            'ajax' => [
              'url' => Url::to(['/parties/country/jsonlist']),
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>

		<div class="form-group">
			<?= Html::submitButton(\Yii::t('app','Create'), ['class' => 'btn btn-success','id'=>'submitPartyCreate']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
