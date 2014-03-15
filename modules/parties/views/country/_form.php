<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\Country $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="country-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'country_code')->textInput(['maxlength' => 2]) ?>

		<?= $form->field($model, 'country_name')->textInput(['maxlength' => 100]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
