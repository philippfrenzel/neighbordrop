<?php
use \yii\helpers\Html;
use yii\helpers\Url;

use \yii\widgets\ActiveForm;
use \yii\web\JsExpression;

?>

<?php $form = ActiveForm::begin(array(
  'action' => Url::to(['/comments/default/createwindow']),
	'options' => array('class' => 'form-comment'),
  'ajaxVar' => new JsExpression("function ($form) { return false; }")
)); ?>

  <?php if(Yii::$app->user->isGuest): ?>

    <?= $form->field($model, 'anonymous')->textInput(); ?>

  <?php endif; ?>

	<?= $form->field($model,'content')->textArea(array('rows'=>4, 'cols'=>40)); ?>
  
  <?= Html::activeHiddenInput($model,'comment_table',['value'=>$model->comment_table]); ?>
  
  <?= Html::activeHiddenInput($model,'comment_id',['value'=>$model->comment_id]); ?>
	
	<div class="form-actions">		
		<?= Html::submitButton('<i class="icon-pencil"></i> '.Yii::t('app','Save'), array('class' => 'btn btn-success fg-color-white')); ?>
	</div>

<?php ActiveForm::end(); ?>
