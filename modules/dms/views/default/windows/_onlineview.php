<?php

use kartik\helpers\Html;
use yii\widgets\ActiveForm;

use app\modules\dms\models\Dmsys;

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

SKRIPT;

$this->registerJs($script);

?>

<iframe id="viewer" src = "./js/Viewer.js/#<?= \Yii::$app->urlManager->createUrl('/dms/default/downloadattachement',['id'=>$model->id,'ext'=>'.pdf']); ?>" width='100%' height='700' allowfullscreen webkitallowfullscreen></iframe>

<div class="alert alert-info">
  <p>
    <?= Html::a(\Yii::t('app','Close'), ['cancel', 'id' => $model->id], ['class' => 'btn btn-danger deletecancelbutton']) ?>    
  </p>
</div>
