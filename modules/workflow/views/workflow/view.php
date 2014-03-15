<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\base\View $this
 * @var app\modules\workflow\models\Workflow $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = array('label' => 'Workflows', 'url' => array('index'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-view">

	<h1><?= Html::encode($this->title); ?></h1>

	<p>
		<?= Html::a('Update', array('update', 'id' => $model->id), array('class' => 'btn btn-danger')); ?>
		<?= Html::a('Delete', array('delete', 'id' => $model->id), array(
			'class' => 'btn btn-danger',
			'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
			'data-method' => 'post',
		)); ?>
	</p>

	<?= DetailView::widget(array(
		'model' => $model,
		'attributes' => array(
			'id',
			'previous_user_id',
			'next_user_id',
			'module',
			'wf_table',
			'wf_id',
			'status_from',
			'status_to',
			'actions_next',
			'date_create',
		),
	)); ?>

</div>
