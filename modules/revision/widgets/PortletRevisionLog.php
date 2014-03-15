<?php
namespace app\modules\revision\widgets;

use \Yii;

use yii\helpers\Html;
use yii\data\ActiveDataProvider;

use app\modules\revision\widgets\Portlet;
use app\modules\revision\models\Revision;

class PortletRevisionLog extends Portlet
{
	public $title='Revision Log';
	
	public $module = 0;
	public $id = 0;

	public function init() {
		parent::init();
	}

	protected function renderContent()
	{
		$query = Revision::getAdapterForRevisionLog($this->module, $this->id);

		$dpRevision = new ActiveDataProvider(array(
		      'query' => $query,
		      'pagination' => array(
		          'pageSize' => 10,
		      ),
	  	));
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/revision/widgets/views/_revisionlog',array('dpRevision'=>$dpRevision));
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