<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\PartySearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="party-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'organisationName') ?>

		<?= $form->field($model, 'partyNote') ?>

		<?= $form->field($model, 'taxNumber') ?>

		<?= $form->field($model, 'registrationNumber') ?>

		<?php // echo $form->field($model, 'registrationCountryCode') ?>

		<?php // echo $form->field($model, 'party_type') ?>

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
