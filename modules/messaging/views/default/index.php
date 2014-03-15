<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/**
 * @var yii\base\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\messaging\models\MessagesSearch $searchModel
 */

$this->title = 'Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-index">

	<h1><?= Html::encode($this->title); ?></h1>

	<?php//= $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create Messages', ['create'], ['class' => 'btn btn-success']); ?>
	</p>

	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => 'iviews/_view',
		'layout'     => '<div class="box-header">{summary}</div>{items}{pager}',
	]); 

	/*
	function ($model, $key, $index, $widget) {
		return Html::a(Html::encode($model->id), ['view', 'id' => $model->id]);
	},
	*/
	?>

</div>
