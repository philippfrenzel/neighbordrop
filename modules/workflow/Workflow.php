<?php

namespace app\modules\workflow;


class Workflow extends \yii\base\Module
{
	/**
	* @var public defaultRoute holding the controller name which will be called by default
	*/
	public $defaultRoute = 'workflow';

	/**
	* @var public $controllerNamespace holing the namespace of the controller
	*/
	public $controllerNamespace = 'app\modules\workflow\controllers';

	public function init()
	{
		parent::init();
		// custom initialization code goes here
	}
}
