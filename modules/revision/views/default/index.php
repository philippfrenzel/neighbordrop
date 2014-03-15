<?php

use \yii\widgets\Block;
use \yii\helpers\Html;
use yii\helpers\Url;

use \yii\grid\GridView;

use app\modules\revision\widgets\PortletSidemenu;
use app\modules\revision\widgets\PortletRevisionLog;

?>

<?php 

	Block::begin(array('id'=>'sidebar'));
		
		$sideMenu = array();
		$sideMenu[] = array('decoration'=>'sticker sticker-color-yellow','icon'=>'icon-arrow-left','label'=>Yii::t('app','Home'),'link'=>Url::to(array('/site/index')));
		$sideMenu[] = array('decoration'=>'sticker sticker-color-green','icon'=>'icon-plus','label'=>Yii::t('app','New Revision Log'),'link'=>Url::to(array('/revision/revision/create')));

		echo PortletSidemenu::widget(array(
			'sideMenu'=>$sideMenu,
		)); 

?>

	<?= PortletRevisionLog::widget(array(
      		'module'=>1,
      		'id'=>1,
	)); ?>

<?php Block::end(); ?>


<div id="page" class="task-default-index">

<h3>Revision Log</h3>

<?= GridView::widget(array(
	'dataProvider' => $dpRevision,
	'columns' => array(
		array('class' => 'yii\grid\SerialColumn'),
		'id',
		'content:ntext',
		'status',
		'creator_id',
		'time_create:datetime',
		// 'revision_table',
		// 'revision_id',

		array('class' => 'yii\grid\ActionColumn'),
	),
)); ?>

</div>