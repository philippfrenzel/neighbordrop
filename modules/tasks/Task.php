<?php

namespace app\modules\tasks;

use \yii\base\Module;

class Task extends Module
{
	/**
	* @var public defaultRoute holding the controller name which will be called by default
	*/
	public $defaultRoute = 'default';

	/**
	* @var public $controllerNamespace holing the namespace of the controller
	*/
	public $controllerNamespace = 'app\modules\tasks\controllers';

	public function init()
	{
		parent::init();
		// custom initialization code goes here
	}
}
