<?php
namespace app\modules\tasks\widgets;

use yii\helpers\Html;
use app\modules\tasks\models\Task;

class PortletTasksBatch extends Portlet
{
	public $title='Aufgaben';
	
	public $module = 0;
	public $id = 0;

	public $enableAdmin = false;

	public $htmlOptions = array('class'=>'panel panel-info');

	public function init() {
		parent::init();
	}

	protected function renderContent()
	{
		$countTasks = Task::getAdapterForTaskLogCount($this->module, $this->id);
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/tasks/widgets/views/_tasksbatch',array('countTasks'=>$countTasks,'module'=>$this->module,'id'=>$this->id));
	}
}