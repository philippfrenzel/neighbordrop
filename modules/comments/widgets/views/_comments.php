<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ListView;
use yii\widgets\Pjax;
use kartik\icons\Icon;

use \yii\widgets\ActiveForm;

$script = <<<SKRIPT

$(document).on('submit', '#CommentCreateForm', function(event) {
  $.pjax.submit(event, '#PtlCommentsPjax')
})

SKRIPT;

$this->registerJs($script);

$deleteJS = <<<DEL
$('.post-box').on('click','.op a.delete',function() {
    var th=$(this),
    container=th.closest('div.post-box'),
    id=container.attr('id').slice(1);
  if(confirm('Are you sure you want to delete comment #'+id+'?')) {
    $.ajax({
      url:th.attr('href'),
      data:{
        'ajax':1,
        'id':id
      },
      type:'POST'
    }).done(function(){container.slideUp()});
  }
  return false;
});

DEL;
$this->registerJs($deleteJS);

?>


<?php
Pjax::begin(['id'=>'PtlCommentsPjax']);
?>  
<?php
$model = new \app\modules\comments\models\Comment;
$form = ActiveForm::begin([
  'id' => 'CommentCreateForm',
  'action' => Url::to(['/comments/default/create']),
]); ?>

  <?php if(Yii::$app->user->isGuest): ?>

    <?= $form->field($model, 'anonymous')->textInput(); ?>

  <?php endif; ?>

  <?= $form->field($model,'content')->textArea(array('rows'=>4, 'cols'=>40)); ?>
  
  <?= Html::activeHiddenInput($model,'comment_table',['value'=>$module]); ?>
  
  <?= Html::activeHiddenInput($model,'comment_id',['value'=>$id]); ?>
  
  <div class="form-actions">
    <?= Html::submitButton('<i class="icon-pencil"></i> '.Yii::t('app','add'), array('class' => 'btn btn-success fg-color-white','id'=>'submit_btn')); ?>
  </div>

<?php 
ActiveForm::end();
?>

<?php
  echo ListView::widget(array(
		'id' => 'PortletCommentsTable',
		'dataProvider'=>$dpComments,
		'itemView' => '@app/modules/comments/widgets/views/iviews/_view',
		'layout' => '{items}',
		)
	);
Pjax::end();
?>

