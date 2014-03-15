<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\grid\GridView;
use yii\widgets\Block;

use app\modules\revision\widgets\PortletSidemenu;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\revision\models\RevisionForm $searchModel
 */

$this->title = 'Revisions';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 

	Block::begin(array('id'=>'sidebar'));
		
		$sideMenu = array();
		$sideMenu[] = array('decoration'=>'sticker sticker-color-yellow','icon'=>'icon-arrow-left','label'=>Yii::t('app','Home'),'link'=>Url::to(array('/site/index')));
		$sideMenu[] = array('decoration'=>'sticker sticker-color-green','icon'=>'icon-plus','label'=>Yii::t('app','New Revision Log'),'link'=>Url::to(array('/revision/revision/create')));

		echo PortletSidemenu::widget(array(
			'sideMenu'=>$sideMenu,
			'enableAdmin' => false,
		)); 

?>

<?php Block::end(); ?>

<div class="revision-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create Revision', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'id',
			'content:ntext',
			'status',
			'creator_id',
			'time_create:datetime',
			// 'revision_table',
			// 'revision_id',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

</div>
