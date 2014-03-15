<?php

use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Json;

use kartik\helpers\Html;
use yii\helpers\Url;

use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var app\modules\dms\models\Dmpaper $model
 * @var yii\widgets\ActiveForm $form
 */

$siteJS = <<<SITEJS
	
$.fn.modal.Constructor.prototype.enforceFocus = function() {};

$('#window_party_create').on('click',myModalWindow);

SITEJS;
$this->registerJs($siteJS);

?>

<div class="dmpaper-form">

  <div class="row">
    <div class="col-md-5">
      <?php 
        if(class_exists('\app\modules\dms\widgets\PortletDms') && Yii::$app->user->identity->isAdvanced){
          echo \app\modules\dms\widgets\PortletDms::widget(array(
            'module'=>\app\modules\dms\models\Dmsys::MODULE_DMPAPER,
            'id'=>$model->id,
          )); 
        }
      ?>
    </div>
    <div class="col-md-7">
    
    <h5><?= \Yii::t('app','Pls. first upload file!');?></h5>

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
      <div class="col-md-7">
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

$url = Url::to(['/parties/party/jsonlist']);

$fInitSelection = <<< SCRIPT
  function (element, callback) {
    var id=$(element).val();
    if (id!=="") {
      $.ajax("$url&id="+id, {
        dataType: "json"
      }).done(function(data) { callback(data.results); });
    }
  }
SCRIPT;

?>

    <?= $form->field($model, 'party_id')->widget(Select2::classname(),[
          'options' => ['placeholder' => \Yii::t('app','Select supplier ...')],            
          'addon' => [
            'append' => [
              'content' => Html::a(\Yii::t('app','Create'), ['window', 'id' => $model->id, 'win'=>'party_create','mainid'=>$model->id], [
                'class' => 'btn btn-info',
                'id' => 'window_party_create'
              ]),
              'asButton' => true
            ]
          ],
          'pluginOptions'=>[
            'minimumInputLength' => 3,
            'ajax' => [
              'url' => Url::to(['/parties/party/jsonlist']),
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>
      </div>
      <div class="col-md-5">
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

$doctypeurl = Url::to(['/dms/dmpaper/jsonlistfield','fieldname'=>'documenttype']);

$fInitSelection = <<< SCRIPT
  function (element, callback) {
    var id=$(element).val();
    if (id!=="") {
      $.ajax("$doctypeurl&id="+id, {
        dataType: "json"
      }).done(function(data) { callback(data.results); });
    }
  }
SCRIPT;

?>

    <?= $form->field($model, 'documenttype')->widget(Select2::classname(),[
          'options' => ['placeholder' => \Yii::t('app','Enter department ...')],            
          'pluginOptions'=>[
            'minimumInputLength' => 1,
            'allowClear' => true,
            'ajax' => [
              'url' => $doctypeurl,
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>
      </div>
    </div>

      
     

<div class="row">
  <div class="col-md-7">
     <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?> 
  </div>
  <div class="col-md-5">
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

$departmenturl = Url::to(['/dms/dmpaper/jsonlistfield','fieldname'=>'department']);

$fInitSelection = <<< SCRIPT
  function (element, callback) {
    var id=$(element).val();
    if (id!=="") {
      $.ajax("$departmenturl&id="+id, {
        dataType: "json"
      }).done(function(data) { callback(data.results); });
    }
  }
SCRIPT;

?>

    <?= $form->field($model, 'department')->widget(Select2::classname(),[
          'options' => ['placeholder' => \Yii::t('app','Enter department ...')],            
          'pluginOptions'=>[
            'minimumInputLength' => 1,
            'allowClear' => true,
            'ajax' => [
              'url' => $departmenturl,
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ],
            'initSelection' => new JsExpression($fInitSelection)
          ]
    ]); ?>
  </div>
</div>

   

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

$tagValues = explode(',', $model->tags);
$initTagList = [];
foreach($tagValues AS $tmptag){
  $initTagList[] = ['id'=>$tmptag, 'text'=>$tmptag];
}

$jsonTags = Json::encode($initTagList);

$tagInitSelection = <<< SCRIPT
function (element, callback) {
  var obj= $jsonTags;
  callback(obj);
}
SCRIPT;

$tagurl = Url::to(['/tags/default/jsonlist']);

?>

    <?= $form->field($model, 'tags')->widget(Select2::classname(),[
          'options' => ['placeholder' => \Yii::t('app','add tags ...')],
          'addon' => [
            'prepend'=>[
              'content' => Html::icon('globe')
            ]
          ],
          'pluginOptions'=>[
            'tags' => true,
            'tokenSeparators' => [","],
            'multiple' => true,
            'allowClear' => true,
            'createSearchChoice' => new JsExpression($createSearchChoice),
            'initSelection' => new JsExpression($tagInitSelection),
            'ajax' => [
              'url' => $tagurl,
              'dataType' => 'json',
              'data' => new JsExpression($dataExp),
              'results' => new JsExpression($dataResults),
            ]
          ]
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
    </div>    
  </div>	

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

		<?php // $form->field($model, 'status')->textInput(['maxlength' => 255]) ?>

		<hr>

		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary pull-right']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>

<?php 
/*
<?= $form->field($model, 'creator_id')->textInput() ?>

<?= $form->field($model, 'time_deleted')->textInput() ?>

<?= $form->field($model, 'time_create')->textInput() ?>
 */
