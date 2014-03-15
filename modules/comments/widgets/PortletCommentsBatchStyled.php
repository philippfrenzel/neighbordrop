<?php
namespace app\modules\comments\widgets;

use yii\helpers\Html;

use app\modules\comments\models\Comment;
use app\modules\app\widgets\Portlet;

class PortletCommentsBatchStyled extends Portlet
{
	public $title='Kommentare';
	
	public $module = 0;
	public $id = 0;

	public $mode = NULL;

	public $contentCssClass="com-portlet";

	/**
	 * should the admin toolbar be shown?
	 * @var boolean
	 */
	public $enableAdmin = false;

	/**
	 * should the comments log be shown?
	 * @var boolean
	 */
	public $enableCommentsLog = true;

	protected function renderContent()
	{
		$countComments = Comment::getAdapterForCommentCount($this->module, $this->id);
		//here we don't return the view, here we just echo it!
		if(is_NULL($this->mode))
		{
			$myview = '_commentsbatch_styled';
		}
		else
		{
			$myview = '_commentsbatch_window_styled';
		}
		echo $this->render('@app/modules/comments/widgets/views/'.$myview,array('countComments'=>$countComments,'module'=>$this->module,'id'=>$this->id,'enableCommentsLog'=>$this->enableCommentsLog,'mode'=>$this->mode));
	}

}