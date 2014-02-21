<?php

$I = new WebGuy($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('NeighborDrop');
$I->seeLink('About');
$I->click('About');
$I->see('This is the About page.');
