<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderGroup $model
 */

$this->title = 'Update Purchase Order Group: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-order-group-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
