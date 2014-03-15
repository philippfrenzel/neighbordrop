<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ListView;

use yii\widgets\Block;

/**
 * @var yii\base\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\pages\models\PageForm $searchModel
 */

$this->title = 'ToDos';
$this->params['breadcrumbs'][] = $this->title;

$deleteJS = <<<DEL
$('.post-box').on('click','.op a.delete',function() {
    var th=$(this),
    container=th.closest('div.post-box'),
    id=container.attr('id').slice(1);
  if(confirm('Are you sure you want to delete comment #'+id+'?')) {
    $.ajax({
      url:th.attr('href'),
      data:{
        'ajax':1,
        'id':id
      },
      type:'POST'
    }).done(function(){container.slideUp()});
  }
  return false;
});

DEL;
$this->registerJs($deleteJS);
?>

<?php Block::begin(array('id'=>'sidebar')); ?>

	<?php 

	$sideMenu = array();
	$sideMenu[] = array('decoration'=>'sticker sticker-color-yellow','icon'=>'fa fa-home','label'=>Yii::t('app','Home'),'link'=>Url::to(array('/site/index')));
	$sideMenu[] = array('icon'=>'fa fa-plus','label'=>Yii::t('app','new Todo'),'link'=>Url::to(array('/tasks/task/create')));
	$sideMenu[] = array('decoration'=>'sticker sticker-color-green','icon'=>'fa fa-icon-list-alt','label'=>Yii::t('app','Overview'),'link'=>Url::to(array('/tasks/default/index')));

	echo \app\modules\tasks\widgets\PortletToolbox::widget(array(
		'menuItems'=>$sideMenu,
		'enableAdmin'=>false,
	)); ?>	 
	
<?php Block::end(); ?>

<div class="module-wsp">

	<h2><?= Html::encode($this->title); ?></h2>

	<?php //echo $this->render('_search', array('model' => $searchModel)); ?>

	<hr>
	<?php 
		echo ListView::widget(array(
					'id'           => 'IndexTaskView',
					'dataProvider' => $dpTasks,
					'itemView'     => 'iviews/_view',
					'layout'     	 => '<div class="box-header">{summary}</div>{items}{pager}',
				)
		);
	?>

</div>


<?php Block::begin(array('id'=>'toolbar')); ?>

  <h4>
    <i class="icon-hand-down"></i>
    Hilfe
  </h4>
 
	<p>
		Hier sehen Sie eine Liste der Ihnen zugeordneten oder der durch Sie erstellten Aufgaben. Durch anklicken auf "anzeigen" kommen Sie zu den 
		jeweiligen Details.
	</p>

<?php Block::end(); ?>