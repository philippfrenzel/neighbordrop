<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use kartik\icons\Icon;

/**
 * @var app\modules\parties\models\Party $model
 */

$modalJS = <<<MODALJS
$('#window_address_add').on('click',myModalWindow);
MODALJS;
$this->registerJs($modalJS);

?>

<div class="nav" role="navigation">
  
  &nbsp;
  <?php echo Html::a(\Yii::t('app','Create'), ['window', 'id' => $model->id, 'win'=>'address_create','mainid'=>$model->id], [
    'class' => 'btn btn-info navbar-btn navbar-right',
    'id' => 'window_address_add'
  ]); ?>
</div>


<?php foreach($model->addresses AS $address): ?>

<div class="row">
  <div class="col-md-1">
    <?= Icon::show('globe', ['class'=>'fa fa-2x'], Icon::FA);?>
  </div>
  <div class="col-md-7">
    <address>
      <abbr title="PO Box">Po Box:</abbr> <?= $address->postBox ?><br>
      <?= $address->addressLine ?> <?= $address->streetDescription ?><br>
      <?= $address->postCode ?> <?= $address->cityName ?> <br>
      <?= $address->countryName ?>
    </address>
  </div>
  <div class="col-md-4">
    <?php 
      echo Html::a(\Yii::t('app','Edit'), ['window', 'id' => $address->id, 'win'=>'address_update','mainid'=>$model->id], [
        'class' => 'btn btn-default navbar-btn navbar-right',
        'id' => 'window_address_edit'.$address->id
      ]); 

      $actionjs = new yii\web\JsExpression("$('#window_address_edit".$address->id."').on('click',myModalWindow);");
      $this->registerJs($actionjs);
    ?>
  </div>
</div>
<hr>
  

<?php endforeach; ?>
