<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ListView;
use kartik\icons\Icon;
use yii\widgets\Pjax;

use app\modules\dms\models\Dmpaper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\dms\models\DmpaperSearch $searchModel
 */

$this->title = \Yii::t('app','Your Reviews');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dmpaper-index">

  <a href="<?= Url::to(['/dms/dmpaper/index']); ?>" class="btn btn-success btn-circle">
      <i class="fa fa-2x fa-home"></i>
  </a>

  <a href="<?= Url::to(['/dms/dmpaper/index','timefilter'=>'today']); ?>" class="btn btn-info btn-big-circle tipster pull-right" id="back_review" title="<?= \Yii::t('app','todays inbox!'); ?>">
      <i class="fa fa-3x fa-chevron-down"></i><br>
      <b><?= Dmpaper::getStatisticForInboxByDays(1)->responsible()->one()->Inbox; ?> tdy</b>
  </a>

  <a href="<?= Url::to(['/dms/dmpaper/index','timefilter'=>'week']); ?>" class="btn btn-primary btn-big-circle tipster pull-right" id="back_review" title="<?= \Yii::t('app','weeks inbox!'); ?>">
      <i class="fa fa-3x fa-chevron-down"></i><br>
      <b><?= Dmpaper::getStatisticForInboxByDays(7)->responsible()->one()->Inbox; ?> wk</b>
  </a>

  <a href="<?= Url::to(['/dms/dmpaper/index','timefilter'=>'month']); ?>" class="btn btn-success btn-big-circle tipster pull-right" id="back_review" title="<?= \Yii::t('app','months inbox!'); ?>">
      <i class="fa fa-3x fa-chevron-down"></i><br>
      <b><?= Dmpaper::getStatisticForInboxByDays(30)->responsible()->one()->Inbox; ?> mth</b>
  </a>

	<h1><?= Html::encode($this->title) ?></h1>

  <div class="row">
    <div class="col-md-12">
      <?php Pjax::begin(); ?>
      <?php echo $this->render('@app/modules/dms/views/dmpaper/_search', ['model' => $searchModel]); ?>
      <?php echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '@app/modules/dms/views/dmpaper/iviews/_item_reviewer',
      ]); ?>      
      <?php Pjax::end(); ?>
    </div>
  </div>

</div>
