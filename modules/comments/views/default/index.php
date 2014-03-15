<?php
use \yii\widgets\ListView;

$this->params['breadcrumbs']=array(
	'Comments',
);
?>

<div id="page">

<h3>Comments</h3>

<?php

echo ListView::widget(array(
		'dataProvider'=>$dpComments,
		'itemView' => '_view',
	)
);

?>

</div>