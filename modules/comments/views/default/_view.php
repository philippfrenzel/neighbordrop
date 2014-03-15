<?php
use \yii\helpers\Html;
use app\modules\workflow\models\Workflow;
use app\modules\comments\models\Comment;


$deleteJS = <<<DEL
$('.container').on('click','.op a.delete',function() {
	var th=$(this),
		container=th.closest('div.comment'),
		id=container.attr('id').slice(1);
	if(confirm('Are you sure you want to delete comment #'+id+'?')) {
		$.ajax({
			url:th.attr('href'),
			data:{
				'ajax':1
			},
			type:'POST'
		}).done(function(){container.slideUp()});
	}
	return false;
});

DEL;
$this->registerJs($deleteJS);
?>

<div class="comment" id="c<?= $model->id; ?>">

	<p>
		<?= Html::a("#{$model->id}", $model->url, array(
			'class'=>'cid',
			'title'=>Yii::t('app','Permalink to this comment'),
		)); ?>
		<?= $model->AuthorLink; ?> <?= Yii::t('app','says on'); ?>
		<?= Html::a(Html::encode($model->post->title), $model->post->url); ?>
	<p>

	<p><?= nl2br(Html::encode($model->content)); ?></p>

	<p class="op">
		<?php if($model->status==Workflow::STATUS_PENDING): ?>
			<span class="pending">Pending approval</span> |
			<?= Html::a('Approve', array('comment/approve','id'=>$model->id), array('class'=>'approve')); ?> |
		<?php endif; ?>
		<?= Html::a('Update',array('comment/update','id'=>$model->id)); ?> |
		<?= Html::a('Delete',array('comment/delete','id'=>$model->id),array('class'=>'delete')); ?> |
		<?= date('F j, Y \a\t h:i a',$model->time_create); ?>		
	</p>

</div><!-- comment -->