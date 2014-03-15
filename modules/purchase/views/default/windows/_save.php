<?php

use kartik\helpers\Html;
use yii\web\JsExpression;
use kartik\widgets\ActiveForm;

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

$('#PurchaseOrderSubmitForm').on('submit',function(){
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

$('.savecancelbutton').on('click',function(event){
  $('#applicationModal').modal('hide');
  event.preventDefault();
});

SKRIPT;

$this->registerJs($script);

?>

<div class="alert alert-danger">
  
  <h1><?= Html::Icon('exclamation-sign'); ?> <?= \Yii::t('app','There are no records to be saved. Please go back and add a record or delete it!'); ?></h1>
<?php if(!is_null($message) AND $message != ""): ?>
  <h4>
    <ul><li><?= \Yii::t('app',$message); ?></li></ul>    
  </h4>
<?php endif; ?>

    <div class="form-group">
      <p>
      <?= Html::a(\Yii::t('app','Back'), ['view', 'id' => $model->id], ['class' => 'btn btn-success savecancelbutton']) ?>
      &nbsp;
      <?php echo Html::a(\Yii::t('app','Delete'), ['/purchase/purchase-order/delete', 'id' => $model->id], [
          'class' => 'btn btn-danger savedeletebutton'
      ]); ?>
      </p>
    </div>

</div>

</div>
