<?php

use yii\helpers\Html;

/**
 * @var yii\base\View $this
 * @var app\modules\workflow\models\Workflow $model
 */

$this->title = 'Update Workflow: ' . $model->id;
$this->params['breadcrumbs'][] = array('label' => 'Workflows', 'url' => array('index'));
$this->params['breadcrumbs'][] = array('label' => $model->id, 'url' => array('view', 'id' => $model->id));
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="workflow-update">

	<h1><?= Html::encode($this->title); ?></h1>

	<?= $this->render('_form', array(
		'model' => $model,
	)); ?>

</div>
