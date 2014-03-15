<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;

use kartik\icons\Icon;

?>

<?php 
	if($enableCommentsLog)
		echo Html::a('<span class="btn btn-info btn-xs tipster" title="# '.$countComments.' Kommentare">'.Icon::show('comment', ['class'=>'fa'], Icon::FA).$countComments.'</span>', array("/comments/default/view", "id"=>$id,'module'=>$module), array('class' => 'view', "id"=>$uniqueIdEDIT)).'&nbsp;';
	if(!is_null($mode))
	{
		echo Html::a('<span class="btn btn-success btn-xs bg-color-green fg-color-white tipster" title="neuer Kommentar">'.Icon::show('plus', ['class'=>'fa'], Icon::FA).' add</span>', array("/comments/default/createwindow", "id"=>$id,'module'=>$module), array('class' => 'create', "id"=>$uniqueIdCREATE));	
	}
	else
	{
		echo Html::a('<span class="btn btn-success btn-xs bg-color-green fg-color-white tipster" title="neuer Kommentar">'.Icon::show('plus', ['class'=>'fa'], Icon::FA).' add</span>', array("/comments/default/create", "id"=>$id,'module'=>$module), array('class' => 'create'));	
	}
?>

