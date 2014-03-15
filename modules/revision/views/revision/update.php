<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\revision\models\Revision $model
 */

$this->title = 'Update Revision: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Revisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="revision-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
