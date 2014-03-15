<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var app\modules\parties\models\Party $model
 */

?>

<h4><?= Yii::t('app','System Info') ?></h4>

<?php echo DetailView::widget([
    'model' => $model,
    'attributes' => [
      'id',
      'system_key',
      'system_name',
      'system_upate:datetime'
    ],
  ]); ?>