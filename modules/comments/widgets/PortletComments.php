<?php
namespace app\modules\comments\widgets;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\modules\comments\models\Comment;
use app\modules\app\widgets\Portlet;

class PortletComments extends Portlet
{
	public $title=NULL;
	
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