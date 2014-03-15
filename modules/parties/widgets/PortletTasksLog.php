<?php
namespace app\modules\parties\widgets;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\modules\tasks\models\Task;

class PortletTasksLog extends Portlet
{
	public $title=Null;
	
	public $module = 0;
	public $id = 0;

	/**
	 * @var string the CSS class for the portlet title tag. Defaults to 'portlet-title'.
	 */
	public $titleCssClass='panel-title';

	public $htmlOptions = array('class'=>'task-panel');

	public $enableAdmin = false;

	public function init() {
		parent::init();
	}

	protected function renderContent()
	{
		$query = Task::getAdapterForTasksLog($this->module, $this->id);

		$dpTasks = new ActiveDataProvider(array(
	      'query' => $query,
	      'pagination' => array(
	        'pageSize' => 10,
	      ),
  	));
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/tasks/widgets/views/_taskslog',array('dpTasks'=>$dpTasks));
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