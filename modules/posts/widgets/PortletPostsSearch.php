<?php
namespace app\modules\posts\widgets;

use Yii;
use yii\helpers\Html;

use app\modules\posts\models\Post;
use app\modules\posts\models\PostSearch;

class PortletPostsSearch extends \app\modules\app\widgets\AdminPortlet
{
	public $title='Post Search';

	public $contentCssClass='noStyler';
	public $htmlOptions=array('class'=>'noStyler');
	
	public $maxResults = 5;

	public $enableAdmin = false;

	public function init() {
		parent::init();
	}

	protected function renderContent()
	{
		$hits = NULL;
		$model = new PostSearch;
		if ($model->load(Yii::$app->request->post()))
		{
			if($model->searchstring!=='')
				$hits = Post::searchByString($model->searchstring)->all();
		}
		echo $this->render('@app/modules/posts/widgets/views/_search',array('model'=>$model,'hits'=>$hits));
	}
}