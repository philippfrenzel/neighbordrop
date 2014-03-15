<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\web\JsExpression;
use kartik\widgets\ActiveForm;

use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\Party $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="party-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'organisationName')->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'taxNumber')->textInput(['maxlength' => 100]) ?>

		<?= $form->field($model, 'partyNote')->textarea(['rows' => 6]) ?>

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

		<?= $form->field($model, 'registrationCountryCode')->widget(Select2::classname(),[
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

		<?= $form->field($model, 'party_type')->dropDownList($model::getPartyTypeOptions()); ?>

		<?= $form->field($model, 'registrationNumber')->textInput(['maxlength' => 100]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? \Yii::t('app','Create') : \Yii::t('app','Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
