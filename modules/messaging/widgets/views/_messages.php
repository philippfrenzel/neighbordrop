<?php

use yii\helpers\Html;
use yii\widgets\ListView;

?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => 'iviews/_view',
    'layout'     => '<div class="box-header">{summary}</div>{items}{pager}',
  ]); 
?>
