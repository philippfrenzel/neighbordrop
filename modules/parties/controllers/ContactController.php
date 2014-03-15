<?php

namespace app\modules\parties\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use app\modules\parties\models\Contact;
use app\modules\parties\models\ContactSearch;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends AppController
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
	 * Lists all Contact models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ContactSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single Contact model.
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
	 * Creates a new Contact model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Contact;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Contact model.
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
	 * Deletes an existing Contact model.
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
	 * Finds the Contact model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Contact the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Contact::find($id)) !== null) {
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
  public function actionDhtmlxgrid($un=NULL, $posStart=0, $count=0,$search=NULL,$party_id=0){
    $currentPage = 0;
    $pageSize = 100;
    
    if($posStart>0){
      $currentPage = round(($posStart / $pageSize),0);
    }

    $query = new Query;
    if(is_Null($search))
    {
      $mainQuery = $query->select('tbl_contact.id AS id, contactName, department, email, phone')
      ->from('tbl_contact')
      ->where('party_id = ' . $party_id)
      ->all(); //just all records
    }
    else
    {
      $mainQuery = $query->select('tbl_contact.id AS id, contactName, department, email, phone')
      ->from('tbl_contact')
      ->where('contactName LIKE "%' . $search . '%" AND party_id = ' . $party_id)
      ->all(); 
    }
    $provider = new ArrayDataProvider([
      'allModels' => $mainQuery,
      'sort' => [
        'attributes' => ['id', 'contactName'],
      ],
      'pagination' => [
        'pageSize' => $pageSize,
        'page' => $currentPage
      ],
    ]);

    //the grid header to pass over total count
    $clean = ['total_count'=>Contact::find()->where(['party_id'=>$party_id])->count(),'pos'=>$posStart];
    foreach($provider->getModels() AS $record){
      if(!is_null($record))
      {
        $record['link'] = Url::to(['/parties/party/window','id'=>$record['id'],'win'=>'contact_update','mainid'=>$party_id]);
        $clean['rows'][]=['id'=>$record['id'],'data'=>array_values($record)];
      }
    }

    header('Content-type: application/json');
    echo Json::encode($clean);
    exit();
  }

  /**
   * [actionJsonlist description]
   * @param  [type] $search Text for the lookuk
   * @return [type]         [description]
   */
  public function actionJsonlist($search = NULL,$id = NULL)
  {
    header('Content-type: application/json');
    $clean['more'] = false;

    $query = new Query;
    if(!is_Null($search))
    {
      $mainQuery = $query->select('id, contactName AS text')
        ->from('tbl_contact')
        ->where('UPPER(contactName) LIKE "%'.strtoupper($search).'%"');

      $command = $mainQuery->createCommand();
      $rows = $command->queryAll();
      $clean['results'] = array_values($rows);
    }
    else
    {     
      if(!is_null($id))
      {
        $clean['results'] = ['id'=> $id,'text' => Contact::find($id)->contactName];
      }else
      {
        $clean['results'] = ['id'=> 0,'text' => 'None found'];
      }
    }
    echo Json::encode($clean);
    exit();
  }

  /**
   * [actionJsonlistemail description] will return the mails available on contacts
   * @param  [type] $search Text for the lookuk
   * @return [type]         [description]
   */
  public function actionJsonlistemail($search = NULL,$id = NULL)
  {
    header('Content-type: application/json');
    $clean['more'] = false;

    $query = new Query;
    if(!is_Null($search))
    {
      $mainQuery = $query->select('email AS id, email AS text')
        ->from('tbl_contact')
        ->where('UPPER(email) LIKE "%'.strtoupper($search).'%"');

      $command = $mainQuery->createCommand();
      $rows = $command->queryAll();
      $clean['results'] = array_values($rows);
    }
    else
    {     
      if(!is_null($id))
      {
        $clean['results'] = ['id'=> $id,'text' => Contact::find(['email'=>$id])->email];
      }else
      {
        $clean['results'] = ['id'=> 0,'text' => 'None found'];
      }
    }
    echo Json::encode($clean);
    exit();
  }

}
