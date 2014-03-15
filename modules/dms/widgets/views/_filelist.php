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

<blockquote>
  <small><?= \Yii::t('app','Here you can attach/download files to/from the record') ?></small>
</blockquote>


<?php

	echo $this->render('_upload_form', [
		'model' => $model,
	]);

?>

<div class="box bordered">
	<ul class="media-list">
		<?php 
			echo ListView::widget(array(
						'id'           => 'PortletWorkflowLogTable',
						'dataProvider' => $dpFiles,
						'itemView'     => 'iviews/_view',
						'layout'     	 => '<div class="box-header">{summary}</div>{items}{pager}',
					)
			);
		?>
	</ul>
</div>
