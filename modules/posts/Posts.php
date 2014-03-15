<?php

namespace app\modules\posts;


class Posts extends \yii\base\Module
{
  /**
  * @var public defaultRoute holding the controller name which will be called by default
  */
  public $defaultRoute = 'post';

  /**
  * @var public $controllerNamespace holing the namespace of the controller
  */
  public $controllerNamespace = 'app\modules\posts\controllers';

	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
