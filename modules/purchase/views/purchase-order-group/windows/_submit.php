<?php

use kartik\helpers\Html;
use yii\web\JsExpression;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderLine $model
 * @var yii\widgets\ActiveForm $form
 */

//suppress reload of existing asstes in main window
$this->assetBundles['yii\web\YiiAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\validators\ValidationAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\widgets\ActiveFormAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\web\JqueryAsset'] = new yii\web\AssetBundle;
$this->assetBundles['yii\bootstrap\BootstrapAsset'] = new yii\web\AssetBundle;


$script = <<<SKRIPT

$('.submitcancelbutton').on('click',function(event){
  $('#applicationModal').modal('hide');
  event.preventDefault();
});

SKRIPT;

$this->registerJs($script);

?>

<div class="alert alert-warning">
  
  <h1><?= Html::Icon('exclamation-sign'); ?> <?= \Yii::t('app','You are going to forward the approval process!'); ?></h1>

  <blockquote>
    <?= \Yii::t('app','The purchase request will now be forwarded to the EGP - hit submit or cancel'); ?>
  </blockquote>

<div class="purchase-order-form">

    <div class="form-group">
        <?= Html::a(\Yii::t('app','Submit'), ['/purchase/purchase-order-group/submit', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?php echo Html::a(\Yii::t('app','Cancel'), ['cancel', 'id' => $model->id], [
          'class' => 'btn btn-info submitcancelbutton'
        ]); ?>
    </div>

</div>

</div>
