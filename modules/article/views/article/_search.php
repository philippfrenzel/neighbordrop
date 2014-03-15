<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\article\models\ArticleSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="article-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'article') ?>

		<?= $form->field($model, 'article_number') ?>

		<?= $form->field($model, 'status') ?>

		<?= $form->field($model, 'system_key') ?>

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
