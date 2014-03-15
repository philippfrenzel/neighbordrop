<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\posts\models\PostForm $searchModel
 */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php yii\widgets\Block::begin(array('id'=>'sidebar')); ?>

	<?php 

	$sideMenu = array();
	$sideMenu[] = array('decoration'=>'sticker sticker-color-yellow','icon'=>'icon-arrow-left','label'=>Yii::t('app','Home'),'link'=>Url::to(array('/site/index')));
	$sideMenu[] = array('decoration'=>'sticker sticker-color-green','icon'=>'icon-plus','label'=>Yii::t('app','New Post'),'link'=>Url::to(array('/posts/post/create')));
 

	echo app\modules\posts\widgets\PortletSidemenu::widget(array(
		'sideMenu'=>$sideMenu,
		'enableAdmin'=>false,
		'htmlOptions'=>array('class'=>'nostyler'),
  )); ?>   

<?php yii\widgets\Block::end(); ?>


<div class="post-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'time_create:datetime',
			//'id',
			'title',
			'content:html',
			'tags:ntext',
			'status',
			// 'author_id',
			// 
			// 'time_update:datetime',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

</div>
