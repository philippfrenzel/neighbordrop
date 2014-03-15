<?php

use yii\widgets\ActiveForm;
use kartik\helpers\Html;
use yii\helpers\Url;

use kartik\widgets\Select2;

use yii\web\JsExpression;
use yii\helpers\Json;

/**
 * @var yii\web\View $this
 * @var app\modules\dms\models\DmpaperSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dmpaper-search">

	<?php $form = ActiveForm::begin([
		//'action' => ['index'],
		'method' => 'get',
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
          'options' => ['placeholder' => \Yii::t('app','Search supplier ...')],
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
		<div class="col-md-4"><?= $form->field($model, 'name') ?></div>
		<div class="col-md-4"><?= $form->field($model, 'tags') ?></div>
	</div>		

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary pull-right']) ?>
      <div class="pull-right">&nbsp;</div>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default pull-right']) ?>
		</div>

    <div class="clearfix"></div>

	<?php ActiveForm::end(); ?>

</div>
