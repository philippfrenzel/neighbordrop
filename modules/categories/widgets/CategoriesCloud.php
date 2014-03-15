<?php

namespace app\modules\categories\widgets;

use \yii\helpers\Html;
use app\modules\app\widgets\Portlet;
use app\modules\categories\models\Categories;

class CategoriesCloud extends Portlet
{
  public $title='Categroies';
  public $module = NULL;
  
  public function init() {
    parent::init();
  }

  protected function renderContent()
  {
    if(!is_null($this->module))
    {
      $categories = Categories::getOptions($this->module);
      foreach($categories as $key=>$value)
      {
        $class = 'c_pink middle-font';
        echo Html::a(strtoupper(Html::encode($value)), array('site/index','category'=>$key), array('class'=>$class))
        ."\n";
      }
    }
    else
    {
      echo " ";
    }
  }
}
