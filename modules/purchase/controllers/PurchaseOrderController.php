<?php

namespace app\modules\purchase\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use app\modules\purchase\models\PurchaseOrder;
use app\modules\purchase\models\PurchaseOrderSearch;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\purchase\models\PurchaseOrderGroup;

use DateTime;
/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class PurchaseOrderController extends AppController
{

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['get'],
				],
			],
			'AccessControl' => [
				'class' => '\yii\web\AccessControl',
				'rules' => [
					[
						'allow'=>true,
						'actions'=>['importer'],
						'roles'=>['?'],
					],
					array(
						'allow'=>true,
						'actions'=>array('update','create','index','view','delete','dhtmlxgrid','create-request','window','workflow','windowoperations'),
				    'roles'=>array('@'),
					),  
					array(
						'allow'=>false,  // deny all users
					),
				]
			]
		];
	}

	/**
	 * Lists all PurchaseOrder models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PurchaseOrderSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single PurchaseOrder model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new PurchaseOrder model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new PurchaseOrder;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

  public function actionCreateRequest()
  {
    //change layout
    $this->layout = '/main';

    $model = new PurchaseOrder;
    $model->contact_id = \Yii::$app->user->CurrentContactId;
    
    if($model->save())
    {
    	$groupModel = new PurchaseOrderGroup;
    	$groupModel->contact_id = \Yii::$app->user->CurrentContactId;
 			$groupModel->purchaseorder_id = $model->id;
 			$groupModel->save();
   		return $this->redirect(['update', 'id' => $model->id]);
   	}else{
   		throw new NotFoundHttpException('The model cant be created.');
   	}
  }

  /**
	 * [actionWindow description]
	 * @param  [type] $id  [description]
	 * @param  [type] $win [description]
	 * @return [type]      [description]
	 */
	public function actionWindow($win, $id=NULL,  $mainid=NULL)
	{
		$winparams = explode('_',$win);
		$modelClassName = '\\app\\modules\\purchase\\models\\'.ucfirst($winparams[0]).'Form'; //'\\app\\modules\\parties\\models\\'.
		$model = new $modelClassName;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $mainid]);
		} 
		else 
		{
			$showform = 'windows/_form_'.$winparams[0];
			return $this->renderPartial('windows/base_window',[
					'model' => $model,
					'showform' => $showform
			]);
		}
	}

	/**
	 * [actionWindow description]
	 * @param  [type] $id  [description]
	 * @param  [type] $win [description]
	 * @return [type]      [description]
	 */
	public function actionWindowoperations($id, $win, $mainid)
	{
		$winparams = explode('_',$win);
		$modelClassName = '\\app\\modules\\purchase\\models\\'.ucfirst($winparams[0]);
		$model = new $modelClassName;

		if($winparams[1]=='update' || $winparams[1]=='deleteajax'|| $winparams[1]=='approveajax'|| $winparams[1]=='rejectajax')
		{
			$model = $model->find($id);
		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $mainid]);
		} 
		else 
		{
			$showform = '_form_compact_update';
			if($winparams[1]=='deleteajax'){
				$showform = '_form_delete';
			}
			if($winparams[1]=='update' || $winparams[1]=='deleteajax')
			{
				return $this->renderPartial('windows/base_window',[
						'model' => $model,
						'showform' => $showform
				]);
			}
		}
	}

	/**
	 * Updates an existing PurchaseOrder model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		//change layout
    $this->layout = '/main';
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			header('Content-type: application/json');
			echo Json::encode('DONE');
			exit();
			//return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('@app/modules/purchase/views/purchase-order/createrequest', [
				'model' => $model,
			]);
		}
	}

	/**
	 * [actionWorkflow description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionWorkflow($id)
	{
		//change layout
		$this->layout = '/main';
		$model = $this->findGroupModel($id);
		return $this->render('@app/modules/purchase/views/purchase-order/workflow', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing PurchaseOrder model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		//$this->findModel($id)->delete();
		$date = new DateTime('now');
		$model = $this->findModel($id);
		$model->time_deleted = $date->format("U");
		$model->save();
		if (\Yii::$app->request->isAjax) {
					header('Content-type: application/json');
					echo Json::encode(['status'=>'DONE']);
					exit();
		}else{
			return $this->redirect(['/site/index']);
		}
	}

	/**
	 * Finds the PurchaseOrder model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return PurchaseOrder the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = PurchaseOrder::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * Finds the PurchaseOrderGroup model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return PurchaseOrder the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findGroupModel($id)
	{
		if (($model = PurchaseOrderGroup::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * returns the json for the dhtmlx grid
	 * @param  date  $un       YYYYMMDD
	 * @param  integer $posStart current position in grid scroll
	 * @param  integer $count    last record handed over
	 * @return JSON            		json object, see dhtmlx for more information
	 */
	public function actionDhtmlxgrid($un=NULL, $posStart=0, $count=0,$search=NULL,$mainid=NULL){
		$currentPage = 0;
		$pageSize = 100;
		
		if($posStart>0){
			$currentPage = round(($posStart / $pageSize),0);
		}

		$currentUserId = \Yii::$app->user->identity->id;		

		$query = new Query;
		if(is_Null($search))
		{
			$mainQuery = $query
			->select([
				'tbl_purchaseorder.id',
				'tbl_purchaseorder.status',
				'order_number', 
				'organisationName', 
				'contactName', 
				'FROM_UNIXTIME(tbl_purchaseorder.time_create,"%d-%m-%Y") AS CreationDate'
			])
			->from('tbl_purchaseorder')
			->leftJoin('tbl_contact','tbl_purchaseorder.contact_id = tbl_contact.id')
			->leftJoin('tbl_party','tbl_contact.party_id = tbl_party.id')
			->where('status = "created" AND tbl_purchaseorder.creator_id = '.$currentUserId.' AND tbl_purchaseorder.time_deleted IS NULL')
			->all(); //just all records
		}
		else
		{
			$mainQuery = $query
			->select([
				'tbl_purchaseorder.id',
				'tbl_purchaseorder.status',
				'order_number',
				'organisationName',
				'contactName', 
				'FROM_UNIXTIME(tbl_purchaseorder.time_create,"%d-%m-%Y") AS CreationDate'
			])
			->from('tbl_purchaseorder')
			->leftJoin('tbl_contact','tbl_purchaseorder.contact_id = tbl_contact.id')
			->leftJoin('tbl_party','tbl_contact.party_id = tbl_party.id')
			->where('order_number LIKE "%'.$search.'%" AND status = "created" AND tbl_purchaseorder.creator_id = '.$currentUserId.' AND tbl_purchaseorder.time_deleted IS NULL')
			->all(); 
		}
		$provider = new ArrayDataProvider([
			'allModels' => $mainQuery,
 			'sort' => [
 				'attributes' => ['CreationDate', 'order_number'],
 			],
 			'pagination' => [
 				'pageSize' => $pageSize,
 				'page' => $currentPage
 			],
 		]); 		

		//the grid header to pass over total count
		$totalcounter = 0;
		foreach($provider->getModels() AS $record){
			if(!is_null($record))
			{
				$record['deletelink'] = Url::to(['/purchase/purchase-order/windowoperations','id'=>$record['id'],'win'=>'purchaseOrder_deleteajax','mainid'=>0]);                
				$clean['rows'][]=['id'=>$record['id'],'data'=>array_values($record)];
				$totalcounter++;
			}
		}
		if($totalcounter == 0)
    {
      $clean['rows'][]=['id'=>0,'data'=>[]];
    }
		$clean['total_count']=PurchaseOrder::find()->where(['status'=>'created','time_deleted'=>'IS NULL']);
    $clean['pos']=$posStart;

		header('Content-type: application/json');
		echo Json::encode($clean);
		exit();
	}

}
