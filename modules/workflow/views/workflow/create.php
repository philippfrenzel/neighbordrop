<?php

use yii\helpers\Html;

/**
 * @var yii\base\View $this
 * @var app\modules\workflow\models\Workflow $model
 */

$this->title = 'Create Workflow';
$this->params['breadcrumbs'][] = array('label' => 'Workflows', 'url' => array('index'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-create">

	<h1><?= Html::encode($this->title); ?></h1>

	<?= $this->render('_form', array(
		'model' => $model,
	)); ?>

</div>
