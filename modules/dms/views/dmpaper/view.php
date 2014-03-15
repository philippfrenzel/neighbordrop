<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\modules\dms\models\Dmpaper $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Dmpapers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dmpaper-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
			'party_id',
			'description:ntext',
			'name',
			'status',
			'creator_id',
			'time_deleted:datetime',
			'time_create:datetime',
			'tags:ntext',
		],
	]); ?>

</div>
