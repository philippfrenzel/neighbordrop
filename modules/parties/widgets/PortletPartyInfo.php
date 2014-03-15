<?php
namespace app\modules\parties\widgets;

use Yii;
use yii\helpers\Html;
use app\modules\parties\models\Party;

class PortletPartyInfo extends Portlet
{
	/**
	 * [$id description]
	 * @var integer
	 */
	public $id = 0;

	public $htmlOptions=array('class'=>'panel panel-success');

	public $isNav = false;

	/**
	 * @var string the CSS class for the portlet title tag. Defaults to 'portlet-title'.
	 */
	public $titleCssClass='title-style';

	public $enableAdmin = false;

	public function init()
  {    
   	if(!$this->isNav)
   	{
   		parent::init();
   	}
   	else
   	{
   		ob_start();
    	ob_implicit_flush(false);
    	ob_clean();
   	}	
  }

	protected function renderContent()
	{
		if($this->id != '' AND $this->id > 0)
		{
			$model = Party::find($this->id);
			//here we don't return the view, here we just echo it!
			if(is_object($model)){
        if(!$this->isNav)
  			{
  				echo $this->render('@app/modules/parties/widgets/views/_party',array('model' => $model));
  			}
  			else
  			{
  				echo "<div class='navbar-brand navbar-left'>".$model->organisationName."</div>";
  			}
      }
    }
	}

	/**
	 * Renders the decoration for the portlet.
	 * The default implementation will render the title if it is set.
	 */
	protected function renderDecoration()
	{
		if($this->title!==null)
		{
			$this->title = Yii::t('app',$this->title);
			echo "<div class='panel-heading'><div class=\"{$this->titleCssClass}\"><i class='icon-info'></i> {$this->title}</div>\n</div>\n";
		}
	}
}