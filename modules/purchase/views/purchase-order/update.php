<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrder $model
 */

$this->title = 'Update Purchase Order: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-order-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
