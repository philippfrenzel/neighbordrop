<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrder $model
 */

$this->registerAssetBundle('yii\widgets\ActiveFormAsset');
$this->registerAssetBundle('yii\validators\ValidationAsset');
$this->registerAssetBundle('kartik\widgets\Select2Asset');
$this->registerAssetBundle('kartik\widgets\TypeaheadAsset');
//$this->registerAssetBundle('kartik\widgets\Typeahead2Asset');
$this->registerAssetBundle('kartik\widgets\DatePickerAsset');


$this->title = 'Approve requested PO';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
//all that has to do with the grid
$target = Url::to(array('/purchase/purchase-order-line/update','id'=>''));
$gridURL = Url::to(['/purchase/default/dhtmlxgridegpdetail','un'=> date('Ymd'),'mainid'=>$model->id]);

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

doOnFilterStart = function(indexes,values){
  $.ajax("$gridURL&search="+values).
  success(function(data){      
      dhtmlxPurchaseRequestGrid.clearAll();
      dhtmlxPurchaseRequestGrid.parse(data,"json");
      calculateFooterValues();
      dhtmlxPurchaseRequestGrid.setNumberFormat('0,000.00',4);
      dhtmlxPurchaseRequestGrid.setNumberFormat('0,000.00',5);
      dhtmlxPurchaseRequestGrid.setNumberFormat('0,000.00',7); 
    }
  );
}

doOnApproval = function(rId,cInd,state){
  alert(rId);
  alert(state);
}

refreshAllGrids = function(){
  doOnFilterStart(1);
}

$('#SubmitPurchaseRequest').on('click',function(event){
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
        <?= Yii::t('app','Manage purchase') ?> <?= $model->contact->contactName; ?> <?= \Yii::t('app','on'); ?> <b><?= date('d-m-Y',$model->time_create); ?></b>
        &nbsp;<?= \Yii::t('app','needs your approval'); ?>
        <div class="pull-right">
          PR# <?= $model->id; ?> Status: <span class="badge"><?= $model->status; ?></span>
        </div>
      </div>
      <div class="panel-body">
        <?php echo $this->render('@app/modules/purchase/views/purchase-order/blocks/egp_block', [
            'model' => $model,
            'gridURL' => $gridURL
        ]); ?>
        <hr>
        <div class="form-group">
          <?php
            echo Html::a(\Yii::t('app','Submit'), ['/purchase/purchase-order-group/window','win'=>'purchaseOrderGroup_purchasesubmit' ,'id' => $model->id], [
              'class' => 'btn btn-success pull-right',
              'id'    => 'SubmitPurchaseRequest'
            ]);
          ?>
            
          <div class="pull-right">&nbsp;</div>
  
          <?php
            echo Html::a(\Yii::t('app','Save'), ['/site/index'], [
              'class' => 'btn btn-primary pull-right',
              'id'    => 'StartpageForwarder'
            ]);
          ?>
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
