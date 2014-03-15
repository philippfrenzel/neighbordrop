<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

?>
<div class="nav navbar-form navbar-right">      
        <?php $form = ActiveForm::begin([
          'id' => 'langSwitcherForm'
        ]); ?>

          <?= Html::dropDownList('_lang',$language,$existinglanguages,['class'=>'input-small form-control','onchange'=>new JsExpression("$('#langSwitcherForm').submit();")]); ?>

          <?php //Html::submitButton(Yii::t('app','I18N'), array('class'=>'btn btn-info')); ?>
            
        <?php ActiveForm::end(); ?>
</div>
