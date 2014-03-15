<?php

namespace app\modules\pages;


class Pages extends \yii\base\Module
{
  /**
  * @var public defaultRoute holding the controller name which will be called by default
  */
  public $defaultRoute = 'page';

  /**
  * @var public $controllerNamespace holing the namespace of the controller
  */
	public $controllerNamespace = 'app\modules\pages\controllers';

	public function init()
	{
		parent::init();
		// custom initialization code goes here
	}
}
