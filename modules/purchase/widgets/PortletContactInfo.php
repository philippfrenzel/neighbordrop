<?php
namespace app\modules\purchase\widgets;

use Yii;
use yii\helpers\Html;
use app\modules\parties\models\Contact;

class PortletContactInfo extends Portlet
{
	/**
	 * [$id description]
	 * @var integer
	 */
	public $id = 0;

	public $htmlOptions=array('class'=>'panel panel-success');

	/**
	 * @var string the CSS class for the portlet title tag. Defaults to 'portlet-title'.
	 */
	public $titleCssClass='title-style';

	public $enableAdmin = false;

	protected function renderContent()
	{
		$model = Contact::find($this->id);
		//here we don't return the view, here we just echo it!
		echo $this->render('@app/modules/purchase/widgets/views/_contact',array('model' => $model));
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
			echo "<div class='panel-heading'><div class=\"{$this->titleCssClass}\"><i class='icon-info'></i> {$this->title}</div>\n</div>\n";
		}
	}
}