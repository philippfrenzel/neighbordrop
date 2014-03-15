<?php
namespace app\modules\workflow\widgets;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\modules\workflow\models\Workflow;
use app\modules\app\widgets\AdminPortlet;

class PortletWorkflowLog extends AdminPortlet
{
	public $title='Workflow Log';
	
	public $module = 0;
	public $id = 0;

	/**
	 * @var string the CSS class for the portlet title tag. Defaults to 'portlet-title'.
	 */
	public $titleCssClass='panel-title';

	public $enableAdmin = false;

	public $htmlOptions = array('class'=>'panel panel-info');

	public function init() {
		parent::init();
	}

	protected function renderContent()
	{
		$query = Workflow::getAdapterForworkflowLog($this->module, $this->id);

		$dpWorkflow = new ActiveDataProvider(array(
		      'query' => $query,
		      'pagination' => array(
		          'pageSize' => 10,
		      ),
	  	));
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/workflow/widgets/views/_workflowlog',array('dpWorkflow'=>$dpWorkflow));
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
			echo "<div class='panel-heading'><h3 class=\"{$this->titleCssClass}\"><i class='icon-info'></i> {$this->title}</h3>\n</div>\n";
		}
	}
}