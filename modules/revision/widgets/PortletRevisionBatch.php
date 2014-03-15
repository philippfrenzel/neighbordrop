<?php
namespace app\modules\revision\widgets;

use yii\helpers\Html;

use app\modules\revision\widgets\Portlet;
use app\modules\revision\models\Revision;

class PortletRevisionBatch extends Portlet
{
	public $title=NULL;
	
	public $module = 0;
	public $id = 0;

	public $contentCssClass="rev-portlet";

	public $enableAdmin = false;

	public function init() {
		parent::init();
	}

	protected function renderContent()
	{
		$countRevision = Revision::getAdapterForRevisionLogCount($this->module, $this->id);
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/revision/widgets/views/_revisionbatch',array('countRevision'=>$countRevision,'module'=>$this->module,'id'=>$this->id));
	}

	/**
	 * Renders the portlet admin toolbar
	 */
	public function renderToolbar(){
		//no toolbar needed
	}

}