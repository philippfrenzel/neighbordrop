<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\grid\GridView;

use yiidhtmlx\Grid;
use yii\widgets\Block;
use kartik\widgets\SideNav;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\parties\models\PartySearch $searchModel
 */

$this->title = 'Parties';
$this->params['breadcrumbs'][] = $this->title;

//all that has to do with the grid
$target = Url::to(array('/parties/party/view','id'=>''));
$gridURL = Url::to(['/parties/party/dhtmlxgrid','un'=> date('Ymd')]);
$gridJS = <<<GRIDJS
function doOnRowSelect(id,ind) {
	window.location = "$target"+id;	
};

function doOnFilterStart(indexes,values){
	$.ajax("$gridURL&search="+values).
	success(function(data){
			dhtmlxPartyGrid.clearAll();
			dhtmlxPartyGrid.parse(data,"json");
		}
	);	
}
GRIDJS;
$this->registerJs($gridJS);

?>

<?php Block::begin(array('id'=>'sidebar')); ?>

	<?php

				echo SideNav::widget([
    			'type' => SideNav::TYPE_DEFAULT,
    			'heading' => \Yii::t('app','Party Options'),
    			'items' => 
    			[
    				[
					    'url' => ['/site/index'],
					    'label' => \Yii::t('app','Home'),
					    'icon' => 'home'
				    ],
				    ['label' => Yii::t('app','Create'), 'icon'=>'plus', 'url'=>['create']]
				  ]
    	]);
   ?>
	
<?php Block::end(); ?>

<div class="party-index">

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php
	echo Grid::widget(
		[
		'clientOptions'=>[
				'parent'      => 'PartyGrid',
				'image_path'  => Yii::$app->AssetManager->getBundle('yiidhtmlx\GridObjectAsset')->baseUrl."/dhtmlxGrid/imgs/",
				'auto_height' => false,
				'auto_width'  => true,
				'smart'       => true,
				'skin'        => "dhx_terrace",			 	
			 	'columns' => [
			 		['label'=>'id','width'=>'40','type'=>'ro'],
					['label'=>[Yii::t('app','Source')],'type'=>'ro','width'=>'100'],
					['label'=>[Yii::t('app','System ID')],'type'=>'ro','width'=>'100'],
					['label'=>[Yii::t('app','organisationName'),'#text_filter'],'type'=>'ed'],
					['label'=>[Yii::t('app','TaxNo')],'type'=>'ed','width'=>'150'],
					['label'=>[Yii::t('app','Country')],'type'=>'ed','width'=>'50'],
				],
			],
			'enableSmartRendering' => true,
			'options'=>[
				'id'    => 'PartyGrid',
				'height' => '500px',								
			],
			'clientDataOptions'=>[
				'type'=>'json',
				'url'=> $gridURL
			],
			'clientEvents'=>[
				'onRowDblClicked'=>'doOnRowSelect',
				'onEnter' => 'doOnRowSelect',
				'onFilterStart' => 'doOnFilterStart'
			]		
		]
	);
	?>

</div>
