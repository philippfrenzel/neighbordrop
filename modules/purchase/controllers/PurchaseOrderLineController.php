<?php

namespace app\modules\purchase\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use app\modules\parties\models\Contact;
use app\modules\purchase\models\PurchaseOrderLine;
use app\modules\purchase\models\PurchaseOrderGroup;
use app\modules\purchase\models\PurchaseOrderLineSearch;
use app\modules\workflow\models\Workflow;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;

use yii\helpers\Json;
use yii\db\Query;
use yii\data\ArrayDataProvider;

use yii\helpers\Html;
use yii\helpers\Url;

use DateTime;

/**
 * PurchaseOrderLineController implements the CRUD actions for PurchaseOrderLine model.
 */
class PurchaseOrderLineController extends AppController
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
		];
	}

	/**
	 * Lists all PurchaseOrderLine models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PurchaseOrderLineSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single PurchaseOrderLine model.
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
	 * [actionWindow description]
	 * @param  [type] $id  [description]
	 * @param  [type] $win [description]
	 * @return [type]      [description]
	 */
	public function actionWindow($id, $win, $mainid)
	{
		$winparams = explode('_',$win);
		$modelClassName = '\\app\\modules\\purchase\\models\\'.ucfirst($winparams[0]);
		$model = new $modelClassName;

		if($winparams[1]=='update' || $winparams[1]=='deleteajax'|| $winparams[1]=='approveajax'|| $winparams[1]=='rejectajax' || $winparams[1]=='purchaseajax')
		{
			$model = $model->find($id);
		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $mainid]);
		} 
		else 
		{
			if(ucfirst($winparams[0]) == 'PurchaseOrderLine' && $winparams[1]=='update')
			{
				$pog = PurchaseOrderGroup::find($model->purchaseordergroup_id);
				$model->purchaseordergroup_id = $pog->contact_id;
				$model->purchaseorder_id = $pog->purchaseorder_id;				
			}
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
			else
			{
				$userContact = Contact::find()->where(['user_id' => \Yii::$app->user->currentContactId])->one();
				if($winparams[1]=='approveajax')
				{
					$workflow = Workflow::addRecordIntoWorkflow(Workflow::MODULE_PURCHASE,$id,Workflow::STATUS_APPROVED,$userContact->reportsTo->id,Workflow::ACTION_BOOK);
					$model->status = Workflow::STATUS_APPROVED;
				}
				elseif($winparams[1]=='purchaseajax')
				{
					$workflow = Workflow::addRecordIntoWorkflow(Workflow::MODULE_PURCHASE,$id,Workflow::STATUS_PURCHASED,$userContact->reportsTo->id,Workflow::ACTION_PURCHASE);
					$model->status = Workflow::STATUS_PURCHASED;
				}
				else
				{
					$workflow = Workflow::addRecordIntoWorkflow(Workflow::MODULE_PURCHASE,$id,Workflow::STATUS_REJECTED,$userContact->reportsTo->id,Workflow::ACTION_ARCHIVE);
					$model->status = Workflow::STATUS_REJECTED;
				}
				$model->save();
				header('Content-type: application/json');
				echo Json::encode($model);
				exit();
			}
		}
	}

	/**
	 * Creates a new PurchaseOrderLine model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new PurchaseOrderLine;

		if ($model->load(Yii::$app->request->post())) {

			//check if the group for the contact already exists, if not, create one, otherwise use the existing one...
			if(PurchaseOrderGroup::find()->where(['contact_id'=>$model->purchaseordergroup_id,'purchaseorder_id'=>$model->purchaseorder_id])->count()==1)
			{
				$model->purchaseordergroup_id = PurchaseOrderGroup::find()->where(['contact_id'=>$model->purchaseordergroup_id,'purchaseorder_id'=>$model->purchaseorder_id])->one()->id;
			}
			else
			{
				$pog = new PurchaseOrderGroup();
				$pog->contact_id = $model->purchaseordergroup_id;
				$pog->purchaseorder_id = $model->purchaseorder_id;
				$pog->save();

				$model->purchaseordergroup_id = $pog->id;
			}

			if($model->save())
			{
				if (\Yii::$app->request->isAjax) {
					header('Content-type: application/json');
					echo Json::encode(['status'=>'DONE']);
					exit();
				}else{	
					return $this->redirect(['view', 'id' => $model->id]);
				}
			}
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing PurchaseOrderLine model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post())) {
			
			//check if the group for the contact already exists, if not, create one, otherwise use the existing one...
			if(PurchaseOrderGroup::find()->where(['contact_id'=>$model->purchaseordergroup_id,'purchaseorder_id'=>$model->purchaseorder_id])->count()==1)
			{
				$model->purchaseordergroup_id = PurchaseOrderGroup::find()->where(['contact_id'=>$model->purchaseordergroup_id,'purchaseorder_id'=>$model->purchaseorder_id])->one()->id;
			}
			else
			{
				$pog = new PurchaseOrderGroup();
				$pog->contact_id = $model->purchaseordergroup_id;
				$pog->purchaseorder_id = $model->purchaseorder_id;
				$pog->save();

				$model->purchaseordergroup_id = $pog->id;
			}

			if($model->save())
			{
				if (\Yii::$app->request->isAjax) {
					header('Content-type: application/json');
					echo Json::encode(['status'=>'DONE']);
					exit();
				}else{
					return $this->redirect(['view', 'id' => $model->id]);
				}
			}
		} else {
			$pog = PurchaseOrderGroup::find($model->purchaseordergroup_id);
			$model->purchaseordergroup_id = $pog->contact_id;
			$model->purchaseorder_id = $pog->purchaseorder_id;
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing PurchaseOrderLine model.
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
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the PurchaseOrderLine model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return PurchaseOrderLine the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = PurchaseOrderLine::find($id)) !== null) {
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
	public function actionDhtmlxgrid($un=NULL, $posStart=0, $count=0,$search=NULL,$mainid=0){
		$currentPage = 0;
		$pageSize = 500;
		
		if($posStart>0){
			$currentPage = round(($posStart / $pageSize),0);
		}

		$query = new Query;
		if(is_Null($search) OR $search == '')
		{
			$mainQuery = $query
			->select([
				'contactName',
				'tbl_purchaseorderline.id',
				'IF(organisationName IS NULL,"no supplier selected",organisationName)',
				'article',
				'order_amount',
				'order_price',
				'order_currency',
				'(IF(order_price IS NULL,0,order_price) * IF(order_amount IS NULL,0,order_amount)) AS total_amount',
				'date_delivery'
			]) //FROM_UNIXTIME(tbl_purchaseorderline.time_create) AS CreationDate
			->from('tbl_purchaseorderline')
			->leftJoin('tbl_purchaseordergroup','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id')
			->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
			->leftJoin('tbl_party','tbl_purchaseorderline.party_id = tbl_party.id')
			->where('purchaseorder_id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
			->all(); //just all records
		}
		else
		{
			$mainQuery = $query
			->select([
				'contactName',
				'tbl_purchaseorderline.id',
				'IF(organisationName IS NULL,"no supplier selected",organisationName)',
				'article',
				'order_amount',
				'order_price',
				'order_currency',
				'(IF(order_price IS NULL,0,order_price) * IF(order_amount IS NULL,0,order_amount)) AS total_amount',
				'date_delivery'
			]) //FROM_UNIXTIME(tbl_purchaseorderline.time_create) AS CreationDate->from('tbl_purchaseorderline')
			->leftJoin('tbl_purchaseordergroup','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id')
			->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
			->leftJoin('tbl_party','tbl_purchaseorderline.party_id = tbl_party.id')
			->where('article LIKE "%'.$search.'%" AND purchaseorder_id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
			->all(); 
		}
		$provider = new ArrayDataProvider([
			'allModels' => $mainQuery,
 			'sort' => [
 				'attributes' => ['id', 'article'],
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
				$record['updatelink'] = Url::to(['/purchase/purchase-order-line/window','id'=>$record['id'],'win'=>'purchaseOrderLine_update','mainid'=>$mainid]);
				$record['deletelink'] = Url::to(['/purchase/purchase-order-line/window','id'=>$record['id'],'win'=>'purchaseOrderLine_deleteajax','mainid'=>$mainid]);
				$clean['rows'][]=['id'=>$record['id'],'data'=>array_values($record)];
				$totalcounter++;
			}
		}
		if($totalcounter == 0)
    {
      $clean['rows'][]=['id'=>0,'data'=>[]];
    }
		$clean['total_count']=$totalcounter;
    $clean['pos']=$posStart;

		header('Content-type: application/json');
		echo Json::encode($clean);
		exit();
	}

}
