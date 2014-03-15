<?php

use \Yii;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

use app\models\User;
use app\modules\workflow\models\Workflow;
use app\modules\tasks\models\Task;

?>

<li class="task-box">
  <table class="table table-condensed">
    
    <tr>
      <td width="5%"><h4><i class="icon-star fg-color-orange"></i></h4></td>
      <td width="33%">
        <h4>
          <img src="http://lorempixel.com/40/40/animals" alt="Animals"></img>
          <?= Yii::t('app','From').' '.strtoupper(User::find($model['previous_user_id'])->email); ?><br>          
        </h4>        
      </td>
      <td>
        <i class="icon-angle-right icon-4x"></i>
      </td>
      <td width="33%">
        <h4>
          <img src="http://lorempixel.com/40/40/people" alt="Animals"></img>          
          <?= Yii::t('app','For').' '.strtoupper(User::find($model['next_user_id'])->email); ?>
        </h4>        
      </td>
      <td>
        <small><i class="icon-time"></i> <?= date('Y-m-d h:m',strtotime($model['date_create'])); ?></small>
        <h4><?= strtoupper(Workflow::getModuleAsString($model['wf_table'])); ?></h4>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <span class="label label-default">
          <?= Html::encode($model['status_from']); ?>
        </span>
        &nbsp;
        <span class="label label-info">
          <?= Html::encode($model['status_to']); ?>
        </span>
      </td>
      <td>        
      </td>
      <td>
        <i class="icon-quote-left"></i>&nbsp;
        <?= $model->RelatedContent; ?>
      </td>
      <td>
        
      </td>
    </tr>
    
    <tr>
      <td colspan="5">
        <?php
          if(class_exists('\app\modules\workflow\widgets\PortletWorkflowActions')){
            echo \app\modules\workflow\widgets\PortletWorkflowActions::widget(array(
              'id'=>$model->id,
            ));
          } 
        ?>
      </td>
    </tr>
  </table>  
</li>
