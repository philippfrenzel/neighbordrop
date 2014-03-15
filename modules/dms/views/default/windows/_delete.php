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
    type : "POST",
    dataType : "json",
  }).success(function(){
    $('#applicationModal').modal('hide');
  });
  event.preventDefault();
});

SKRIPT;

$this->registerJs($script);

?>

<div class="alert alert-danger">
  
  <h1><?= Html::Icon('exclamation-sign'); ?> <?= \Yii::t('app','Caution, do you want to delete this record!'); ?></h1>
  <p>
    <?php echo Html::a(\Yii::t('app','Yes'), ['/dms/default/delete', 'id' => $model->id], [
      'class' => 'btn btn-success deletedeletebutton',
      //'data-method' => 'post'
    ]); ?>
    <?= Html::a(\Yii::t('app','No'), ['cancel', 'id' => $model->id], ['class' => 'btn btn-danger deletecancelbutton']) ?>    
  </p>

</div>
