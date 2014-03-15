<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrder $model
 */

$this->title = 'Request PO';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
//all that has to do with the grid
$target = Url::to(array('/purchase/purchase-order-line/update','id'=>''));
$gridURL = Url::to(['/purchase/purchase-order-line/dhtmlxgrid','un'=> date('Ymd'),'mainid'=>$model->id]);

$gridJS = <<<GRIDJS

$.fn.modal.Constructor.prototype.enforceFocus = function() {};

calculateFooterValues = function() {
    var nrTA = document.getElementById("nr_ta");
    var myResult = sumColumn(7);
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

doOnFilterStart = function(indexes,values){
  $.ajax("$gridURL&search="+values).
  success(function(data){      
      dhtmlxPurchaseRequestGrid.clearAll();
      dhtmlxPurchaseRequestGrid.parse(data,"json");
      calculateFooterValues();
      dhtmlxPurchaseRequestGrid.groupBy(0,["#title","#cspan","#cspan","#cspan","#stat_total","","","#stat_total","","",""]);
      dhtmlxPurchaseRequestGrid.setNumberFormat('0,000.00',4);
      dhtmlxPurchaseRequestGrid.setNumberFormat('0,000.00',5);
      dhtmlxPurchaseRequestGrid.setNumberFormat('0,000.00',7); 
    }
  );
}

$('#SubmitPurchaseRequest').on('click',function(event){
  myUrl = $(this).attr('href');
  $('#applicationModal').modal('show');
  $('#applicationModal div.modal-header h4').html('Window');
  $('#applicationModal div.modal-body').load(myUrl);
  return false;
});

$('#StartpageForwarder').on('click',function(event){
  myUrl = $(this).attr('href');
  $('#applicationModal').modal('show');
  $('#applicationModal div.modal-header h4').html('Window');
  $('#applicationModal div.modal-body').load(myUrl);
  return false;
});


GRIDJS;
$this->registerJs($gridJS);

?>

<div class="purchase-order-create">

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <?= Yii::t('app','Purchase Request by') ?> <?= $model->contact->contactName; ?> <?= \Yii::t('app','on'); ?> <b><?= date('d-m-Y',$model->time_create); ?></b>
        <div class="pull-right">
          PR# <?= $model->id; ?> Status: <span class="badge"><?= $model->status; ?></span>
        </div>
      </div>
      <div class="panel-body">
        <?php 

        if($model->status == 'created')
        {
          echo $this->render('@app/modules/purchase/views/purchase-order-line/_form_compact', [
            'model' => new app\modules\purchase\models\PurchaseOrderLine,
            'purchaseorder_id' => $model->id,
          ]); 
        }

        ?>

        <?php echo $this->render('@app/modules/purchase/views/purchase-order/blocks/order_block', [
            'model' => $model,
            'gridURL' => $gridURL
        ]); ?>        
        <hr>
        <div class="row">
          <div class="col-md-12">
          <?php

          if($model->status == 'created'):
            echo Html::a(\Yii::t('app','Submit'), ['/purchase/default/window', 'id' => $model->id,'win'=>'purchaseOrder_submit'], [
              'class' => 'btn btn-success pull-right',
              'id'    => 'SubmitPurchaseRequest'
            ]);
          ?>
            
          <div class="pull-right">&nbsp;</div>
  
          <?php
            echo Html::a(\Yii::t('app','Save'), ['/purchase/default/window', 'id' => $model->id,'win'=>'purchaseOrder_save'], [
              'class' => 'btn btn-primary pull-right',
              'id'    => 'StartpageForwarder'
            ]);            
          
          endif;
          ?>
          </div>
        </div>
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
