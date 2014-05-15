<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\icons\Icon;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);

//init icons
Icon::map($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>	
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<?php 
		$this->registerMetaTag(['name' => 'description','lang'=>'en', 'content' => Html::encode($this->context->metadescription)], 'meta-description');
		$this->registerMetaTag(['name' => 'keywords','lang'=>'en','content' => Html::encode($this->context->metakeywords)], 'meta-keywords');
	?>
	<title><?= Html::encode($this->title) ?></title>
	<link rel="icon" href="img/favicon.png" type="image/png">
	<?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<div id="nbdrop-logo"></div>
	
<?php
		NavBar::begin([
			'id' => 'myMainMenu',
			'brandLabel' => '',
			'brandUrl' => Yii::$app->homeUrl,
			'options' => [
				'class' => 'navbar-default navbar-fixed-top',
			],
		]);
		echo Nav::widget([
			'options' => ['class' => 'navbar-nav navbar-right'],
			'items' => [
				['label' => 'Home', 'url' => ['/site/index'],'data-menuanchor'=>'intro1'],
				['label' => 'About', 'url' => ['/site/about']],
				['label' => 'Contact', 'url' => ['/site/contact']],
				Yii::$app->user->isGuest ?
					['label'=> '','url'=>'#']:
					['label' => 'Logout (' . Yii::$app->user->identity->username . ')' ,
						'url' => ['/user/security/logout'],
						'linkOptions' => ['data-method' => 'post']
					],
			],
		]);
		NavBar::end();
?>

	<?= $content ?>	
	
<div id="footer">
	<div class="container">
		<p class="pull-left fg_white">&copy; NeigborDrop <?= date('Y') ?></p>
		<p class="pull-right"><?= Yii::powered() ?></p>
	</div>
</div>	

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
