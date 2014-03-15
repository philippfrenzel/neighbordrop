<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;

/**
 * @var yii\base\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\pages\models\PageForm $searchModel
 */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-wsp">

	<h1><?= Html::encode($this->title); ?></h1>

	<?php //echo $this->render('_search', array('model' => $searchModel)); ?>

	<hr>

	<div>
		<?= Html::a('Create Page', array('create'), array('class' => 'btn btn-danger')); ?>
	</div>

	<?= GridView::widget(array(
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => array(
			'id',
			'name:ntext',
			//'body:html',
			//'parent_pages_id',
			'ord',
			// 'time_create:datetime',
			 'time_update:datetime',
			// 'special',
			 'title:ntext',
			 'template:ntext',
			// 'category',
			// 'tags:ntext',
			// 'description:ntext',
			// 'date_associated',
			// 'vars:ntext',
			// 'status',
			['class' => 'yii\grid\ActionColumn'],
		),
	)); ?>

</div>
