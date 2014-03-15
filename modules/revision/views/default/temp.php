<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var app\modules\revision\models\Revision $model
 * @var ActiveForm $form
 */
?>
<div class="temp">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'content'); ?>
		<?= $form->field($model, 'revision_table'); ?>
		<?= $form->field($model, 'revision_id'); ?>
		<?= $form->field($model, 'creator_id'); ?>
		<?= $form->field($model, 'status'); ?>
	
		<div class="form-group">
			<?= Html::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
		</div>
	<?php ActiveForm::end(); ?>

</div><!-- temp -->
