<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use kartik\icons\Icon;
use yiidhtmlx\Grid;

?>

<?php
  echo Grid::widget(
    [
    'clientOptions'=>[
        'parent'      => 'PurchaseRequestGrid',
        'image_path'  => Yii::$app->AssetManager->getBundle('yiidhtmlx\GridObjectAsset')->baseUrl."/dhtmlxGrid/imgs/",
        'auto_height' => false,
        'auto_width'  => false,
        'skin'        => "dhx_terrace",       
        'columns' => [
          //['label' => Yii::t('app','Approved'),'width'=>'80','type'=>'ch'],
          ['label' => 'id','width'=>'40','type'=>'ro'],
          ['label' => [Yii::t('app','Supplier')],'type'=>'ro'],
          ['label' => [Yii::t('app','Product')],'type'=>'ro'],
          ['label' => [Yii::t('app','Quantity')],'type'=>'ron','width'=>90,'align'=>'right'],
          ['label' => [Yii::t('app','Price')],'type'=>'ron','width'=>90,'align'=>'right'],
          ['label' => [Yii::t('app','CUR')],'type'=>'ro','width'=>50],
          ['label' => [Yii::t('app','Total')],'type'=>'ron','width'=>90,'align'=>'right'],
          ['label' => [Yii::t('app','Ship wish')],'type'=>'ro','width'=>100],
          ['label' => [Yii::t('app','Status')],'type'=>'ro','width'=>100],
          ['label' => [Yii::t('app','Edit')],'type'=>'modalbutton','width'=>'60'],
          ['label' => [Yii::t('app','Purchase')],'type'=>'modalpurchase','width'=>'60'],
          ['label' => [Yii::t('app','Reject')],'type'=>'modalreject','width'=>'90'],
        ],
      ],
      'enableSmartRendering' => false,
      'attachFooter' => '"'.Yii::t('app','Total').',#cspan,#cspan,#cspan,#cspan,#cspan,<div id=\'nr_ta\'>0</div>,,,,,", ["text-align:right;"]',
      'setNumberFormat' => [
        '4' => '0,000.00',
        '5' => '0,000.00',
        '7' => '0,000.00',
      ],
      'options'=>[
        'id'    => 'PurchaseRequestGrid',
        'height' => '400px', 
        'width'  => '100%',               
      ],
      'clientDataOptions'=>[
        'type'=>'json',
        'url'=> $gridURL
      ],
      'clientEvents'=>[
        'onFilterStart' => 'doOnFilterStart'
        ,'onCheck' => 'doOnApproval'
      ]   
    ]
  );
?>
