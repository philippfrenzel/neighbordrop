<?php

namespace app\modules\revision\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use yii\data\Sort;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\revision\models\Revision;
use app\modules\workflow\models\Workflow;

class DefaultController extends AppController
{
	public $_model=NULL;

	public function behaviors() {
		return array(
			'AccessControl' => array(
				'class' => '\yii\web\AccessControl',
				'rules' => array(
					array(
						'allow'=>true, 
						'roles'=>array('@'), // allow authenticated users to access all actions
					),
					array(
						'allow'=>false
					),
				)
			)
		);
	}

	public function actionIndex()
	{
		$query = \app\modules\revision\models\Revision::find();
		$sort = new Sort(array(
          'attributes' => array(
              'id',
              'revision_table'
        	),
      	));

      	$dpRevision = new ActiveDataProvider(array(
		      'query' => $query,
		      'pagination' => array(
		          'pageSize' => 10,
		      ),
		      'sort' => $sort
	  	));

		return $this->render('index',array(
			'dpRevision' => $dpRevision,
		));
	}

	public function actionView($id,$module)
	{
		echo $this->renderPartial('view_window',array(
			'module' => $module,
			'id'     => $id,
		));
	}

	public function actionUpdate($id){
		$model=$this->loadModel($id);
		if ($model->load(Yii::$app->request->post())) {
			if($model->save()){
				$myCounter = Revision::getAdapterForRevisionLogCount($model->revision_table,$module->revision_id);
				header('Content-type: application/json');
				$myResponse = array('info'=>'Done!','id'=>$model->id,'content'=>$model->content,'newCount'=>$myCounter);
				echo Json::encode($myResponse);
				exit;
			}
			else{
				throw new \yii\web\HttpException(404,'ERROR happened, pls contact '.Yii::$app->params[adminEmail].'.');
			}
		}
		//define the request target		
		$requestUrl = Url::to(array('default/update','id'=>$model->id));		

		$this->layout = 'column1';
		echo $this->renderPartial('update',array(
			'model'=>$model,
			'requestUrl' => $requestUrl,
		));
	}

	public function actionCreate($id,$module){		
		//define the request target		
		$requestUrl = Url::to(array('default/create','id'=>$id,'module'=>$module));
		
		$model=new Revision();
		$model->revision_id = $id;
		$model->revision_table = $module;
		$model->creator_id = Yii::$app->user->id;
		if ($model->load(Yii::$app->request->post())) {
			if($model->save()){
				$myCounter = Revision::getAdapterForRevisionLogCount($module,$id);
				header('Content-type: application/json');
				$myResponse = array('info'=>'Done!','id'=>$model->id,'content'=>$model->content,'newCount'=>$myCounter);
				echo Json::encode($myResponse);
				exit;
			}
			else{
				throw new \yii\web\HttpException(404,'ERROR happened, pls contact '.Yii::$app->params[adminEmail].'.');
			}
		}

		$this->layout = 'column1';
		echo $this->renderPartial('update',array(
			'model'=>$model,
			'requestUrl' => $requestUrl,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel($id='')
	{
		if($this->_model===null)
		{
			if(!empty($id))
			{
				$this->_model=Revision::find($id);				
			}
			if($this->_model===null)
				throw new \yii\web\HttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
