<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderLineSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="purchase-order-line-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'purchaseordergroup_id') ?>

		<?= $form->field($model, 'order_amount') ?>

		<?= $form->field($model, 'order_price') ?>

		<?= $form->field($model, 'article_id') ?>

		<?php // echo $form->field($model, 'status') ?>

		<?php // echo $form->field($model, 'creator_id') ?>

		<?php // echo $form->field($model, 'time_deleted') ?>

		<?php // echo $form->field($model, 'time_create') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
