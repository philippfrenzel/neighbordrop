<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\modules\dms\models\Dmpaper $model
 */

$this->title = \Yii::t('app','Add Paper');
$this->params['breadcrumbs'][] = ['label' => 'Dmpapers', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dmpaper-update">

  <a href="<?= Url::to(['/dms/dmpaper/assistant']); ?>" class="btn btn-success btn-circle">
      <i class="fa fa-2x fa-angle-double-left"></i>
  </a>

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
