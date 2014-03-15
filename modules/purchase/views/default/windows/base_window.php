<?php

use yii\helpers\Html;

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

    
    <?php echo $this->render($showform, [
      'model' => $model,
      'message' => $message
    ]); ?>


<?php $this->endBody(); ?>
  </body>
</html>
<?php $this->endPage(); ?>
