<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var app\modules\messaging\models\Messages $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="messages-form">

	<?php $form = ActiveForm::begin(); ?>

		<?=$form->field($model, 'sender_id')->textInput(); ?>

		<?=$form->field($model, 'reciever_id')->textInput(); ?>

		<?=$form->field($model, 'body')->textarea(['rows' => 6]); ?>

		<?=$form->field($model, 'deleted_by')->textInput(); ?>

		<?=$form->field($model, 'is_read')->checkbox(); ?>

		<?=$form->field($model, 'date_create')->textInput(); ?>

		<?=$form->field($model, 'subject')->textInput(['maxlength' => 255]); ?>

		<?=$form->field($model, 'module')->textInput(['maxlength' => 50]); ?>

		<div class="form-group">
			<?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
