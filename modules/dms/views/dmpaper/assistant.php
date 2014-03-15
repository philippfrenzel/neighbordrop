<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ListView;
use kartik\icons\Icon;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\dms\models\DmpaperSearch $searchModel
 */

$this->title = 'Dmpapers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dmpaper-index">

  <a href="<?= Url::to(['/dms/dmpaper/index']); ?>" class="btn btn-success btn-circle">
      <i class="fa fa-2x fa-home"></i>
  </a>

	<h1><?= Html::encode($this->title) ?></h1>

  <div class="row">
    <div class="col-md-10">
      <?php echo $this->render('@app/modules/dms/views/dmpaper/_search', ['model' => $searchModel]); ?>
      <?php echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '@app/modules/dms/views/dmpaper/iviews/_item',
      ]); ?>
    </div>
    <div class="col-md-2">
      <div class="panel panel-warning">
        <div class="panel-heading">
          <?= \Yii::t('app','Actions'); ?>
        </div>
        <div class="panel-body">
          <a href="<?= Url::to(['/dms/dmpaper/create']); ?>" class="btn btn-success">
            <?= Icon::show('plus', ['class'=>'fa'], Icon::FA);?><?= Yii::t('app','Register Paper'); ?>                
          </a>
        </div>
      </div>      
    </div>
  </div>

</div>
