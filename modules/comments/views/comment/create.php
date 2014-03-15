<?php

use yii\helpers\Html;

/**
 * @var yii\base\View $this
 * @var app\modules\comments\models\Comment $model
 */

$this->title = 'Create Comment';
$this->params['breadcrumbs'][] = array('label' => 'Comments', 'url' => array('index'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-wsp">

	<h1><?= Html::encode($this->title); ?></h1>

	<?= $this->render('_form', array(
		'model' => $model,
	)); ?>

</div>
