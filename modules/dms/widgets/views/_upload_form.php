<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use app\modules\parties\models\Contact;
use dosamigos\fileinput\FileInput;

?>


<?php $form = ActiveForm::begin([
  'action' => Url::to(['/dms/default/attachfile']),
  'method' => 'post',
  'options' => [
   'enctype' => 'multipart/form-data',
  ]
]); ?>

<?= FileInput::widget([
  'model' => $model,
  'attribute' => 'fileattachement', // image is the attribute
  // using STYLE_IMAGE allows me to display an image. Cool to display previously
  'style' => FileInput::STYLE_BUTTON
]);?>

<?php //$form->field($model, 'owner_id')->dropDownList(Contact::getDepartments()); ?>

<?= Html::activeHiddenInput($model,'dms_module'); ?>

<?= Html::activeHiddenInput($model,'dms_id'); ?>

<?= Html::submitButton(\Yii::t('app','Upload'), ['class' => 'btn btn-info pull-right']) ?>

<?php ActiveForm::end(); ?>
