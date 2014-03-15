<?php

use kartik\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderLine $model
 * @var yii\widgets\ActiveForm $form
 */

$script = <<<SKRIPT

$('.deletecancelbutton').on('click',function(event){
  $('#applicationModal').modal('hide');
  event.preventDefault();
});

$('.deletedeletebutton').on('click',function(event){  
  var delURL = $(this).attr('href');
  $.ajax({
    url : delURL,
    type : "GET",
    dataType : "json",
  }).success(function(){
    $('#applicationModal').modal('hide');
    window.doOnFilterStart(1,'');
  });
  event.preventDefault();
});

SKRIPT;

$this->registerJs($script);

?>

<div class="alert alert-danger">
  
  <h1><?= Html::Icon('exclamation-sign'); ?> <?= \Yii::t('app','Caution, you are planing to delete a record!'); ?></h1>
  <p>
    <?= Html::a(\Yii::t('app','Cancel'), ['cancel', 'id' => $model->id], ['class' => 'btn btn-primary deletecancelbutton']) ?>
    <?php echo Html::a(\Yii::t('app','Delete'), ['delete', 'id' => $model->id], [
      'class' => 'btn btn-danger deletedeletebutton'
    ]); ?>
  </p>

</div>
