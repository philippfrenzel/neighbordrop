<?php

namespace app\modules\posts\controllers;

use Yii;
use app\modules\app\controllers\AppController;

class DefaultController extends AppController
{
  
  /**
   * controlling the different access rights
   * @return [type] [description]
   */
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['post'],
        ],
      ],
      'AccessControl' => [
        'class' => '\yii\web\AccessControl',
        'rules' => [
          [
            'allow'=>true,
            'actions'=>array(
              'index'
            ),
            'roles'=>array('*'),
          ]
        ]
      ]
    ];
  }

	public function actionIndex()
	{
		return $this->render('index');
	}
}
