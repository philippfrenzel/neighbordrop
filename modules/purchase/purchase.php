<?php

namespace app\modules\purchase;


class purchase extends \yii\base\Module
{
  /**
  * @var public defaultRoute holding the controller name which will be called by default
  */
  public $defaultRoute = 'purchase-order';

	public $controllerNamespace = 'app\modules\purchase\controllers';

	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
