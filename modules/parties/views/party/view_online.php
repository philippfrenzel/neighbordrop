<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\Block;

use yii\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var app\modules\parties\models\Party $model
 */

$this->title = Html::encode($model->organisationName);
$this->params['breadcrumbs'][] = ['label' => 'Parties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$modalJS = <<<MODALJS
$('#window_party_edit').on('click',myModalWindow);
$.fn.modal.Constructor.prototype.enforceFocus = function() {};

MODALJS;
$this->registerJs($modalJS);

?>

<?php Block::begin(array('id'=>'sidebar')); ?>

<p>
	<?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
		'class' => 'btn btn-danger',
		'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
		'data-method' => 'post',
	]); ?>
</p>

<?= $this->render('blocks/block_system', [
		'model' => $model,
	]); ?>

<?php Block::end(); ?>

<div class="party-view">	

	<a href="<?= Url::to(['/parties/party/index']); ?>" class="btn btn-success btn-circle tipster" id="back_review" title="<?= \Yii::t('app','Back to overview!'); ?>">
      <i class="fa fa-2x fa-angle-double-left"></i>
  </a>

  <div class="clearfix"></div>

	<h1><?= Html::encode($this->title) ?></h1>	

<?php echo Tabs::widget([
 'items' => [
	 [
		 'label' => 'General',
		 'content' => $this->render('blocks/block_party', ['model' => $model]),
		 'active' => true
	 ]	 
	]
]);
?>


<?php echo Tabs::widget([
 'items' => [
	 [
		 'label' => 'Contacts',
		 'content' => $this->render('blocks/block_contact', ['model' => $model]),
		 'active' => true
	 ],
	 [
		 'label' => 'Adresses',
		 'content' => $this->render('blocks/block_address', ['model' => $model]),		 
	 ]	 
	]
]);
?>

</div>
