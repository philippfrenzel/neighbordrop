<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\base\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\comments\models\CommentSearch $searchModel
 */

$this->title = 'Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-wsp">

	<h1><?= Html::encode($this->title); ?></h1>

	<?php // echo $this->render('_search', array('model' => $searchModel)); ?>

	<p>
		<?= Html::a('Create Comment', array('create'), array('class' => 'btn btn-danger')); ?>
	</p>

	<?= GridView::widget(array(
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => array(
			array('class' => 'yii\grid\SerialColumn'),

			'id',
			'content:ntext',
			'status',
			'author_id',
			'time_create:datetime',
			// 'comment_table',
			// 'comment_id',

			array('class' => 'yii\grid\ActionColumn'),
		),
	)); ?>

</div>
