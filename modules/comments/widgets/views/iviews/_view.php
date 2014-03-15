<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

?>

<div class="post-box bg_white" id="<?= $model->id; ?>">
  <?php echo \cebe\gravatar\Gravatar::widget([
    'email' => is_Object($model->author)?$model->author->name:$model->anonymous,
    'options' => [
        'alt' => $model->author->name,
        'class' => 'gravatar'
    ],
    'size' => 100
  ]); ?>
  <blockquote>
    <h5><i class="fa fa-comment"></i>&nbsp;<?= Yii::t('app','By'); ?> <?= is_Object($model->author)?Html::encode(strtoupper($model->author->name)):Html::encode(strtoupper($model->anonymous)); ?>  <small><?= date('Y-m-d h:m',$model->time_create); ?></small></h5>
    <i class="fa fa-quote-left"></i>&nbsp;<?= HtmlPurifier::process($model->content); ?> 
    <div class="op">
      <?php if(\Yii::$app->user->id == $model->author_id && !\Yii::$app->request->isAjax && !Yii::$app->user->isGuest): ?>
        <?= Html::a('Delete',array('/comments/comment/delete','id'=>$model->id),array('class'=>'delete pull-right tipster','title'=>delete)); ?>
      <?php endif; ?>
    </div>
  </blockquote>
</div>
<div class="clearfix"></div>
