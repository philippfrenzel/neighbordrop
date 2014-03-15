<?php

namespace app\modules\comments;

use yii\base\Module;

class Comments extends Module
{

	/**
	* @var public $controllerNamespace holing the namespace of the controller
	*/
	public $controllerNamespace = 'app\modules\comments\controllers';

	/**
	* @var public defaultRoute holding the controller name which will be called by default
	*/
	public $defaultRoute = 'comment';

	public function init()
	{
		parent::init();
	}
}
