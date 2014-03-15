<?php

use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\web\JsExpression;
use kartik\icons\Icon;

?>

<div class="media file-box">
  <a href="<?= Url::to(['/dms/default/downloadattachement','id'=>$model['id']]); ?>" target='_blank' class="pull-left">
    <img src="<?= Url::to(['/dms/default/getthumb','id'=>$model['id']]); ?>" alt="thumb"/> 
  </a>
  <?php 
    echo Html::a(Icon::show('search', ['class'=>'fa'], Icon::FA) . ' ' . \Yii::t('app','Online View'), ['/dms/default/window', 'id' => $model['id'], 'win'=>'dmsys_onlineview','mainid'=>NULL], [
      'class' => 'btn btn-success navbar-btn',
      'id' => 'window_dmsys_onlineview'.$model['id']
    ]); 

    $actionjs = new JsExpression("$('#window_dmsys_onlineview".$model['id']."').on('click',myModalWindow);");
    $this->registerJs($actionjs);
  ?>
  <div class="media-body">
    <h4 class="media-heading"><?= $model['filename']; ?></h4>
    <small><?= $model['used_space']; ?></small>
    <?= $model['status']; ?>
    <div class="op">
      <?php 
        echo Html::a(Icon::show('pencil', ['class'=>'fa'], Icon::FA) . ' ' . \Yii::t('app','Update'), ['/dms/default/window', 'id' => $model['id'], 'win'=>'dmsys_update','mainid'=>$model['id']], [
          'class' => 'btn btn-default navbar-btn navbar-right',
          'id' => 'window_dmsys_update'.$model['id']
        ]); 

        $actionjs = new JsExpression("$('#window_dmsys_update".$model['id']."').on('click',myModalWindow);");
        $this->registerJs($actionjs);
      ?>
      <div class="pull-right">&nbsp;</div>
      <?php 
        echo Html::a(Icon::show('trash-o', ['class'=>'fa'], Icon::FA) . ' ' . \Yii::t('app','Delete'), ['/dms/default/window', 'id' => $model['id'], 'win'=>'dmsys_delete','mainid'=>NULL], [
          'class' => 'btn btn-default navbar-btn navbar-right',
          'id' => 'window_dmsys_delete'.$model['id']
        ]); 

        $actionjs = new JsExpression("$('#window_dmsys_delete".$model['id']."').on('click',myModalWindow);");
        $this->registerJs($actionjs);
      ?>
      <div class="pull-right">&nbsp;</div>      
    </div>
  </div>
</div>
