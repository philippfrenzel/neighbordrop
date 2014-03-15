<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;

use kartik\icons\Icon;

$uniqueIdEDIT = 'CommentsLogBatch'.$module.'_'.$id.'EDIT';
$uniqueIdCREATE = 'CommentsLogBatch'.$module.'_'.$id.'CREATE';

$initbookingJS = <<<INITBOOK
$('#$uniqueIdEDIT').on('click',function() {
	var th=$(this),
		id=th.attr('id').slice(0);		
	$('#applicationModal').modal('show');
	$('#applicationModal div.modal-header h4').html('Kommentare für '+id);
	$('#applicationModal div.modal-body').load(th.attr('href'));
	return false;
});

$('#$uniqueIdCREATE').on('click',function() {
	var th=$(this),
		id=th.attr('id').slice(0);		
	$('#applicationModal').modal('show');
	$('#applicationModal div.modal-header h4').html('Neuer Kommentar');
	$('#applicationModal div.modal-body').load(th.attr('href'));
	return false;
});

INITBOOK;
$this->registerJs($initbookingJS);

?>

<?php 
	if($enableCommentsLog)
		echo Html::a('<span class="btn btn-info btn-xs tipster" title="# '.$countComments.' Kommentare">'.Icon::show('comment', ['class'=>'fa'], Icon::FA).$countComments.'</span>', array("/comments/default/view", "id"=>$id,'module'=>$module), array('class' => 'view', "id"=>$uniqueIdEDIT)).'&nbsp;';
	
	echo Html::a('<span class="btn btn-success btn-xs bg-color-green fg-color-white tipster" title="neuer Kommentar">'.Icon::show('plus', ['class'=>'fa'], Icon::FA).' add</span>', array("/comments/default/createwindow", "id"=>$id,'module'=>$module), array('class' => 'create', "id"=>$uniqueIdCREATE));	
?>

