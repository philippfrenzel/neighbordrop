<?php

namespace app\modules\categories;


class categories extends \yii\base\Module
{
	public $controllerNamespace = 'app\modules\categories\controllers';

  /**
  * @var public defaultRoute holding the controller name which will be called by default
  */
  public $defaultRoute = 'categories';

	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
