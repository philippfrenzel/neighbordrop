<?php

use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\web\JsExpression;
use kartik\icons\Icon;

?>

<div class="dmpaper">
  <div class="widget-header">
    <div class="row">
      <div class="col-md-7">
        <h5>DocId #<?= $model['id']; ?> <?= $model['name']; ?> (<?= $model['documenttype']; ?>)</h5>
      </div>
      <div class="col-md-2">
        <div class="pull-right">
          <h5>
          <?php 
            if(class_exists('\app\modules\comments\widgets\PortletCommentsBatch')){
              echo \app\modules\comments\widgets\PortletCommentsBatch::widget(array(
                'module'      => \app\modules\workflow\models\Workflow::MODULE_DMPAPER,
                'id'          => $model['id'],
                'title'       => \Yii::t('app','Comments'),
                'htmlOptions' => array('class'=>'nothing'),
                'mode' => 'window',
              )); 
            }
          ?>
          </h5>
        </div>
      </div>
      <div class="col-md-3">
        <div class="pull-right">
          <h5>
          <div class="label label-warning tipster" title="<?= \Yii::t('app','incoming date') ?>">
            <?= Icon::show('arrow-circle-down', ['class'=>'fa'], Icon::FA) . ' ' . date('Y-m-d',$model['time_create']); ?>
          </div>
          &nbsp;
          <a href="<?= Url::to(['/dms/dmpaper/update','id'=>$model['id']]); ?>" class="label label-success tipster" title="<?= Yii::t('app','update'); ?>">
            <?= Icon::show('pencil', ['class'=>'fa'], Icon::FA);?>                
          </a>
          &nbsp;
          <a href="<?= Url::to(['/dms/dmpaper/delete','id'=>$model['id']]); ?>" class="label label-danger tipster" title="<?= Yii::t('app','delete'); ?>" data-confirm="<?= \Yii::t('app','Are you sure to delete this item?'); ?>" data-method="post">
            <?= Icon::show('trash-o', ['class'=>'fa'], Icon::FA);?>                
          </a>
          </h5>
        </div>
      </div>
    </div>    
  </div>
  <div class="widget-body">
    <div class="row">
      <div class="col-md-2">
        <img src="<?= Url::to(['/dms/default/getlatestthumb','id'=>$model['id'],'module' => \app\modules\workflow\models\Workflow::MODULE_DMPAPER]); ?>" alt="thumb"/>
      </div>
      <div class="col-md-4">          
        <blockquote>
          <p><?= \Yii::t('app','From') ?> <?= $model->party->organisationName; ?> <?= \Yii::t('app','to') ?> <?= $model['department']; ?></p>
          <small><?= $model['description']; ?></small>
        </blockquote>
      </div>
      <div class="col-md-3">
        <h6><?= \Yii::t('app','Reviewers') ?>:</h6>
        <?php 
          if(class_exists('\app\modules\workflow\widgets\PortletWorkflowParticipants')){
            echo \app\modules\workflow\widgets\PortletWorkflowParticipants::widget(array(
              'module'      => \app\modules\workflow\models\Workflow::MODULE_DMPAPER,
              'id'          => $model['id'],
              'htmlOptions' => array('class'=>'nothing'),
            )); 
          }
        ?>
      </div>
      <div class="col-md-3">
        <h6><?= \Yii::t('app','Tags') ?>:</h6>
        <?= implode('&nbsp; ',$model->tagLabels); ?>
      </div>      
    </div>
  </div>
</div>
