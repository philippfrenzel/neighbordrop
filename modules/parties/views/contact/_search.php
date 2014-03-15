<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\ContactSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="contact-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'party_id') ?>

		<?= $form->field($model, 'contactName') ?>

		<?= $form->field($model, 'department') ?>

		<?= $form->field($model, 'email') ?>

		<?php // echo $form->field($model, 'phone') ?>

		<?php // echo $form->field($model, 'mobile') ?>

		<?php // echo $form->field($model, 'fax') ?>

		<?php // echo $form->field($model, 'user_id') ?>

		<?php // echo $form->field($model, 'system_key') ?>

		<?php // echo $form->field($model, 'system_name') ?>

		<?php // echo $form->field($model, 'system_upate') ?>

		<?php // echo $form->field($model, 'creator_id') ?>

		<?php // echo $form->field($model, 'time_deleted') ?>

		<?php // echo $form->field($model, 'time_create') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
