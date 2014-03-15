<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\base\View $this
 * @var app\modules\comments\models\Comment $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = array('label' => 'Comments', 'url' => array('index'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-wsp">

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
			'content:ntext',
			'status',
			'author_id',
			'time_create:datetime',
			'comment_table',
			'comment_id',
		),
	)); ?>

</div>
