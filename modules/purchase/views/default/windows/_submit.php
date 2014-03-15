<?php

use kartik\helpers\Html;
use yii\helpers\Url;

use yii\web\JsExpression;
use kartik\widgets\ActiveForm;

use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderLine $model
 * @var yii\widgets\ActiveForm $form
 */

//suppress reload of existing asstes in main window
$this->assetBundles['yii\web\YiiAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\validators\ValidationAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\widgets\ActiveFormAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\web\JqueryAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\bootstrap\BootstrapAsset'] = new yii\web\AssetBundle;


$script = <<<SKRIPT

$('.submitcancelbutton').on('click',function(event){
  $('#applicationModal').modal('hide');
  event.preventDefault();
});

SKRIPT;

$this->registerJs($script);

?>

<div class="alert alert-info">
  
  <h1><?= Html::Icon('exclamation-sign'); ?> <?= \Yii::t('app','You are going to start the approval process!'); ?></h1>

  <blockquote>
    <?= \Yii::t('app','Based on your pouvoire guidelines select the appropriate approver to forward your request. If no approval is necessary click submit or cancel'); ?>
  </blockquote>

<?php if(!is_null($message) AND $message != ""): ?>
  <h4>
    <ul><li><?= \Yii::t('app',$message); ?></li></ul>    
  </h4>
<?php endif; ?>

<div class="purchase-order-form">

  <?php $form = ActiveForm::begin([
    'id' => 'PurchaseOrderSubmitForm',
    'action' => Url::to(['/purchase/default/submit','id'=>$model->id])
  ]); ?>

  <?= Html::activeHiddenInput($model,'id'); ?>

<?php if(is_null($message)): ?>

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

    <?= $form->field($model, 'approver_id')->widget(Select2::classname(),[
          'pluginOptions'=>[
            'minimumInputLength' => 3,
            'ajax' => [
              'url' => $url,
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>

<?php endif; ?>

    <div class="form-group">
      <?php if(is_null($message)): ?>
        <?= Html::submitButton(\Yii::t('app','Submit'), ['class' => 'btn btn-success']) ?>
        &nbsp;
      <?php endif; ?>
      <?php echo Html::a(\Yii::t('app','Cancel'), ['cancel', 'id' => $model->id], [
          'class' => 'btn btn-info submitcancelbutton'
        ]); ?>
      <?php if(!is_null($message) AND $message != ""): ?>
        &nbsp;
        <?php echo Html::a(\Yii::t('app','Delete'), ['/purchase/purchase-order/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger submitdeletebutton'
        ]); ?>
      <?php endif; ?>
    </div>

  <?php ActiveForm::end(); ?>

</div>

</div>
