<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ListView;
use kartik\icons\Icon;
use yii\widgets\Pjax;

use app\modules\dms\models\Dmpaper;

use yiidhtmlx\Chart;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\dms\models\DmpaperSearch $searchModel
 */

$this->title = 'DOCrunner Overview';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dmpaper-index">

	<h2 class="pull-left"><?= Html::encode($this->title) ?></h2>

  <a href="<?= Url::to(['/dms/dmpaper/index','timefilter'=>'today']); ?>" class="btn btn-info btn-big-circle tipster pull-right" id="back_review" title="<?= \Yii::t('app','todays inbox!'); ?>">
      <i class="fa fa-3x fa-chevron-down"></i><br>
      <b><?= Dmpaper::getStatisticForInboxByDays(1)->one()->Inbox; ?> tdy</b>
  </a>

  <a href="<?= Url::to(['/dms/dmpaper/index','timefilter'=>'week']); ?>" class="btn btn-primary btn-big-circle tipster pull-right" id="back_review" title="<?= \Yii::t('app','weeks inbox!'); ?>">
      <i class="fa fa-3x fa-chevron-down"></i><br>
      <b><?= Dmpaper::getStatisticForInboxByDays(7)->one()->Inbox; ?> wk</b>
  </a>

  <a href="<?= Url::to(['/dms/dmpaper/index','timefilter'=>'month']); ?>" class="btn btn-success btn-big-circle tipster pull-right" id="back_review" title="<?= \Yii::t('app','months inbox!'); ?>">
      <i class="fa fa-3x fa-chevron-down"></i><br>
      <b><?= Dmpaper::getStatisticForInboxByDays(30)->one()->Inbox; ?> mth</b>
  </a>

  <div class="clearfix"></div>

  <div class="row">
    <div class="col-sm-12 col-md-6">
      <h3><?= \Yii::t('app','Choose Your Role'); ?></h3>
    </div>
    <div class="col-sm-12 col-md-6">
      <h3><?= \Yii::t('app','Statistics'); ?></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-6 col-md-3">
    <div class="thumbnail tile tile-double">
          <a href="<?= Url::to(['/dms/dmpaper/assistant']); ?>" class="fa fa-links">
              <h1>Assistant</h1>
              <i class="fa fa-3x fa-user"></i>
          </a>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
    <div class="thumbnail tile tile-double tile-orange">
          <a href="<?= Url::to(['/dms/dmpaper/reviewer']); ?>" class="fa fa-links">
              <h1>Reviewer</h1>
              <i class="fa fa-3x fa-user-md"></i>
          </a>
      </div>
    </div>
    <div class="col-sm-12 col-md-6">

        <?= Chart::widget(
            array(
              'clientOptions'=>array(
                'view'  => 'bar',
                'container' => 'InboxDayChart',
                'value' => '#Inbox#',
                'color' => "#E3E9F8",
                'border' => 1,
                'radius' => 1,
                'xAxis'=>array(
                  'title'=> Yii::t('app','Inbox by Day'),
                  'template'=> '#Day#',
                )
              ),      
              'options'=>array(
                'id'    => 'InboxDayChart',
                'style' => 'width:100%;height:150px;'
              ),
              'clientDataOptions'=>array(
                'type'=>'json',
                'url'=>Url::to(array('/dms/default/jsonbarchartinbox'))
              )   
            )
          );
        ?>

    </div>
  </div>

  <h2><?= \Yii::t('app','Browse Catalogue'); ?></h2>

  <div class="row">
    <div class="col-md-12">
      <?php Pjax::begin(); ?>
        <?php echo $this->render('@app/modules/dms/views/dmpaper/_search', ['model' => $searchModel]); ?>
        <?php echo ListView::widget([
          'dataProvider' => $dataProvider,
          'itemOptions' => ['class' => 'item'],
          'itemView' => '@app/modules/dms/views/dmpaper/iviews/_item_catalogue',
        ]); ?> 
      <?php Pjax::end(); ?>    
    </div>
  </div>

</div>
