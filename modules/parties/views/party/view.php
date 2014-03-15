<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\DetailView;
use kartik\icons\Icon;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\Party $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="party-view">

	<a href="<?= Url::to(['/parties/party/index']); ?>" class="btn btn-success btn-circle tipster" id="back_review" title="<?= \Yii::t('app','Back to overview!'); ?>">
      <i class="fa fa-2x fa-angle-double-left"></i>
  </a>

  <div class="clearfix"></div>

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
			'organisationName',
			'partyNote:ntext',
			'taxNumber',
			'registrationNumber',
			'registrationCountryCode',
			'party_type',
			'system_key',
			'system_name',
			'system_upate',
			'creator_id',
			'time_deleted:datetime',
			'time_create:datetime',
		],
	]); ?>

</div>
