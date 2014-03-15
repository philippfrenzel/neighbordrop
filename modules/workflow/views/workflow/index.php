<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ListView;

use yii\grid\GridView;
use yii\grid\DataColumn;

use yii\widgets\Block;
use app\modules\workflow\models\Workflow;

/**
 * @var yii\base\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\workflow\models\WorkflowForm $searchModel
 */

$this->title = 'Workflows';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Block::begin(array('id'=>'sidebar')); ?>

	<?php 
  if(class_exists('\app\modules\workflow\widgets\PortletToolbox')){
    echo \app\modules\workflow\widgets\PortletToolbox::widget(array(
      'enableAdmin'=>false,
      'menuItems'=>array(
          array('label'=>Yii::t('app','home'),'link'=>Url::to(array('/site/index')),'icon'=>'icon-home'),
          array('label'=>Yii::t('app','overview'),'link'=>Url::to(array('/workflow/workflow/index')),'icon'=>'icon-list-alt'),          
      )
    )); 
  }
?>	 
	
<?php Block::end(); ?>


<?php Block::begin(array('id'=>'toolbar')); ?>

  <h4>
    <i class="icon-hand-down"></i>
    Hilfe
  </h4>
 
	<p>
		Um die Abläufe im Unternehmen zu optimieren, haben wir uns einen Ablauf erarbeitet, der alle <b>relevanten Informationen beinhaltet</b> und
		alle betroffenen Personen über das entsprechende Ereignis informiert. Da unsere Organisation eine flexible bleiben soll, sind <b>Verbesserungsvorschläge
		oder Änderungswünsche</b> jederzeit <b>willkommen</b>. Im Bereich "Inhalt", kann jeder sich zum aktuellen Ablauf unter "Anwendungen" ein konkretes Bild
		machen.
	</p>

<?php Block::end(); ?>

<div class="module-wsp">

	<h1><?= Html::encode($this->title); ?></h1>

	<hr>

	<?php // echo $this->render('_search', array('model' => $searchModel)); ?>

<div class="box bordered">
	<ul class="list-group">
		<?php 
			echo ListView::widget(array(
						'id'           => 'IndexWorkflowView',
						'dataProvider' => $dataProvider,
						'itemView'     => 'iviews/_view',
						'layout'     => '<div class="box-header">{summary}</div>{items}{pager}',
					)
			);
		?>
	</ul>
</div>

</div>
