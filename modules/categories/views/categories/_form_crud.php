<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\categories\models\Categories $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="categories-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'parent')->textInput() ?>

		<?= $form->field($model, 'cat_module')->textInput() ?>

		<?= $form->field($model, 'creator_id')->textInput() ?>

		<?= $form->field($model, 'time_deleted')->textInput() ?>

		<?= $form->field($model, 'time_create')->textInput() ?>

		<?= $form->field($model, 'name')->textInput(['maxlength' => 200]) ?>

		<?= $form->field($model, 'status')->textInput(['maxlength' => 200]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
