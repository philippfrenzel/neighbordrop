<?php
$this->params['breadcrumbs']=array(
	array(
		'label'=>'Comments',
		'url'=>array('index')
	),
	'Update Comment #'.$model->id,
);
?>

<h1>Update Comment #<?= $model->id; ?></h1>

<?= $this->context->renderPartial('_form', array('model'=>$model)); ?>