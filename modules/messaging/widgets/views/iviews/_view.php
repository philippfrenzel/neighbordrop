<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

?>

<div class="task-box">
  <div class="row">
    <div class="col-md-4">
      <h4>
        <img src="http://lorempixel.com/40/40/animals" alt="Animals"></img>
        <?= $model->Sender->prename; ?>
        &nbsp;<i class="icon-angle-right"></i>&nbsp;
        <img src="http://lorempixel.com/40/40/people" alt="People"></img>
        <?= is_null($model->Reciever)?'System':$model->Reciever->prename; ?>
      </h4>
    </div>
    <div class="col-md-6">

    </div>
    <div class="col-md-2">
      <i class="icon-time"></i>
      <?= $model->date_create; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-1">
      
    </div>
    <div class="col-md-9">
      <h4>
        <?= Html::encode($model->subject); ?>
      </h4>
      <i class="icon-quote-left"></i>&nbsp;
      <?= HtmlPurifier::process($model->body); ?>
    </div>
    <div class="col-md-2">
      <a href="<?= Url::to('reply'); ?>" class="btn btn-warning btn-sm tipster" title="<?= Yii::t('app','reply'); ?>"> 
        <i class="icon-mail-reply"> </i>
      </a>
      <a href="<?= Url::to('update'); ?>" class="btn btn-info btn-sm tipster" title="<?= Yii::t('app','update'); ?>"> 
        <i class="icon-pencil"> </i>
      </a>
      <a href="<?= Url::to('update'); ?>" class="btn btn-danger btn-sm tipster" title="<?= Yii::t('app','delete'); ?>"> 
        <i class="icon-trash"> </i>
      </a>      
    </div>
  </div>
</div>
