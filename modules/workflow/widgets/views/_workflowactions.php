<?php

use yii\helpers\Html;
use app\modules\workflow\models\Workflow;

?>

<div class="row-fluid">
  <div class="col-md-1">
    <i class="fg-color-blue icon icon-angel-right"></i>Next:
  </div>
  <div class="col-md-11">
    <?php
      foreach($model->NextActions AS $wfAction){
        if($wfAction!=''){
          $html .= '<span class="btn btn-default btn-xs tipster" title="next action: '.Yii::t('app',$wfAction).'">';
          $html .= '<i class="icon-eye"></i>'.Html::a(Yii::t('app',$wfAction), array('/'.Workflow::getModuleAsController($model->wf_table).'/'.$wfAction,'id'=>$model->wf_id,'senderId'=>$model->id)).' ';
          $html .= '</span>&nbsp;';
        }
      }
      echo $html;
    ?>
  </div>
</div>