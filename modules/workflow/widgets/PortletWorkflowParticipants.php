<?php
namespace app\modules\workflow\widgets;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\modules\workflow\models\Workflow;
use app\modules\app\widgets\AdminPortlet;

class PortletWorkflowParticipants extends AdminPortlet
{
	public $title=NULL;
	
	public $module = 0;
	public $id = 0;

	/**
	 * @var string the CSS class for the portlet title tag. Defaults to 'portlet-title'.
	 */
	public $titleCssClass='panel-title';

	public $enableAdmin = false;

	public $htmlOptions = array('class'=>'panel panel-info');

	/**
   * Renders the content of the portlet.
   */
  public function run()
  {
    $this->renderContent();
    echo ob_get_clean();
  }

	protected function renderContent()
	{
		$recipients = Workflow::getAdapterForWorkflowParticipants($this->module, $this->id);
		foreach($recipients as $recipient)
    {
      echo Html::tag('p',Html::tag('div', Html::tag('i',' ',['class'=>'fa fa-user']).' '.Html::encode($recipient),['class'=>'label label-primary']));
    }
	}

}