<?php

use yii\helpers\Html;
use app\modules\comments\widgets\PortletComments;

$this->assetBundles['yii\web\YiiAsset'] = new yii\web\AssetBundle;;
$this->assetBundles['yii\web\JqueryAsset'] = new yii\web\AssetBundle;;

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

    
<?= PortletComments::widget(array(
  'module'=>$module,
  'id'=>$id,
)); ?>


<?php $this->endBody(); ?>
  </body>
</html>
<?php $this->endPage(); ?>