<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;

use kartik\widgets\Select2;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var app\modules\article\models\Article $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="article-form">

	<?php $form = ActiveForm::begin(); ?>

		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'article')->textInput(['maxlength' => 200]) ?>
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'article_number')->textInput(['maxlength' => 200]) ?>
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

		<?php// $form->field($model, 'system_key')->textInput(['maxlength' => 100]) ?>

		<?php// $form->field($model, 'system_name')->textInput(['maxlength' => 100]) ?>

		<?= $form->field($model, 'creator_id')->textInput() ?>

		<?php// $form->field($model, 'system_upate')->textInput() ?>

		<?php// $form->field($model, 'time_deleted')->textInput() ?>

		<?php// $form->field($model, 'time_create')->textInput() ?>

		<?= $form->field($model, 'status')->textInput(['maxlength' => 255]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
