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
      <td width="5%"><h5><i class="icon-check-empty"></i></h5></td>
      <td width="75%">
        <h5>
          <img src="http://lorempixel.com/40/40/animals" alt="Animals"></img>        
          <?= Yii::t('app','For').' '.strtoupper(User::find($model['creator_id'])->prename) .' '. strtoupper(User::find($model['creator_id'])->name); ?>
        </h5>        
      </td>
      <td>
        <small><i class="icon-time"></i> <?= date('Y-m-d h:m',$model['time_create']); ?></small>
        <h5><?= strtoupper(Workflow::getModuleAsString($model['task_table'])); ?></h5>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <i class="icon-quote-left"></i>&nbsp;
        <?= $model['content']; ?></td>
      <td>
        <span class="label label-info">
          <?= Html::encode($model['status']); ?>
        </span>
      </td>
    </tr>
    
    <tr>
      <td><h5><i class="icon-angel-right"></i></h5></td>
      <td>
        <?= Html::a(NULL, array("/tasks/default/update", "id"=>$model['id']), array('class' => 'btn btn-success btn-sm edit icon icon-edit', "id"=>$model['id'])); ?>
      </td>
      <td></td>
    </tr>
  </table>  
</li>
