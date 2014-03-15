<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrder $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="purchase-order-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'party_id')->textInput() ?>

		<?= $form->field($model, 'creator_id')->textInput() ?>

		<?= $form->field($model, 'time_deleted')->textInput() ?>

		<?= $form->field($model, 'time_create')->textInput() ?>

		<?= $form->field($model, 'order_number')->textInput(['maxlength' => 200]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
