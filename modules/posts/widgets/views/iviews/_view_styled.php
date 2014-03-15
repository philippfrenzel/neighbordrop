<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

?>

<div class="post-box">
  <div class="post-header">
    <div class="datebox pull-left c_gray">
      <?= date("M", strtotime($model->time_create)); ?><br>
      <?= date("d", strtotime($model->time_create)); ?>
    </div>
    <h3 class="lspace subline"><a href="<?=\Yii::$app->urlManager->createAbsoluteUrl(['/posts/post/onlineview', 'id' => $model->id, 'title'=>Html::encode(strtoupper($model->title))]); ?>"><?= Html::encode(strtoupper($model->title)); ?></a></h3>
  </div>
  <div class="post-content">
    <?= $model->content; ?>
  </div>

  <iframe src="<?=\Yii::$app->urlManager->createAbsoluteUrl(['/posts/post/disqus', 'id' => $model->id]); ?>" width="100%" height="250px" border="0"></iframe>

</div>