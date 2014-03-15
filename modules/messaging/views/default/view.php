<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\base\View $this
 * @var app\modules\messaging\models\Messages $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-view">

	<h1><?php echo Html::encode($this->title); ?></h1>

	<p>
		<?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
		<?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
			'data-method' => 'post',
		]); ?>
	</p>

	<?php echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'sender_id',
			'reciever_id',
			'subject',
			'body:ntext',
			'is_read:boolean',
			'deleted_by',
			'date_create',
			'module',
		],
	]); ?>

</div>
