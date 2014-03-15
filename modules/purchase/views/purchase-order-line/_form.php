<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderLine $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="purchase-order-line-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'purchaseordergroup_id')->textInput() ?>

		<?= $form->field($model, 'creator_id')->textInput() ?>

		<?= $form->field($model, 'article_id')->textInput() ?>
		
		<?= $form->field($model, 'party_id')->textInput() ?>
		
		<?= $form->field($model, 'date_delivery')->textInput() ?>

		<?= $form->field($model, 'time_deleted')->textInput() ?>

		<?= $form->field($model, 'time_create')->textInput() ?>

		<?= $form->field($model, 'order_amount')->textInput() ?>

		<?= $form->field($model, 'order_price')->textInput() ?>

		<?= $form->field($model, 'status')->textInput(['maxlength' => 255]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
