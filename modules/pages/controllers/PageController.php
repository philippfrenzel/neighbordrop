<?php

namespace app\modules\pages\controllers;

use \Yii;

use app\models\User;

use app\modules\pages\models\Page;
use app\modules\pages\models\PageForm;

use yii\data\ActiveDataProvider;
use app\modules\app\controllers\AppController;
use yii\base\HttpException;
use yii\web\Repsonse;

use yii\helpers\Json;
use yii\helpers\StringHelper;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends AppController
{

	public $_model = NULL;

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
						'allow'=>true,
						'actions' => array('onlineview','connector'),
						'roles'=>array('?'),
					),
					array(
						'allow'=>false
					),
				)
			),
			'disableCSRF' => [
        // required to disable csrf validation on OpenID requests
        'class' => \app\modules\app\behaviours\CSRFdisableBehaviour::className(),
        'only' => array('connector'),
      ]
		);
	}

	/**
	 * Actions by class
	 * @return array
	 */
	public function actions()
	{
		return array(
			'connector' => array(
				'class' => 'yii2elfinder\ConnectorAction',				
				'clientOptions'=>array(
					'connectorRoute'=>'/pages/page/connector',
					'locale' => 'uk',	
					'roots'  => array(
			        array(
			        	  'rootAlias' => 'CMS Bilder',
			            'driver' => 'LocalFileSystem',
			            'path'   => dirname(__DIR__).'/../../web/img/',
			            'URL'    => '/img',				            
			            'mimeDetect' => 'internal',
			            'dotFiles' => false,
			            'uploadAllow' => array('image','pdf'),
			            'accessControl' => 'access'
			      )
				  ) 	
				)
			)
		);
	}

	/**
	 * Lists all Page models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PageForm;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', array(
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		));
	}

	/**
	 * Displays a single Page model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', array(
			'model' => $this->findModel($id),
		));
	}

	/**
	 * Displays a single Page model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionOnlineview($id)
	{
		$model = $this->findModel($id);
		if(strlen($model->template)>0)
			$this->layout = $model->template;

		return $this->render('onlineview', array(
			'model' => $model,
		));
	}

	/**
	* renders the file manager for the content management system
	* @return  mixed
	*/
	public function actionFilemanager(){
		$this->layout = '/main_blog';
		return $this->render('elfinder');
	}

	/**
	 * Creates a new Page model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate($id = NULL)
	{
		$model=new Page();
		if(!is_null($id))
			$model->parent_pages_id = $id;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(array('/pages/page/view','id'=>$model->id));
		}

		return $this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates an existing Page model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(array('onlineview', 'id' => $model->id));
		} else {
			return $this->render('update', array(
				'model' => $model,
			));
		}
	}

	/**
	 * Deletes an existing Page model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		return $this->redirect(array('index'));
	}

	/**
	 * Finds the Page model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Page the loaded model
	 * @throws HttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Page::find($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}

	/**
	 * Returns the parent data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function findParentModel($id='')
	{
		if($this->_model===null)
		{
			if(!empty($id))
			{
				$tmpModel = $this->findModel($id);
				$this->_model=Page::find($tmpModel->parent_pages_id);				
			}
			if($this->_model===null)
				throw new \yii\web\HttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Shows the diff of the current cms page compared to the version before
	 * @param  integer $id of the old page
	 * @return view the diff view
	 */
	public function actionDiffview($id){
		//changing layout
		$this->layout = 'column2';
		
		$model = $this->findParentModel($id);
		$compareModel = Page::find($id);

		$difftext = StringHelper::diff($compareModel->body,$model->body);

		return $this->render('view_diff',array(
			'difftext' => $difftext,
			'model'=>$model,
		));
	}

	/**
	 * jumps to the parent page of the current cms page
	 * @param  integer $id of the old page
	 * @return view the diff view
	 */
	public function actionViewparent($id){
		$model=$this->findParentModel($id);
		return $this->render('view',array(
			'model'=>$model,		
		));
	}

	/**
	 * creates the JSON for the dhtmlx tree object
	 * @param  integer $id     The id of the current id
	 * @param  integer $rootId The id of the shown root node
	 * @return JSON         Returns a json array, that can be parsed by dhtmlx tree component
	 */
	public function actionJsontreeview($id=NULL,$rootId=NULL)
	{
		$data = array();
		if(!is_NULL($rootId) AND is_null($id))
			$data = Page::rootTreeAsArray($rootId);
		else
			$data = Page::nodeChildren($id,true);
		echo Yii::$app->response->sendContentAsFile(Json::encode($data),'tree.json','application/json');
		exit;
	}
}
