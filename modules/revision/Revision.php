<?php

namespace app\modules\revision;

use \yii\base\Module;

class Revision extends Module
{
  /**
  * @var public defaultRoute holding the controller name which will be called by default
  */
  public $defaultRoute = 'revision';

	/**
	* @var public $controllerNamespace holing the namespace of the controller
	*/
	public $controllerNamespace = 'app\modules\revision\controllers';

	public function init()
	{
		parent::init();
	}
}
