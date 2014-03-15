<?php

use \Yii;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

use app\models\User;
use app\modules\workflow\models\Workflow;
use app\modules\tasks\models\Task;

?>

<div class="post-box" id="<?= $model->id; ?>">
  <blockquote>
    <h5>    
      <i class="fa fa-square-o"></i>  
      <?= Yii::t('app','Created by').' '.strtoupper(User::find($model['creator_id'])->email); ?>
    </h5>
    <?php 
      if(class_exists('\app\modules\workflow\widgets\PortletWorkflowParticipants')){
        echo \app\modules\workflow\widgets\PortletWorkflowParticipants::widget(array(
          'module'      => \app\modules\workflow\models\Workflow::MODULE_TASKS,
          'id'          => $model['id'],
          'htmlOptions' => array('class'=>'nothing'),
        )); 
      }
    ?>
    <small><i class="icon-time"></i> <?= date('Y-m-d h:m',$model['time_create']); ?></small>
    <i class="fa fa-quote-left"></i>&nbsp;<?= HtmlPurifier::process($model['content']); ?>  
  </blockquote>
  <div class="op">
    <?php if(\Yii::$app->user->id == $model->creator_id && !\Yii::$app->request->isAjax): ?>
      <?= Html::a(NULL,array('/tasks/default/delete','id'=>$model->id),array('class'=>'delete btn btn-danger btn-sm pull-right fa fa-trash-o tipster','title'=>delete)); ?>
    <?php endif; ?>
    <?= Html::a(NULL, array("/tasks/default/update", "id"=>$model['id']), array('class' => 'update btn btn-success btn-sm edit fa fa-pencil pull-right', "id"=>$model['id'])); ?>
  </div>
  <span class="label label-warning">
    <?= Html::encode($model['status']); ?>
    <?= strtoupper(Workflow::getModuleAsString($model['task_table'])); ?>
  </span>  
</div>
<div class="clearfix"></div>
