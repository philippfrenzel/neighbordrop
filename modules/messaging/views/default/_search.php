<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\base\View $this
 * @var app\modules\messaging\models\MessagesSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="messages-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?=$form->field($model, 'id'); ?>
		<?=$form->field($model, 'sender_id'); ?>
		<?=$form->field($model, 'reciever_id'); ?>
		<?=$form->field($model, 'subject'); ?>
		<?=$form->field($model, 'body'); ?>
		<?php // echo $form->field($model, 'is_read')->checkbox(); ?>
		<?php // echo $form->field($model, 'deleted_by'); ?>
		<?php // echo $form->field($model, 'date_create'); ?>
		<?php // echo $form->field($model, 'module'); ?>
		<div class="form-group">
			<?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
