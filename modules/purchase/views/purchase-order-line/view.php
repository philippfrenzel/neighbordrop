<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderLine $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Lines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-line-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
			'data-method' => 'post',
		]); ?>
	</p>

	<?php echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'purchaseordergroup_id',
			'order_amount',
			'order_price',
			'article_id',
			'status',
			'creator_id',
			'time_deleted:datetime',
			'time_create:datetime',
		],
	]); ?>

</div>
