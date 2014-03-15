<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;

use kartik\widgets\Select2;
use yii\web\JsExpression;

//suppress reload of existing asstes in main window
$this->assetBundles['yii\web\YiiAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\validators\ValidationAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\web\JqueryAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\bootstrap\BootstrapAsset'] = new yii\web\AssetBundle;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\Contact $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="contact-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= Html::activeHiddenInput($model,'party_id'); ?>

		<?= $form->field($model, 'contactName')->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'department')->textInput(['maxlength' => 100]) ?>

		<div class="row">			
			<div class="col-md-6">
				<?= $form->field($model, 'email',[
							'addon' => ['prepend' => ['content'=>'@']]
						])->textInput(['maxlength' => 200]) ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'phone', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-earphone"></i>']]
						])->textInput(['maxlength' => 100]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'mobile', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]
						])->textInput(['maxlength' => 100]) ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'fax', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-print"></i>']]
						])->textInput(['maxlength' => 100]) ?>
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

$url = Url::to(['/parties/contact/jsonlistemail']);

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

		<?= $form->field($model, 'parent_mail')->widget(Select2::classname(),[
          'modal' => true,
          'addon' => ['prepend' => ['content'=>'@']],
          'pluginOptions'=>[
            'allowClear' => true,
            'minimumInputLength' => 2,
            'ajax' => [
              'url' => Url::to(['/parties/contact/jsonlistemail']),
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>		

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

$url = Url::to(['/parties/contact/jsonlistemail']);

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

		<?= $form->field($model, 'backup_mail')->widget(Select2::classname(),[
          'modal' => true,
          'addon' => ['prepend' => ['content'=>'@']],
          'pluginOptions'=>[
            'allowClear' => true,
            'minimumInputLength' => 2,
            'ajax' => [
              'url' => Url::to(['/parties/contact/jsonlistemail']),
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
