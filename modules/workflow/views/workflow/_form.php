<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var app\modules\workflow\models\Workflow $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="workflow-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'previous_user_id')->textInput(); ?>

		<?= $form->field($model, 'date_create')->textInput(); ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', array('class' => 'btn btn-primary')); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
