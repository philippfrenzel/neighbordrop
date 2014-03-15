<?php

namespace app\modules\purchase\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\db\Query;
use yii\data\ArrayDataProvider;

use app\modules\parties\models\Contact;
use app\modules\purchase\models\PartyForm;
use app\modules\purchase\models\PurchaseOrder;
use app\modules\purchase\models\PurchaseOrderGroup;
use app\modules\purchase\models\PurchaseOrderLine;
use app\modules\workflow\models\Workflow;

class DefaultController extends AppController
{
  
	public function actionIndex()
	{
		return $this->render('index');
	}

  public function actionCreateparty()
  {
    $model = new PartyForm;

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      if (\Yii::$app->request->isAjax) {
        header('Content-type: application/json');
        echo Json::encode(['status'=>'DONE','model'=>$model]);
        exit();
      }else{
        return $this->redirect(['view', 'id' => $model->id]);
      }
    } else {
      throw new NotFoundHttpException('The requested page does not exist.');
    }
  }

  /**
   * [actionSubmit description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function actionSubmit($id)
  {
    $model = $this->findModel($id);
    if($model->load(Yii::$app->request->post()))
    {
      $OrderGroups = PurchaseOrderGroup::find()->where(['purchaseorder_id' => $id])->all();
      //in ordergroups we have all orders split up by requester...
      foreach($OrderGroups AS $OrderGroup)
      {
        $OrderLines = PurchaseOrderLine::find()->where(['purchaseordergroup_id'=>$OrderGroup->id])->all();
        foreach($OrderLines AS $OrderLine)
        {
          $workflow = Workflow::addRecordIntoWorkflow(Workflow::MODULE_PURCHASE,$OrderLine->id,Workflow::STATUS_REQUESTED,$model->approver_id,Workflow::ACTION_APPROVE . ',' . Workflow::ACTION_REJECT);
          $OrderLine->status = Workflow::STATUS_REQUESTED;
          $OrderLine->save();
        }
        $OrderGroup->status = Workflow::STATUS_PENDING;
        $OrderGroup->save();
      }
      $model->status = Workflow::STATUS_PENDING;    
      $model->save();
    }
    return $this->redirect(['/site/index']);
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

    if($winparams[1]=='submit' OR $winparams[1]=='save')
    {
      $model = $model->find($id);
      $model->approver_id = Contact::find($model->contact_id)->reportsTo->id;
    }

    $query = new Query;
    $query->select([
      'tbl_purchaseordergroup.purchaseorder_id'
      ,'SUM(if(tbl_purchaseorderline.time_deleted IS NULL,1,0)) AS LineCounter'
      ,'SUM(if(tbl_purchaseordergroup.time_deleted IS NULL,1,0)) AS GroupLineCounter'
    ])
    ->from('tbl_purchaseordergroup')
    ->innerJoin('tbl_purchaseorderline','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id',['where'=>'tbl_purchaseorderline.time_deleted IS NULL'])
    ->where('tbl_purchaseordergroup.purchaseorder_id = '.$id.' AND tbl_purchaseordergroup.time_deleted IS NULL')
    ->groupBy('tbl_purchaseordergroup.purchaseorder_id')
    ->all(); //just all records

    // Create a command. 
    $command = $query->createCommand();
    // Execute the command:
    $rows = $command->queryAll();

    //@todo: purchasegroups need to be marked as delted when all lines below for the current purchase request are delted
    if($rows[0]['LineCounter']==0 OR is_null($rows))
    {
      $message = 'No purchase request edited';
    }

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $mainid]);
    } 
    else 
    {
      $showform = 'windows/_'.$winparams[1];
      if($winparams[1]=='save' && is_NUll($message)){
        return $this->redirect(['/site/index']);
      }
      else
      {        
        return $this->renderPartial('windows/base_window',[
            'model' => $model,
            'showform' => $showform,
            'message' => $message
        ]);
      } 
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
   * @return JSON               json object, see dhtmlx for more information
   */
  public function actionDhtmlxgrid($un=NULL, $posStart=0, $count=0,$search=NULL){
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
        'tbl_purchaseordergroup.id'
        ,'tbl_purchaseordergroup.status'
        ,'contactName'
        ,'FROM_UNIXTIME(tbl_purchaseordergroup.time_create,"%d-%m-%Y") AS CreationDate'
        ,'SUM(if(tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status = "approved" AND tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status = "rejected" AND tbl_purchaseorderline.time_deleted IS NULL,1,0))'
      ])
      ->from('tbl_purchaseordergroup')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_purchaseorder','tbl_purchaseordergroup.purchaseorder_id = tbl_purchaseorder.id')
      ->leftJoin('tbl_purchaseorderline','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id',['where'=>'tbl_purchaseorderline.time_deleted IS NULL'])
      ->where('tbl_purchaseordergroup.status = "pending" AND tbl_purchaseordergroup.time_deleted IS NULL AND approver_id = "'.\Yii::$app->user->getCurrentContactId().'"')
      ->groupBy('tbl_purchaseordergroup.id,status,contactName,tbl_purchaseordergroup.time_create')
      ->all(); //just all records
    }
    else
    {
      $mainQuery = $query
      ->select([
        'tbl_purchaseordergroup.id'
        ,'tbl_purchaseordergroup.status'
        ,'contactName'
        ,'FROM_UNIXTIME(tbl_purchaseordergroup.time_create,"%d-%m-%Y") AS CreationDate'
        ,'SUM(if(tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status = "approved" AND tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status = "rejected" AND tbl_purchaseorderline.time_deleted IS NULL,1,0))'
      ])
      ->from('tbl_purchaseordergroup')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_purchaseorder','tbl_purchaseordergroup.purchaseorder_id = tbl_purchaseorder.id')
      ->leftJoin('tbl_purchaseorderline','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id',['where'=>'tbl_purchaseorderline.time_deleted IS NULL'])
      ->where('order_number LIKE "%'.$search.'%" AND tbl_purchaseordergroup.status = "pending"'.' AND tbl_purchaseordergroup.time_deleted IS NULL AND approver_id = "'.\Yii::$app->user->getCurrentContactId().'"')
      ->groupBy('tbl_purchaseordergroup.id,status,contactName,tbl_purchaseordergroup.time_create')
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

    //the grid header to pass over total count
    $totalcounter = 0;
    foreach($provider->getModels() AS $record){
      if(!is_null($record))
      {
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


  /**
   * returns the json for the dhtmlx grid
   * @param  date  $un       YYYYMMDD
   * @param  integer $posStart current position in grid scroll
   * @param  integer $count    last record handed over
   * @return JSON               json object, see dhtmlx for more information
   */
  public function actionDhtmlxgridworkflow($un=NULL, $posStart=0, $count=0,$search=NULL,$mainid = null){
    $currentPage = 0;
    $pageSize = 100;
    
    if($posStart>0){
      $currentPage = round(($posStart / $pageSize),0);
    }

    $query = new Query;
    if(is_Null($search))
    {
      /*->select([
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
      
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_party','tbl_purchaseorderline.party_id = tbl_party.id')
      ->where('purchaseorder_id = '.$mainid)*/
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
        //,'contactName'
        //,'FROM_UNIXTIME(tbl_purchaseordergroup.time_create) AS CreationDate'
      ])
      ->from('tbl_purchaseorderline')
      ->leftJoin('tbl_purchaseordergroup','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_party','tbl_purchaseorderline.party_id = tbl_party.id')
      ->where('tbl_purchaseordergroup.status = "pending" AND tbl_purchaseordergroup.id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
      //->where('tbl_purchaseordergroup.status = "pending" AND parent_mail = "'.\Yii::$app->user->identity->email.'" AND tbl_purchaseordergroup.id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
      ->all(); //just all records
    }
    else
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
        //,'contactName'
        //,'FROM_UNIXTIME(tbl_purchaseordergroup.time_create) AS CreationDate'
      ])
      ->from('tbl_purchaseorderline')
      ->leftJoin('tbl_purchaseordergroup','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_party','tbl_purchaseorderline.party_id = tbl_party.id')
      ->where('tbl_purchaseordergroup.status = "pending" AND tbl_purchaseordergroup.id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
      //->where('tbl_purchaseordergroup.status = "pending" AND tbl_purchaseordergroup.id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
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
        $record['approvallink'] = Url::to(['/purchase/purchase-order-line/window','id'=>$record['id'],'win'=>'purchaseOrderLine_approveajax','mainid'=>$mainid]);
        $record['rejectedlink'] = Url::to(['/purchase/purchase-order-line/window','id'=>$record['id'],'win'=>'purchaseOrderLine_rejectajax','mainid'=>$mainid]);        
        //styling the background
        if($record['status']=='approved')
        {
          $style = 'background-color:green;color:white;'; // assign style
        }
        elseif($record['status']=='rejected')
        {
          $style = 'background-color:red;color:white;'; // assign style
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

  /**
   * returns the json for the dhtmlx grid
   * @param  date  $un       YYYYMMDD
   * @param  integer $posStart current position in grid scroll
   * @param  integer $count    last record handed over
   * @return JSON               json object, see dhtmlx for more information
   */
  public function actionDhtmlxgridapproved($un=NULL, $posStart=0, $count=0,$search=NULL,$status='approved'){
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
        'tbl_purchaseordergroup.id'
        ,'tbl_purchaseordergroup.status'
        ,'contactName'
        ,'FROM_UNIXTIME(tbl_purchaseordergroup.time_create,"%d-%m-%Y") AS CreationDate'
        ,'SUM(if(tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status = "approved" AND tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status = "rejected" AND tbl_purchaseorderline.time_deleted IS NULL,1,0))'
      ])
      ->from('tbl_purchaseordergroup')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_purchaseorder','tbl_purchaseordergroup.purchaseorder_id = tbl_purchaseorder.id')
      ->leftJoin('tbl_purchaseorderline','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id',['where'=>'tbl_purchaseorderline.time_deleted IS NULL'])
      ->where('tbl_purchaseordergroup.status = "'.$status.'" AND tbl_purchaseordergroup.time_deleted IS NULL AND email = "'.\Yii::$app->user->identity->email.'"')
      ->groupBy('tbl_purchaseordergroup.id,status,contactName,tbl_purchaseordergroup.time_create')
      ->all(); //just all records
    }
    else
    {
      $mainQuery = $query
      ->select([
        'tbl_purchaseordergroup.id'
        ,'tbl_purchaseordergroup.status'
        ,'contactName'
        ,'FROM_UNIXTIME(tbl_purchaseordergroup.time_create,"%d-%m-%Y") AS CreationDate'
        ,'SUM(if(tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status = "approved" AND tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status = "rejected" AND tbl_purchaseorderline.time_deleted IS NULL,1,0))'
      ])
      ->from('tbl_purchaseordergroup')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_purchaseorder','tbl_purchaseordergroup.purchaseorder_id = tbl_purchaseorder.id')
      ->leftJoin('tbl_purchaseorderline','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id',['where'=>'tbl_purchaseorderline.time_deleted IS NULL'])
      ->where('order_number LIKE "%'.$search.'%" AND tbl_purchaseordergroup.status = "'.$status.'" AND tbl_purchaseordergroup.time_deleted IS NULL AND email = "'.\Yii::$app->user->identity->email.'"')
      ->groupBy('tbl_purchaseordergroup.id,status,contactName,tbl_purchaseordergroup.time_create')
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

    //the grid header to pass over total count
    $totalcounter = 0;
    foreach($provider->getModels() AS $record){
      if(!is_null($record))
      {
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

  /**
   * returns the json for the dhtmlx grid
   * @param  date  $un       YYYYMMDD
   * @param  integer $posStart current position in grid scroll
   * @param  integer $count    last record handed over
   * @return JSON               json object, see dhtmlx for more information
   */
  public function actionDhtmlxgridegp($un=NULL, $posStart=0, $count=0,$search=NULL,$status='booked'){
    $currentPage = 0;
    $pageSize = 200;
    
    if($posStart>0){
      $currentPage = round(($posStart / $pageSize),0);
    }

    $query = new Query;
    if(is_Null($search))
    {
      $mainQuery = $query
      ->select([
        'tbl_purchaseordergroup.id'
        ,'tbl_purchaseordergroup.status'
        ,'contactName'
        ,'FROM_UNIXTIME(tbl_purchaseordergroup.time_create,"%d-%m-%Y") AS CreationDate'
        ,'SUM(if(tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status IN ("approved","booked") AND tbl_purchaseorderline.time_deleted IS NULL,1,0)) AS ST_APPROVED'
        ,'SUM(if(tbl_purchaseorderline.status = "rejected" AND tbl_purchaseorderline.time_deleted IS NULL,1,0)) AS ST_REJECTED'
        ,'SUM(if(tbl_purchaseorderline.status = "purchased" AND tbl_purchaseorderline.time_deleted IS NULL,1,0)) AS ST_PURCHASED'
      ])
      ->from('tbl_purchaseordergroup')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_purchaseorder','tbl_purchaseordergroup.purchaseorder_id = tbl_purchaseorder.id')
      ->leftJoin('tbl_purchaseorderline','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id',['where'=>'tbl_purchaseorderline.time_deleted IS NULL'])
      ->where('tbl_purchaseordergroup.status = "'.$status.'" AND tbl_purchaseordergroup.time_deleted IS NULL')
      ->groupBy('tbl_purchaseordergroup.id,status,contactName,tbl_purchaseordergroup.time_create')
      ->all(); //just all records
    }
    else
    {
      $mainQuery = $query
      ->select([
        'tbl_purchaseordergroup.id'
        ,'tbl_purchaseordergroup.status'
        ,'contactName'
        ,'FROM_UNIXTIME(tbl_purchaseordergroup.time_create,"%d-%m-%Y") AS CreationDate'
        ,'SUM(if(tbl_purchaseorderline.time_deleted IS NULL,1,0))'
        ,'SUM(if(tbl_purchaseorderline.status IN ("approved","booked") AND tbl_purchaseorderline.time_deleted IS NULL,1,0)) AS ST_APPROVED'
        ,'SUM(if(tbl_purchaseorderline.status = "rejected" AND tbl_purchaseorderline.time_deleted IS NULL,1,0)) AS ST_REJECTED'
        ,'SUM(if(tbl_purchaseorderline.status = "purchased" AND tbl_purchaseorderline.time_deleted IS NULL,1,0)) AS ST_PURCHASED'
      ])
      ->from('tbl_purchaseordergroup')
      ->leftJoin('tbl_contact','tbl_purchaseordergroup.contact_id = tbl_contact.id')
      ->leftJoin('tbl_purchaseorder','tbl_purchaseordergroup.purchaseorder_id = tbl_purchaseorder.id')
      ->leftJoin('tbl_purchaseorderline','tbl_purchaseorderline.purchaseordergroup_id = tbl_purchaseordergroup.id',['where'=>'tbl_purchaseorderline.time_deleted IS NULL'])
      ->where('order_number LIKE "%'.$search.'%" AND tbl_purchaseordergroup.status = "'.$status.'" AND tbl_purchaseordergroup.time_deleted IS NULL')
      ->groupBy('tbl_purchaseordergroup.id,status,contactName,tbl_purchaseordergroup.time_create')
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

    //the grid header to pass over total count
    $totalcounter = 0;
    foreach($provider->getModels() AS $record){
      if(!is_null($record) && ($record['ST_APPROVED'] > 0 OR $record['ST_PURCHASED'] > 0))
      {
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

  /**
   * returns the json for the dhtmlx grid
   * @param  date  $un       YYYYMMDD
   * @param  integer $posStart current position in grid scroll
   * @param  integer $count    last record handed over
   * @return JSON               json object, see dhtmlx for more information
   */
  public function actionDhtmlxgridegpdetail($un=NULL, $posStart=0, $count=0,$search=NULL,$mainid = null){
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
      ->where('tbl_purchaseorderline.status IN ("approved","booked","purchased") AND tbl_purchaseordergroup.id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
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
      ->where('tbl_purchaseorderline.status IN ("approved","booked","purchased") AND tbl_purchaseordergroup.id = '.$mainid.' AND tbl_purchaseorderline.time_deleted IS NULL')
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
        $record['updatelink'] = Url::to(['/purchase/purchase-order-line/window','id'=>$record['id'],'win'=>'purchaseOrderLine_update','mainid'=>$mainid]);
        $record['approvallink'] = Url::to(['/purchase/purchase-order-line/window','id'=>$record['id'],'win'=>'purchaseOrderLine_purchaseajax','mainid'=>$mainid]);
        $record['rejectedlink'] = Url::to(['/purchase/purchase-order-line/window','id'=>$record['id'],'win'=>'purchaseOrderLine_rejectajax','mainid'=>$mainid]);        
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

  public function actionGeneratepo($id)
  {
    $this->layout = "/main";
    $model = $this->findGroupModel($id);
    return $this->render('purchaseorder',[
      'model' => $model,
      'suppliers' => $model->suppliers
    ]);
  }

}
