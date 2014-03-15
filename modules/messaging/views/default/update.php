<?php

use yii\helpers\Html;

/**
 * @var yii\base\View $this
 * @var app\modules\messaging\models\Messages $model
 */

$this->title = 'Update Messages: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="messages-update">

	<h1><?php echo Html::encode($this->title); ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
