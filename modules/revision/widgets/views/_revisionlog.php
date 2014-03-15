<?php

use \yii\helpers\Html;
use \yii\grid\GridView;
use \yii\grid\DataColumn;


$initbookingJS = <<<INITBOOK
$('#PortletRevisionLogTable').on('click','.table a.edit',function() {
	var th=$(this),
		id=th.attr('id').slice(0);		
	$('#applicationModal').modal('show');
	$('#applicationModal div.modal-header h4').html('You are going to edit revision log #'+id);
	$('#applicationModal div.modal-body').load(th.attr('href'));
	return false;
});

INITBOOK;
$this->registerJs($initbookingJS);

?>

<?php 
	echo GridView::widget(array(
		'id' => 'PortletRevisionLogTable',
		'dataProvider'=>$dpRevision,
		'columns'=>array(
			array(
				'class' => DataColumn::className(),
				'attribute' => 'content',
				'format' => 'text',
			),
			array(
				'class' => DataColumn::className(),
				'content'=>function($data, $row) {
					$html = Html::a(NULL, array("update", "id"=>$data->id), array('class' => 'edit icon icon-edit', "id"=>$data->id));
					//$html .= ' | ';
					//$html .= Html::a(NULL, array("delete", "id"=>$data->id), array('class'=>'delete icon icon-trash'));
					return $html;
				}
			),
		),
	));
?>
