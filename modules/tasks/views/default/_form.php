<?php

use yii\helpers\Json;

use kartik\helpers\Html;
use yii\helpers\Url;

use kartik\widgets\Select2;
use yii\web\JsExpression;

use yii\widgets\ActiveForm;
use yii\widgets\Block;

use app\modules\workflow\models\Workflow;
use app\modules\workflow\widgets\PortletWorkflowLog;
/**
 * @var yii\base\View $this
 * @var app\modules\tasks\models\Task $model
 * @var ActiveForm $form
 */
?>

<?php Block::begin(array('id'=>'sidebar')); ?>

	<?php 

	$sideMenu = array();
	$sideMenu[] = array('decoration'=>'sticker sticker-color-yellow','icon'=>'icon-home','label'=>Yii::t('app','Home'),'link'=>Url::to(array('/site/index')));
	$sideMenu[] = array('decoration'=>'sticker sticker-color-green','icon'=>'icon-list-alt','label'=>Yii::t('app','Overview'),'link'=>Url::to(array('/tasks/default/index')));

	echo app\modules\tasks\widgets\PortletToolbox::widget(array(
		'menuItems'=>$sideMenu,
		'enableAdmin' => false,
	)); ?>	 
	
<?php Block::end(); ?>



<div id="page" class="form">

<h3><?= $this->context->action->uniqueId; ?></h3>

<?php $form = ActiveForm::begin([
	'id' => 'TaskCreateForm',
]); ?>

	<?php

$dataExp = <<< SCRIPT
  function (term, page) {
    return {
      search: term, // search term
    };
  }
SCRIPT;

$dataResults = <<< SCRIPT
  function (data, page) {
    return {
      results: data.results
    };
  }
SCRIPT;

$createSearchChoice = <<< SCRIPT
function(term, data) {
    if ($(data).filter(function() {
      return this.text.localeCompare(term) === 0;
    }).length === 0) {
      return {
        id: term,
        text: term
      };
    }
 }
SCRIPT;

$rcpValues = explode(',', $model->recipients);
$initRcpList = [];
foreach($rcpValues AS $tmprcp){
  $initRcpList[] = ['id'=>$tmprcp, 'text'=>$tmprcp];
}

$jsonRcps = Json::encode($initRcpList);

$rcpInitSelection = <<< SCRIPT
function (element, callback) {
  var obj= $jsonRcps;
  callback(obj);
}
SCRIPT;

$contacturl = Url::to(['/parties/contact/jsonlistemail']);

?>

    <?= $form->field($model, 'recipients')->widget(Select2::classname(),[
          'options' => ['placeholder' => \Yii::t('app','add recipients ...')],
          'addon' => [
            'prepend'=>[
              'content' => Html::icon('user')
            ]
          ],
          'pluginOptions'=>[
            'tags' => true,
            'tokenSeparators' => [","],
            'multiple' => true,
            'allowClear' => true,
            'minimumInputLength' => 2,
            'createSearchChoice' => new JsExpression($createSearchChoice),
            'initSelection' => new JsExpression($rcpInitSelection),
            'ajax' => [
              'url' => $contacturl,
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ]
          ]
    ]); ?>

	<?= $form->field($model, 'content')->textArea(array('rows'=>3)); ?>
	<?php //$form->field($model, 'status')->dropDownList(Workflow::getStatusOptions()); ?>
	
	<div class="form-group">
		<?= Html::submitButton('<i class="icon-pencil"></i> '.Yii::t('app','Update'), array('class'=>'btn btn-success fg-color-white')); ?>
	</div>

  <?= Html::activeHiddenInput($model,'creator_id'); ?>
  <?= Html::activeHiddenInput($model,'time_create'); ?>
  <?= Html::activeHiddenInput($model,'task_table'); ?>
  <?= Html::activeHiddenInput($model,'task_id'); ?>

<?php ActiveForm::end(); ?>

</div><!-- _form -->
