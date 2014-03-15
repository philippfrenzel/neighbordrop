<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\purchase\models\PurchaseOrderGroupSearch $searchModel
 */

$this->title = 'Purchase Order Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-group-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create PurchaseOrderGroup', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'id',
			'contact_id',
			'purchaseorder_id',
			'creator_id',
			'time_deleted:datetime',
			// 'time_create:datetime',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

</div>
