<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrder $model
 */

$this->title = 'Pending requested POs';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php
//all that has to do with the grid
$gridURL = Url::to(['/purchase/purchase-order-group/dhtmlxgrid','un'=> date('Ymd'),'mainid'=>$model->id]);

$gridJS = <<<GRIDJS

$.fn.modal.Constructor.prototype.enforceFocus = function() {};

calculateFooterValues = function() {
    var nrTA = document.getElementById("nr_ta");
    var myResult = sumColumn(6);
    nrTA.innerHTML = addCommas(myResult.toFixed(2));
    return true;
}

sumColumn = function(ind) {  
    var out = 0;
    for (var i = 0; i < dhtmlxPurchaseRequestGrid.getRowsNum(); i++) {
        out += parseFloat(dhtmlxPurchaseRequestGrid.cells2(i, ind).getValue());
    }
    return out;
}

GRIDJS;
$this->registerJs($gridJS);

?>

<div class="purchase-order-create">

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <?= Yii::t('app','Purchase Request by') ?> <?= $model->contact->contactName; ?> <?= \Yii::t('app','on'); ?> <b><?= date('d-m-Y',$model->time_create); ?></b>
        &nbsp;<?= \Yii::t('app','waiting for approval'); ?>
        <div class="pull-right">
          PR# <?= $model->id; ?> Status: <span class="badge"><?= $model->status; ?></span>
        </div>
      </div>
      <div class="panel-body">
        
        <?php echo $this->render('@app/modules/purchase/views/purchase-order/blocks/pending_block', [
            'model' => $model,
            'gridURL' => $gridURL
        ]); ?>        
        
        <hr>

        <div class="form-group">
          
          <div class="pull-right">&nbsp;</div>

          <?php
            echo Html::a(\Yii::t('app','Back'), ['/site/index'], [
              'class' => 'btn btn-primary pull-right',
              'id'    => 'StartpageForwarder'
            ]);
          ?>

        </div>
        <div class="clearfix"></div>
        <hr>
        <?php 
          if(class_exists('\app\modules\dms\widgets\PortletDms') && Yii::$app->user->identity->isAdvanced){
            echo \app\modules\dms\widgets\PortletDms::widget(array(
              'module'=>\app\modules\dms\models\Dmsys::MODULE_PURCHASE,
              'id'=>$model->id,
            )); 
          }
        ?>
    </div>
  </div>
  </div>
</div>

</div>
