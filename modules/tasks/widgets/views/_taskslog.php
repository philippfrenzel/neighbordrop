<?php

use yii\helpers\Html;
use yii\widgets\ListView;


$initbookingJS = <<<INITBOOK
$('#PortletWorkflowLogTable').on('click','.table a.edit',function() {
	var th=$(this),
		id=th.attr('id').slice(0);		
	$('#applicationModal').modal('show');
	$('#applicationModal div.modal-header h4').html('You are going to edit task log #'+id);
	$('#applicationModal div.modal-body').load(th.attr('href'));
	return false;
});

INITBOOK;
$this->registerJs($initbookingJS);

?>

<div class="box bordered">
	<ul class="list-group">
		<?php 
			echo ListView::widget(array(
						'id'           => 'PortletWorkflowLogTable',
						'dataProvider' => $dpTasks,
						'itemView'     => 'iviews/_view',
						'layout'     => '<div class="box-header">{summary}</div>{items}{pager}',
					)
			);
		?>
	</ul>
</div>
