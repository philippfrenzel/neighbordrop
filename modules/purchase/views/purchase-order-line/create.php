<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderLine $model
 */

$this->title = 'Create Purchase Order Line';
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Lines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-line-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
