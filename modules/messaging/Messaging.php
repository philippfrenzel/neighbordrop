<?php

namespace app\modules\messaging;


class Messaging extends \yii\base\Module
{
	public $controllerNamespace = 'app\modules\messaging\controllers';

  /**
  * @var public defaultRoute holding the controller name which will be called by default
  */
  public $defaultRoute = 'default';

	public function init()
	{
		parent::init();
		// custom initialization code goes here
	}
}
