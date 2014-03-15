<?php

namespace app\modules\pages\controllers;

use Yii;
use app\modules\app\controllers\AppController;

class DefaultController extends AppController
{
	public function actionIndex()
	{
		return $this->render('index');
	}
}
