<?php

use yii\helpers\Html;

/**
 * @var yii\base\View $this
 * @var app\modules\comments\models\Comment $model
 */

$this->title = 'Update Comment: ' . $model->id;
$this->params['breadcrumbs'][] = array('label' => 'Comments', 'url' => array('index'));
$this->params['breadcrumbs'][] = array('label' => $model->id, 'url' => array('view', 'id' => $model->id));
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="module-wsp">

	<h1><?= Html::encode($this->title); ?></h1>

	<?= $this->render('_form', array(
		'model' => $model,
	)); ?>

</div>
