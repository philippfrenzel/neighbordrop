<?php
namespace app\modules\comments\widgets;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\modules\comments\models\Comment;
use app\modules\app\widgets\Portlet;

class PortletDesignComments extends Portlet
{
	public $title='';
	
	public $module = 0;
	
	public $id = 0;

	public $enableAdmin = false;

	public $htmlOptions = array('class'=>'panel panel-warning');
	
	public $titleCssClass = "panel panel-info";

	public function init() {
		parent::init();
	}

	protected function renderContent()
	{
		$query = Comment::findRecentComments($this->module, $this->id);

		$dpComments = new ActiveDataProvider(array(
		  'query' => $query,
	  ));
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/comments/widgets/views/_comments_nonepjax',['dpComments'=>$dpComments,'module'=>$this->module,'id'=>$this->id]);
	}

}