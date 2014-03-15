<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\DetailView;

use yiidhtmlx\Grid;

/**
 * @var app\modules\parties\models\Party $model
 */

$modalJS = <<<MODALJS
$('#window_contact_edit').on('click',myModalWindow);
$('#window_contact_add').on('click',myModalWindow);

MODALJS;
$this->registerJs($modalJS);

//all that has to do with the grid
$target = Url::to(array('/parties/contact/view','id'=>''));
$gridURL = Url::to(['/parties/contact/dhtmlxgrid','un'=> date('Ymd'),'party_id'=>$model->id]);
$gridJS = <<<GRIDJS

function doOnFilterStart(indexes,values){
  $.ajax("$gridURL&search="+values).
  success(function(data){
      dhtmlxContactGrid.clearAll();
      dhtmlxContactGrid.parse(data,"json");
    }
  );  
}
GRIDJS;
$this->registerJs($gridJS);

?>

<div class="nav" role="navigation">
  <?php /*echo Html::a('edit', ['window', 'id' => $model->id, 'win'=>'contact_update','mainid'=>$model->id], [
    'class' => 'btn btn-default navbar-btn navbar-right',
    'id' => 'window_contact_edit'
  ]);*/?>
  &nbsp;
  <?php echo Html::a(\Yii::t('app','Create'), ['window', 'id' => $model->id, 'win'=>'contact_create','mainid'=>$model->id], [
    'class' => 'btn btn-info navbar-btn navbar-right',
    'id' => 'window_contact_add'
  ]); ?>
</div>


<?php
  echo Grid::widget(
    [
    'clientOptions'=>[
        'parent'      => 'ContactGrid',
        'image_path'  => Yii::$app->AssetManager->getBundle('yiidhtmlx\GridObjectAsset')->baseUrl."/dhtmlxGrid/imgs/",
        'auto_height' => false,
        'auto_width'  => false,
        'smart'       => true,
        'skin'        => "dhx_terrace",       
        'columns' => [
          ['label'=>'id','width'=>'40','type'=>'ro'],
          ['label'=>[Yii::t('app','contactName'),'#text_filter'],'type'=>'ed'],
          ['label'=>[Yii::t('app','Department')],'type'=>'ed'],
          ['label'=>[Yii::t('app','Email')],'type'=>'ed','width'=>'130'],
          ['label'=>[Yii::t('app','Phone')],'type'=>'ed','width'=>'90'],
          ['label'=>[Yii::t('app','Edit')],'type'=>'modalbutton','width'=>'80'],
        ],
      ],
      'enableSmartRendering' => true,
      'options'=>[
        'id'    => 'ContactGrid',
        'height' => '300px',  
        'width'  => '100%',            
      ],
      'clientDataOptions'=>[
        'type'=>'json',
        'url'=> $gridURL
      ],
      'clientEvents'=>[
        'onFilterStart' => 'doOnFilterStart'
      ]   
    ]
  );
?>
