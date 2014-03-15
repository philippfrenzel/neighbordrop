<?php

use yii\widgets\Block;
use yii\helpers\Html;
use yii\helpers\Url;

use app\widgets\PortletSideMenu;
use \yii\grid\GridView;
use \yii\grid\DataColumn;


use app\modules\workflow\models\Workflow;
?>


<?php Block::begin(array('id'=>'sidebar')); ?>

	<?php 

	$sideMenu = array();
	$sideMenu[] = array('decoration'=>'sticker sticker-color-yellow','icon'=>'icon-arrow-left','label'=>Yii::t('app','Home'),'link'=>Url::to(array('/site/index')));

	echo PortletSideMenu::widget(array(
		'sideMenu'=>$sideMenu,
	)); ?>	 
	
<?php Block::end(); ?>

<div id="page" class="workflow-default-index">

<h3><?= Yii::t('app','Workflow'); ?></h3>
	
<?php 
	echo GridView::widget(array(
		'dataProvider'=>$dpWorkflow,
		'columns'=>array(
			array(
				'class'     => DataColumn::ClassName(),
				'attribute' => 'date_create',
			),
			array(
				'class'     => DataColumn::ClassName(),
				'attribute' => 'module',
				'label'     => 'Modul'
			),
			/*array(
				'class' => DataColumn::ClassName(),
				'attribute'=> 'status_from',
			),*/
			array(
				'class' => DataColumn::ClassName(),
				'attribute'=> 'status_to',
			),
			array(
				'class' => DataColumn::ClassName(),
				'attribute' => 'previous_user_id',
				'content'=>function($data, $row) {
					$html = $data->PreviousUser->username;
					return $html;
				},
				'label'     => 'Von'
			),
			array(
				'class' => DataColumn::ClassName(),
				'attribute' => 'next_user_id',
				'content'=>function($data, $row) {
					$html = $data->NextUser->username;
					return $html;
				},
				'label'     => 'Für'
			),
			array(
				'class' => DataColumn::className(),
				'content'=>function($data, $row) {
					$html = "";
					foreach($data->NextActions AS $wfAction){
						if($wfAction!=''){
							$html .= '<span class="label tipster" title="next action: '.Yii::t('other',$wfAction).'">';
							$html .= '<i class="icon-eye"></i>'.Html::a(Yii::t('other',$wfAction), array('/'.Workflow::getModuleAsController($data->wf_table).'/'.$wfAction,'id'=>$data->wf_id)).' ';
							$html .= '</span>&nbsp;';
						}
					}
					return $html;
				},
				'label'     => 'Aktionen'
			),
		)
	));
?>

</div>
