<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var app\modules\workflow\models\WorkflowForm $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="workflow-search">

	<?php $form = ActiveForm::begin(array('method' => 'get')); ?>

		<?= $form->field($model, 'id'); ?>
		<?= $form->field($model, 'previous_user_id'); ?>
		<?= $form->field($model, 'next_user_id'); ?>
		<?= $form->field($model, 'module'); ?>
		<?= $form->field($model, 'wf_table'); ?>
		<?php // echo $form->field($model, 'wf_id'); ?>
		<?php // echo $form->field($model, 'status_from'); ?>
		<?php // echo $form->field($model, 'status_to'); ?>
		<?php // echo $form->field($model, 'actions_next'); ?>
		<?php // echo $form->field($model, 'date_create'); ?>
		<div class="form-group">
			<?= Html::submitButton('Search', array('class' => 'btn btn-primary')); ?>
			<?= Html::resetButton('Reset', array('class' => 'btn btn-default')); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
