<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var app\modules\comments\models\Comment $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="comment-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'content')->textarea(array('rows' => 6)); ?>

		<?= $form->field($model, 'comment_table')->textInput(); ?>

		<?= $form->field($model, 'comment_id')->textInput(); ?>

		<?= $form->field($model, 'author_id')->textInput(); ?>

		<?= $form->field($model, 'time_create')->textInput(); ?>

		<?= $form->field($model, 'status')->textInput(array('maxlength' => 255)); ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', array('class' => 'btn btn-primary')); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
