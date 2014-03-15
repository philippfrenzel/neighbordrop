<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

use app\modules\workflow\models\Workflow;

$this->assetBundles['yii\web\YiiAsset'] = new yii\web\AssetBundle;;
$this->assetBundles['yii\web\JqueryAsset'] = new yii\web\AssetBundle;;

$uniqueIdEDIT = 'CommentsLogBatch'.$model->comment_table.'_'.$model->comment_id.'EDIT';

$formsubmitJS = <<<FORMJS
$('#comment-form-update').submit(function(){
	$(':submit', this).click(function() {
		var data = $("#comment-form-update").serialize();
		$.ajax({
	   		type: 'POST',
	   		url: '$requestUrl',
	   		data: data,
			success: function(data){
				$('#applicationModal').modal('hide');
				$('#$uniqueIdEDIT > span > i').html(data.newCount);	        
	        },
	   		error: function(data) {
	         	alert("Error occured. Please try again");
	         	alert(data.info);	         	
	    	},
		  	dataType:'json'
	  	});
		return false;
	});
});
FORMJS;
$this->registerJs($formsubmitJS);
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title><?= Html::encode($this->title); ?></title>
	<?php $this->head(); ?>
</head>
<body>
	<?php $this->beginBody(); ?>

<div id="page">
	
<?php $form = ActiveForm::begin(array(
	'enableAjaxValidation' => false,
	'options' => array(
		'class' => ActiveField::className(),
		'id' => 'comment-form-update',
    ),
)); ?>

	<?php if(Yii::$app->user->isGuest): ?>

    <?= $form->field($model, 'anonymous')->textInput(); ?>

  <?php endif; ?>

	<?= $form->field($model,'content')->textArea(array('rows'=>4, 'cols'=>40)); ?>

	<div class="form-group">
		<?= Html::submitButton('<i class="icon-pencil"></i> '.$model->isNewRecord?Yii::t('app','Create'):Yii::t('app','Update'), array('class'=>'btn btn-success fg-color-white')); ?>
	</div>

<?php ActiveForm::end(); ?>

</div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
