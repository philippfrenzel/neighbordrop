<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\web\JsExpression;
use kartik\widgets\ActiveForm;

use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrder $model
 * @var yii\widgets\ActiveForm $form
 */

$script = <<<SKRIPT

$('#PurchaseOrderForm').on('submit',function(){
  var postData = $(this).serializeArray();
  var formURL = $(this).attr('action');
  $.ajax({
    url : formURL,
    type: "POST",
    data : postData,
    success:function(data, textStatus, jqXHR)
    {
      
    },
    error: function(jqXHR, textStatus, errorThrown)
    {
      
    }
  });
  return false;
});

SKRIPT;

$this->registerJs($script);

?>

<div class="purchase-order-form">

	<?php $form = ActiveForm::begin([
    'id' => 'PurchaseOrderForm'
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

$url = Url::to(['/parties/contact/jsonlist']);

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

    <?= $form->field($model, 'contact_id')->widget(Select2::classname(),[
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

		<div class="form-group">
			<?= Html::submitButton(\Yii::t('app','Apply'), ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
