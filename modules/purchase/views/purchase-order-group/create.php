<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\modules\purchase\models\PurchaseOrderGroup $model
 */

$this->title = 'Create Purchase Order Group';
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-group-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
