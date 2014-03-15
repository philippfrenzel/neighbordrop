<?php

use yii\helpers\Html;

/**
 * @var yii\base\View $this
 * @var app\modules\messaging\models\Messages $model
 */

$this->title = 'Create Messages';
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-create">

	<h1><?php echo Html::encode($this->title); ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
