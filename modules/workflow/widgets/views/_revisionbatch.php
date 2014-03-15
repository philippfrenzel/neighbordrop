<?php

use \yii\helpers\Html;
use \yii\grid\GridView;
use \yii\grid\DataColumn;

$uniqueIdEDIT = 'RevLogBatch'.$module.'_'.$id.'EDIT';
$uniqueIdCREATE = 'RevLogBatch'.$module.'_'.$id.'CREATE';

$initbookingJS = <<<INITBOOK
$('#$uniqueIdEDIT').on('click',function() {
	var th=$(this),
		id=th.attr('id').slice(0);		
	$('#applicationModal').modal('show');
	$('#applicationModal div.modal-header h4').html('You are going to show '+id);
	$('#applicationModal div.modal-body').load(th.attr('href'));
	return false;
});

$('#$uniqueIdCREATE').on('click',function() {
	var th=$(this),
		id=th.attr('id').slice(0);		
	$('#applicationModal').modal('show');
	$('#applicationModal div.modal-header h4').html('New Revision Log');
	$('#applicationModal div.modal-body').load(th.attr('href'));
	return false;
});

INITBOOK;
$this->registerJs($initbookingJS);

?>

<?php 
	echo Html::a('<span class="label label-success bg-color-green fg-color-white tipster" title="neue Revision"><i class="icon-plus-sign-alt"> add</i></span>', array("/revision/default/create", "id"=>$id,'module'=>$module), array('class' => 'create', "id"=>$uniqueIdCREATE));
	echo ' ';
	echo Html::a('<span class="label label-info tipster" title="# Revision Logs"><i class="icon-list"> '.$countRevision.'</i></span>', array("/revision/default/view", "id"=>$id,'module'=>$module), array('class' => 'view', "id"=>$uniqueIdEDIT));
?>

