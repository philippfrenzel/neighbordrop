<?php

namespace app\modules\comments\controllers;

use Yii;

use app\modules\app\controllers\AppController;

use yii\data\ActiveDataProvider;

use yii\helpers\Html;
use yii\helpers\Url;

use yii\helpers\Json;

use app\modules\comments\models\Comment;

class DefaultController extends AppController
{

	public function actionIndex()
	{
		$query = Comment::findRecentComments();

		$dpComments = new ActiveDataProvider(array(
		      'query' => $query,
		      'pagination' => array(
		          'pageSize' => 20,
		      ),
	  	));

		return $this->render('index', array(
			'dpComments' => $dpComments,
		));
	}

	/**
	 * views the comments related to the current module - id
	 * @param  integer $id     [description]
	 * @param  integer $module [description]
	 * @return html         [description]
	 */
	public function actionView($id,$module)
	{
		echo $this->renderPartial('windows/view_window',array(
			'module' => $module,
			'id'     => $id,
		));
	}

	/**
	 * will create a new commment
	 * @param  integer $id     [description]
	 * @param  integer $module [description]
	 * @return [type]         [description]
	 */
	public function actionCreate($id=NULL,$module=NULL)
	{
		$model=new Comment;
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$query = Comment::findRecentComments($model->comment_table, $model->comment_id);
			$dpComments = new ActiveDataProvider(array(
			  'query' => $query,
		  ));
			echo $this->renderPartial('@app/modules/comments/widgets/views/_comments_nonepjax',[
				'dpComments' =>$dpComments,
				'module'     =>$model->comment_table,
				'id'         =>$model->comment_id
			]);
		} else {
			$model->comment_id = $id;
			$model->comment_table = $module;
			$model->author_id = Yii::$app->user->id;

			return $this->renderPartial('_form_create', array(
				'model' => $model,
			));
		}
		
	}

	/**
	 * will create a window with the relevant form
	 * @param  [type] $id     [description]
	 * @param  [type] $module [description]
	 * @return [type]         [description]
	 */
	public function actionCreatewindow($id,$module){		
		//define the request target		
		$requestUrl = Url::to(array('default/createwindow','id'=>$id,'module'=>$module));
		
		$model=new Comment;
		$model->comment_id = $id;
		$model->comment_table = $module;
		$model->author_id = Yii::$app->user->id;
		if ($model->load(Yii::$app->request->post())) {
			if($model->save()){
				$myCounter = Comment::getAdapterForCommentCount($module,$id);
				header('Content-type: application/json');
				$myResponse = array('info'=>'Done!','id'=>$model->id,'content'=>$model->content,'newCount'=>$myCounter);
				echo Json::encode($myResponse);
				exit;
			}
			else{
				throw new \yii\web\HttpException(404,'ERROR happened, pls contact '.Yii::$app->params[adminEmail].'.');
			}
		}

		$this->layout = '/column1';
		echo $this->renderPartial('windows/update',array(
			'model'=>$model,
			'requestUrl' => $requestUrl,
		));
	}

}
