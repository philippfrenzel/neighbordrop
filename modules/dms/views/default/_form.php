<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\dms\models\Dmsys $model
 * @var ActiveForm $form
 */
?>
<div class="dmsys-_form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'fileattachement') ?>
		<?= $form->field($model, 'parent') ?>
		<?= $form->field($model, 'owner_id') ?>
		<?= $form->field($model, 'source_security') ?>
		<?= $form->field($model, 'time_expired') ?>
		<?= $form->field($model, 'creator_id') ?>
		<?= $form->field($model, 'time_deleted') ?>
		<?= $form->field($model, 'time_create') ?>
		<?= $form->field($model, 'source_path') ?>
		<?= $form->field($model, 'used_space') ?>
		<?= $form->field($model, 'status') ?>
	
		<div class="form-group">
			<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
		</div>
	<?php ActiveForm::end(); ?>

</div><!-- dmsys-_form -->
