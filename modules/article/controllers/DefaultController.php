<?php

namespace app\modules\article\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use yii\web\Controller;

class DefaultController extends AppController
{
	public function actionIndex()
	{
		return $this->render('index');
	}
}
