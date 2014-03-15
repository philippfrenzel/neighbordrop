<?php

use \yii\helpers\Html;
use \yii\grid\GridView;
use \yii\grid\DataColumn;

$uniqueIdEDIT = 'TaskLogBatch'.$module.'_'.$id.'EDIT';
$uniqueIdCREATE = 'TaskLogBatch'.$module.'_'.$id.'CREATE';

$initbookingJS = <<<INITBOOK
$('#$uniqueIdEDIT').on('click',function() {
	var th=$(this),
		id=th.attr('id').slice(0);		
	$('#applicationModal').modal('show');
	$('#applicationModal div.modal-header h4').html('show Tasks');
	$('#applicationModal div.modal-body').load(th.attr('href'));
	return false;
});

$('#$uniqueIdCREATE').on('click',function() {
	var th=$(this),
		id=th.attr('id').slice(0);		
	$('#applicationModal').modal('show');
	$('#applicationModal div.modal-header h4').html('new Task');
	$('#applicationModal div.modal-body').load(th.attr('href'));
	return false;
});

INITBOOK;
$this->registerJs($initbookingJS);

?>

<?php 
	echo Html::a('<span class="btn btn-success btn-xs bg-color-green fg-color-white tipster" title="neue Aufgabe"><i class="icon-plus-sign-alt"></i> add</span>', array("/tasks/default/createwindow", "id"=>$id,'module'=>$module), array('class' => 'create', "id"=>$uniqueIdCREATE));
	echo ' ';
	echo Html::a('<span class="btn btn-info btn-xs tipster" title="# Aufgaben Logs"><i class="icon-list"></i> <span class="batch">'.$countTasks.'</span></span>', array("/tasks/default/viewwindow", "id"=>$id,'module'=>$module), array('class' => 'view', "id"=>$uniqueIdEDIT));
?>

