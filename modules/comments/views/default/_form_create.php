<?php

use \Yii;

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;
use \yii\web\JsExpression;

?>

<?php
$form = ActiveForm::begin([
  'id' => 'CommentCreateForm'
]); ?>

  <?php if(Yii::$app->user->isGuest): ?>

    <?= $form->field($model, 'anonymous')->textInput(); ?>

  <?php endif; ?>

	<?= $form->field($model,'content')->textArea(array('rows'=>4, 'cols'=>40)); ?>
  
  <?= Html::activeHiddenInput($model,'comment_table',['value'=>$model->comment_table]); ?>
  
  <?= Html::activeHiddenInput($model,'comment_id',['value'=>$model->comment_id]); ?>
	
	<div class="form-actions">
    <?= Html::submitButton('<i class="icon-pencil"></i> '.Yii::t('app','add'), array('class' => 'btn btn-success fg-color-white','id'=>'submit_btn')); ?>		
		<?php //Html::a(\Yii::t('app','add'),['/comments/default/create'],array('class' => 'btn btn-success fg-color-white','id'=>'submitCommentCreate')); ?>
	</div>

<?php 
ActiveForm::end();
?>
