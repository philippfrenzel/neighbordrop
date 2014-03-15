<?php
namespace app\modules\tags\widgets;

use \yii\helpers\Html;
use app\modules\app\widgets\Portlet;
use app\modules\tags\models\Tag;

class TagCloud extends Portlet
{
  public $title='Tags';
  public $maxTags=20;

  private $_labels = array();

  public function init() {
    parent::init();
    $this->_labels = array(
      '9'=>'tags-danger',
      '10'=>'tags-success',
      '11'=>'tags-info',
      '12'=>'tags-inverse',
      '13'=>'tags-important',
      '14'=>'tags-warning',
    );
  }

  protected function renderContent()
  {
    $tags=Tag::findTagWeights($this->maxTags);
    foreach($tags as $tag=>$weight)
    {
      if ($weight>14) $weight=14;
      $class = 'tags';
      if (isset($this->_labels[$weight])) {
        $class .=' '.$this->_labels[$weight];
      }
      echo Html::a(strtolower(Html::encode($tag)), array('site/index','tag'=>$tag), array('class'=>$class))."\n";
    }
  }
}
