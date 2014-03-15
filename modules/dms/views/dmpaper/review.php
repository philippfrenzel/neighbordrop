<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\DetailView;
use app\modules\dms\models\Dmsys;

/**
 * @var yii\web\View $this
 * @var app\modules\dms\models\Dmpaper $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Dmpapers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dmpaper-view">

	<a href="<?= Url::to(['/dms/dmpaper/reviewer']); ?>" class="btn btn-success btn-circle tipster" id="back_review" title="<?= \Yii::t('app','Back to overview!'); ?>">
      <i class="fa fa-2x fa-angle-double-left"></i>
  </a>

  <a href="<?= Url::to(['/dms/dmpaper/submit','id'=>$model['id']]); ?>" class="btn btn-primary btn-circle pull-right tipster" id="submit_review" title="<?= \Yii::t('app','Submit as done!'); ?>">
      <i class="fa fa-2x fa-check-square"></i>
  </a>

	<h1>DocId #<?= $model['id']; ?> <?= $model['name']; ?> (<?= $model['documenttype']; ?>)</h1>

	<div class="row">
		<div class="col-md-6">
			<iframe id="viewer" src = "./js/Viewer.js/#<?= \Yii::$app->urlManager->createUrl('/dms/default/getlatestattachement',['id'=>$model['id'],'module'=>Dmsys::MODULE_DMPAPER,'ext'=>'.pdf']); ?>" width='100%' height='700' allowfullscreen webkitallowfullscreen></iframe>
		</div>
		<div class="col-md-3">
			<?php 
			  if(class_exists('\app\modules\tasks\widgets\PortletTasks')){
			    echo \app\modules\tasks\widgets\PortletTasks::widget(array(
			      'module'=>\app\modules\workflow\models\Workflow::MODULE_DMPAPER,
			      'id'=>$model->id,
			      'title' => \Yii::t('app','Tasks')
			    )); 
			  }
			?>
		</div>
		<div class="col-md-3">
			<?php 
			  if(class_exists('\app\modules\comments\widgets\PortletComments')){
			    echo \app\modules\comments\widgets\PortletComments::widget(array(
			      'module'=>\app\modules\workflow\models\Workflow::MODULE_DMPAPER,
			      'id'=>$model->id,
			      'title' => \Yii::t('app','Comments')
			    )); 
			  }
			?>
		</div>
	</div>

</div>
