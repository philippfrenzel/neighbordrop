<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\article\models\Price $model
 * @var ActiveForm $form
 */
?>
<div class="price-_form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'article_id') ?>
		<?= $form->field($model, 'creator_id') ?>
		<?= $form->field($model, 'time_deleted') ?>
		<?= $form->field($model, 'time_create') ?>
		<?= $form->field($model, 'price') ?>
		<?= $form->field($model, 'status') ?>
	
		<div class="form-group">
			<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
		</div>
	<?php ActiveForm::end(); ?>

</div><!-- price-_form -->
