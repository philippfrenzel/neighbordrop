<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\icons\Icon;
use yii\widgets\Pjax;

$this->registerAssetBundle('yii\widgets\ActiveFormAsset');
$this->registerAssetBundle('yii\validators\ValidationAsset');
$this->registerAssetBundle('kartik\widgets\Select2Asset');

if (\Yii::$app->request->isAjax) {
  $this->assetBundles['yii\bootstrap\BootstrapAsset'] = new yii\web\AssetBundle;
  $this->assetBundles['app\assets\AppAsset'] = new yii\web\AssetBundle;
}

$script = <<<SKRIPT

$(document).on('submit', '#TaskCreateForm', function(event) {
  $.pjax.submit(event, '#PtlTasksPjax')
})

SKRIPT;

$this->registerJs($script);

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

<?php
Pjax::begin(['id'=>'PtlTasksPjax']);

  echo Html::a('<span class="btn btn-success btn-xs bg-color-green fg-color-white tipster" title="new Task">'.Icon::show('plus', ['class'=>'fa'], Icon::FA).' add</span>', array("/tasks/default/create", "id"=>$id,'module'=>$module), array('class' => 'create'));
	echo ListView::widget(array(
		'id' => 'PortletTasksTable',
		'dataProvider'=>$dpTasks,
		'itemView' => '@app/modules/tasks/widgets/views/iviews/_view',
		'layout' => '{items}',
		)
	);

Pjax::end();
?>

