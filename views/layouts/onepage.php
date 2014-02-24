<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

use philippfrenzel\yii2fullpagejs\yii2fullpagejs;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
	
	<?php
			NavBar::begin([
				'id' => 'myMainMenu',
				'brandLabel' => '',
				'brandUrl' => Yii::$app->homeUrl,
				'options' => [
					'class' => 'navbar-default navbar-fixed-top',
				],
			]);

	?>

	<div class="navbar-brand">
		<img src="img/logo_small_75.png" alt="NeighborDrop - Social Platform">
	</div>

	<?php
			echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => [
					['label' => 'Home', 'url' => ['/site/index'],'data-menuanchor'=>'intro1'],
					['label' => 'About', 'url' => ['/site/about']],
					['label' => 'Contact', 'url' => ['/site/contact']],
					Yii::$app->user->isGuest ?
						['label' => 'Login', 'url' => ['/site/login']] :
						['label' => 'Logout (' . Yii::$app->user->identity->username . ')' ,
							'url' => ['/site/logout'],
							'linkOptions' => ['data-method' => 'post']],
				],
			]);
			NavBar::end();
	?>

	<?= yii2fullpagejs::widget([
		'clientOptions'=>[
			//'menu'=>'#myMainMenu'
		]
	]); ?>

	
	<?= $content ?>	
	
	<div class="section" id="intro2">
		<h1>Hello World!</h1>
	</div>
	<div class="section">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<footer class="footer">
			<div class="container">
				<p class="pull-left">&copy; NeigborDrop <?= date('Y') ?></p>
				<p class="pull-right"><?= Yii::powered() ?></p>
			</div>
		</footer>
	</div>	

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
