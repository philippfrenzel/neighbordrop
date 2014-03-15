<?php

use yii\helpers\Html;

?>


<div class="purchase-default-index">

<?php foreach($suppliers AS $supplier): ?>
<div class="paper" style="background-color: #ffffff;padding:5px 5px 5px 5px">
	
	<?php
    echo Html::a(\Yii::t('app','Export 2 Word'), ['/word-export/purchase-request-doc','id'=>$model->id,'supplier_id'=>$supplier->supplier->id], [
      'class' => 'btn btn-primary pull-right',
      'target' => '_blank'
    ]);
  ?>
  <div class="clearfix"></div>
	<hr>
	<h1>Purchase Order</h1>
	<div class="row">
		<div class="col-md-6">
			<blockquote>
				<h4>Supplier</h4>
				<p>
					<?= $supplier->supplier->organisationName; ?><br>
					<?= $supplier->supplier->addresses[0]->addressLine; ?><br>
					<?= $supplier->supplier->addresses[0]->postCode; ?> <?= $record->supplier->addresses[0]->cityName; ?><br>
				</p>
			</blockquote>
		</div>
		<div class="col-md-6">
			<blockquote>
				<h4>Requester</h4>
				<p>
					<?= $model->contact->contactName; ?><br>
					<?= $model->contact->department; ?>
				</p>
			</blockquote>
		</div>
	</div>
	<hr>
	<h2>Order</h2>
	
<?php 
$orderLines = $model::adapterForPolinesbysupplier($model->id,$supplier->supplier->id);
foreach($orderLines AS $record): ?>
	<div class="row">
		<div class="col-md-1"><div class="pull-right"><?= $record->order_amount; ?></div></div>
		<div class="col-md-5"><?= $record->article; ?><br></div>		
		<div class="col-md-3"><div class="pull-right"><?= $record->order_price; ?></div></div>
		<div class="col-md-3"><?= $record->order_currency; ?></div>
	</div>
<?php endforeach; ?>

</div>
<?php endforeach; ?>