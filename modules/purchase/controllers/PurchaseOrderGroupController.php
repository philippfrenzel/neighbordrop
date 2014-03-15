<?php

namespace app\modules\purchase\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use app\modules\purchase\models\PurchaseOrderGroup;
use app\modules\purchase\models\PurchaseOrderLine;
use app\modules\purchase\models\PurchaseOrderGroupSearch;
use app\modules\workflow\models\Workflow;
use app\modules\parties\models\Contact;

use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;

use yii\db\Query;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;

/**
 * PurchaseOrderGroupController implements the CRUD actions for PurchaseOrderGroup model.
 */
class PurchaseOrderGroupController extends AppController
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all PurchaseOrderGroup models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PurchaseOrderGroupSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single PurchaseOrderGroup model.
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
	 * Creates a new PurchaseOrderGroup model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new PurchaseOrderGroup;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing PurchaseOrderGroup model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
   * [actionWindow description]
   * @param  [type] $id  [description]
   * @param  [type] $win [description]
   * @return [type]      [description]
   */
  public function actionWindow($win, $id=NULL)
  {
    $message = NULL;
    $winparams = explode('_',$win);
    $modelClassName = '\\app\\modules\\purchase\\models\\'.ucfirst($winparams[0]);
    $model = new $modelClassName;

    if($winparams[1]=='submit' || $winparams[1]=='purchasesubmit')
    {
      $model = $model->find($id);
    }

    $showform = 'windows/_'.$winparams[1];

    return $this->renderPartial('windows/base_window',[
        'model' => $model,
        'showform' => $showform
    ]);
  }

	/**
	 * [actionSubmit description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionSubmit($id)
	{
		$model = $this->findModel($id);
		$userContact = Contact::find()->where(['email' => \Yii::$app->params->egpmail])->one();
    $workflow = Workflow::addRecordIntoWorkflow(Workflow::MODULE_PURCHASE,$model->id,Workflow::STATUS_BOOKED,$userContact->id);
    $model->status = Workflow::STATUS_BOOKED;
    $model->save();

    $OrderLines = PurchaseOrderLine::find()->where(['purchaseordergroup_id'=>$model->id])->all();
    foreach($OrderLines AS $OrderLine)
    {
      $OrderLine->status = $OrderLine->status==Workflow::STATUS_APPROVED?Workflow::STATUS_BOOKED:Workflow::STATUS_REJECTED;
      $OrderLine->save();
    }

		return $this->redirect(['/site/index']);
	}

  /**
   * This function will submit the purchase request and allow the user to print out the approved items as purchase order
   * it is going to set the group status to purchased
   * @param  INTERGER $id Primary Key of the PurchaseOrderGroup
   * @return HTML     [description]
   */
  public function actionPurchasesubmit($id)
  {
    $model = $this->findModel($id);
    $workflow = Workflow::addRecordIntoWorkflow(Workflow::MODULE_PURCHASE,$model->id,Workflow::STATUS_PURCHASED,$model->contact_id);
    $model->status = Workflow::STATUS_PURCHASED;
    $model->save();
    return $this->redirect(['/site/index']);
  }

	/**
	 * Deletes an existing PurchaseOrderGroup model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		return $this->redirect(['index']);
	}

	/**
	 * Finds the PurchaseOrderGroup model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return PurchaseOrderGroup the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = PurchaseOrderGroup::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

  /**
   * [actionPurchase description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function actionPurchase($id)
  {
    //change layout
    $this->layout = '/main';
    $model = $this->findModel($id);
    return $this->render('@app/modules/purchase/views/purchase-order/purchase', [
      'model' => $model,
    ]);
  }

  /**
   * [actionPurchase description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function actionPending($id)
  {
    //change layout
    $this->layout = '/main';
    $model = $this->findModel($id);
    return $this->render('@app/modules/purchase/views/purchase-order/pending', [
      'model' => $model,
    ]);
  }

  /**
   * returns the json for the dhtmlx grid
   * @param  date  $un       YYYYMMDD
   * @param  integer $posStart current position in grid scroll
   * @param  integer $count    last record handed over
   * @return JSON               json object, see dhtmlx for more information
   */
  public function actionDhtmlxgrid($un=NULL, $posStart=0, $count=0,$search=NULL,$mainid = null){
    $currentPage = 0;
    $pageSize = 100;
    
    if($posStart>0){
      $currentPage = round(($posStart / $pageSize),0);
    }

    $query = new Query;
    if(is_Null($search))
    {
      $mainQuery = $query      
      ->select([
        //'if(tbl_purchaseorderline.status = "approved",1,0) AS Checkbox',
        'tbl_purchaseorderline.id',
        'IF(organisationName IS NULL,"no supplier selected",organisationName)',
        'article',
        'order_amount',
        'order_price',
        'order_currency',
        '(IF(order_price IS NULL,0,order_price) * IF(order_amount IS NULL,0,order_amount)) AS total_amount',
        'date_delivery',
        'tbl_purchaseorderline.status'
      ])
      ->from('tbl_purchaseorderline')
      ->leftJoin('tbl_purchaseordergroup','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_party','tbl_purchaseorderline.party_id = tbl_party.id')
      ->where('tbl_purchaseordergroup.id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
      ->all(); //just all records
    }
    else
    {
      $mainQuery = $query
      ->select([
        'tbl_purchaseorderline.id',
        'IF(organisationName IS NULL,"no supplier selected",organisationName)',
        'article',
        'order_amount',
        'order_price',
        'order_currency',
        '(IF(order_price IS NULL,0,order_price) * IF(order_amount IS NULL,0,order_amount)) AS total_amount',
        'date_delivery',
        'tbl_purchaseorderline.status'
      ])
      ->from('tbl_purchaseorderline')
      ->leftJoin('tbl_purchaseordergroup','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_party','tbl_purchaseorderline.party_id = tbl_party.id')
      ->where('tbl_purchaseordergroup.id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
      ->all(); 
    }
    $provider = new ArrayDataProvider([
      'allModels' => $mainQuery,
      'sort' => [
        'attributes' => ['CreationDate', 'status'],
      ],
      'pagination' => [
        'pageSize' => $pageSize,
        'page' => $currentPage
      ],
    ]);

    $totalcounter = 0;
    //the grid header to pass over total count
    foreach($provider->getModels() AS $record){
      if(!is_null($record))
      {
        //styling the background
        if($record['status']=='booked')
        {
          $style = 'background-color:LimeGreen;color:white;'; // assign style
        }
        elseif($record['status']=='purchased')
        {
          $style = 'background-color:green;color:white;'; // assign style
        }
        else
        {
          $style = '';
        }
        $clean['rows'][]=['id'=>$record['id'],'data'=>array_values($record),'style'=>$style];
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
