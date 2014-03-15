<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\revision\models\Revision $model
 */

$this->title = 'Create Revision';
$this->params['breadcrumbs'][] = ['label' => 'Revisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="revision-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
