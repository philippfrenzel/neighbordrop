<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

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
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title><?= Html::encode($this->title) ?></title>
	<link rel="icon" href="img/favicon.png" type="image/png">
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
					['label' => 'Register', 'url' => ['/user/registration/register']]:
					NULL,
				Yii::$app->user->isGuest ?
					['label' => 'Login', 'url' => ['/user/security/login']] :
					['label' => 'Logout (' . Yii::$app->user->identity->username . ')' ,
						'url' => ['/user/security/logout'],
						'linkOptions' => ['data-method' => 'post']],
			],
		]);
		NavBar::end();
?>

	<?= $content ?>	
	
<div id="footer">
	<div class="container">
		<p class="pull-left">&copy; NeigborDrop <?= date('Y') ?></p>
		<p class="pull-right"><?= Yii::powered() ?></p>
	</div>
</div>	

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
