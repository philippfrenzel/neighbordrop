<?php

namespace app\modules\workflow\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use yii\data\Sort;
use yii\data\ActiveDataProvider;

use app\modules\workflow\models\Workflow;

class DefaultController extends AppController
{
	/**
	* @var string layout as default for the rendering
	*/
	public $layout='column2';

	/**
	* @var object current record as model
	*/
	private $_model=NULL;

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
		$query = Workflow::getAdapterForUserWorkflow();

		$dpWorkflow = new ActiveDataProvider(array(
		      'query' => $query,
		      'pagination' => array(
		          'pageSize' => 10,
		      ),
	  	));

		return $this->render('index',array(
			'dpWorkflow' => $dpWorkflow,
		));
	}

	public function actionSendworkflowmessage($module,$text){
		$wfMsg = new Messages();
		$wfMsg->reciever_id = (int)Yii::$app->user->identity->parent_user_id;
		$wfMsg->sender_id = (int)Yii::$app->user->identity->id;
		$wfMsg->subject = 'WFMESSAGE';
		$wfMsg->is_read = (string)'0';
		$wfMsg->date_create = Date('Y-m-d H:i:s');
		$wfMsg->body = $text;
		$wfMsg->module = strtoupper($module);		
		if(!$wfMsg->save())
			var_dump($wfMsg);
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
				$this->_model=Workflow::find($id);				
			}
			if($this->_model===null)
				throw new \yii\web\HttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
