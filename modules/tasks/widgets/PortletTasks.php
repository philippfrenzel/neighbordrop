<?php
namespace app\modules\tasks\widgets;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\modules\tasks\models\Task;

class PortletTasks extends Portlet
{
	public $title='Tasks';
	
	public $module = 0;
	public $id = 0;

	/**
	 * @var string the CSS class for the portlet title tag. Defaults to 'portlet-title'.
	 */
	//public $titleCssClass='fg-color-black';

	/**
	 * @var string the CSS class for the portlet title tag. Defaults to 'portlet-content'.
	 */
	//public $contentCssClass='fg-color-black';

	public $enableAdmin = false;

	public $htmlOptions = array('class'=>'panel');
	public $titleCssClass = "panel panel-warning";

	public function init() {
		parent::init();
	}

	protected function renderContent()
	{
		$query = Task::getAdapterForTasksLog($this->module, $this->id);

		$dpTasks = new ActiveDataProvider(array(
		  'query' => $query,
	  ));
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/tasks/widgets/views/_tasks_nonepjax',['dpTasks'=>$dpTasks,'module'=>$this->module,'id'=>$this->id]);
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
			echo "<div class='{$this->titleCssClass}'><div class='panel-heading'><i class='fa fa-info'></i> {$this->title}</div>\n</div>\n";
		}
	}

}