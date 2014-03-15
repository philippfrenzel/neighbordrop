<?php

use \Yii;
use yii\helpers\Html;
use yiijquerytoc\yiijquerytoc;

?>

<?php

echo yiijquerytoc::widget(
	array(
		'context' => '#onlineviewwrap',
		'clientOptions' => array(
			'smoothScroll' => false,
			'theme' => 'none',
		),	
	  'options'=>array(
			'id'    => 'pagetoccmspage',		
		),
	)
);
?>
