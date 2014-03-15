<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\icons\Icon;
use yii\widgets\Pjax;

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

  echo ListView::widget(array(
		'id' => 'PortletCommentsTable',
		'dataProvider'=>$dpComments,
		'itemView' => '@app/modules/comments/widgets/views/iviews/_view',
		'layout' => '{items}',
		)
	);
  echo Html::a('<span class="btn btn-success bg-color-green fg-color-white tipster" title="add comment">'.Icon::show('plus', ['class'=>'fa'], Icon::FA).' add comment</span>', array("/comments/default/create", "id"=>$id,'module'=>$module), array('class' => 'create'));  

Pjax::end();
?>

