<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\Party $model
 */

$this->title = 'Create Party';
$this->params['breadcrumbs'][] = ['label' => 'Parties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="party-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
