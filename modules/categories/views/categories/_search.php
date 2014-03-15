<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\categories\models\CategoriesSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="categories-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'parent') ?>

		<?= $form->field($model, 'name') ?>

		<?= $form->field($model, 'status') ?>

		<?= $form->field($model, 'cat_module') ?>

		<?php // echo $form->field($model, 'creator_id') ?>

		<?php // echo $form->field($model, 'time_deleted') ?>

		<?php // echo $form->field($model, 'time_create') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
