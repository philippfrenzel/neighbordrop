<?php

namespace app\modules\parties\controllers;

use Yii;
use app\modules\app\controllers\AppController;

class DefaultController extends AppController
{
	public function actionIndex()
	{
		return $this->render('index');
	}
}
