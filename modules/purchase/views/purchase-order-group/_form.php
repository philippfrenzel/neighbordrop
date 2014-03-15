<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderGroup $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="purchase-order-group-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'contact_id')->textInput() ?>

		<?= $form->field($model, 'purchaseorder_id')->textInput() ?>

		<?= $form->field($model, 'creator_id')->textInput() ?>

		<?= $form->field($model, 'time_deleted')->textInput() ?>

		<?= $form->field($model, 'time_create')->textInput() ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
