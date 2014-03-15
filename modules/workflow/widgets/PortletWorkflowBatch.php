<?php
namespace app\modules\workflow\widgets;

use yii\helpers\Html;
use app\modules\workflow\models\Workflow;
use app\modules\app\widgets\AdminPortlet;

class PortletWorkflowBatch extends AdminPortlet
{
	public $title=NULL;
	
	public $module = 0;
	public $id = 0;

	public function init(){
		parent::init();
	}

	protected function renderContent()
	{
		$countWorkflow = Workflow::getAdapterForWorkflowLogCount($this->module, $this->id);
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/workflow/widgets/views/_workflowbatch',array('countWorkflow'=>$countWorkflow,'module'=>$this->module,'id'=>$this->id));
	}

}