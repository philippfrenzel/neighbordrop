<?php

use yii\helpers\Html;
use yii\widgets\Block;

/**
 * @var yii\web\View $this
 * @var app\modules\article\models\Article $model
 */

$this->title = 'Update Article: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="article-update">

<?php Block::begin(array('id'=>'toolbar')); ?>

  <?= $this->render('blocks/block_system', [
    'model' => $model,
  ]); ?>

<?php Block::end(); ?>

<?php Block::begin(array('id'=>'sidebar')); ?>

<p>
  <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'),
    'data-method' => 'post',
  ]); ?>
</p>

<?php Block::end(); ?>

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
