<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\DetailView;

/**
 * @var app\modules\parties\models\Party $model
 */

?>

<div class="nav" role="navigation">
  <?php echo Html::a(\Yii::t('app','Edit'), ['window', 'id' => $model->id, 'win'=>'party_update','mainid'=>$model->id], [
    'class' => 'btn btn-primary navbar-btn navbar-right',
    'id' => 'window_party_edit'
  ]); ?>
</div>


<div class="row">
  <div class="col-md-6">
    <?php echo DetailView::widget([
    'model' => $model,
    'attributes' => [
      'partyTypeAsString',
      //'organisationName',
      //'partyNote:ntext',
      'taxNumber',
      'registrationNumber',
      'countryName'
    ],
]); ?>
  </div>
  <div class="col-md-6">
    <blockquote>
      <small><?= \Yii::t('app','Note'); ?></small>
      <p>
        <?= HtmlPurifier::process($model->partyNote); ?>
      </p>
    </blockquote>
  </div>
</div>

