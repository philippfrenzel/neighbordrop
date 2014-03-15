<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\dms\models\Dmpaper $model
 */

$this->title = 'Create Dmpaper';
$this->params['breadcrumbs'][] = ['label' => 'Dmpapers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dmpaper-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
