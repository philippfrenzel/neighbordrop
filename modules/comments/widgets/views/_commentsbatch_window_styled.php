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
	$('#applicationModal div.modal-header h4').html('Kommentare');
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

<div class="fncybtn bg_cream tipster pull-right" title="add comment">
	<?= Html::a(Icon::show('comments', ['class'=>'fa fa-2x'], Icon::FA), array("/comments/default/createwindow", "id"=>$id,'module'=>$module), array('class' => 'create c_pink', "id"=>$uniqueIdCREATE));?>
</div>

<?php 
	if($enableCommentsLog)
		echo Html::a($countComments.' COMMENTS', array("/comments/default/view", "id"=>$id,'module'=>$module), array('class' => 'view c_gray pull-right', "id"=>$uniqueIdEDIT)).'&nbsp;';
?>

<div class="clearfix"></div>

