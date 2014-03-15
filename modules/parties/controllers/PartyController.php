<?php

namespace app\modules\parties\controllers;

use app\modules\parties\models\Party;
use app\modules\parties\models\PartySearch;

use Yii;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use app\modules\app\controllers\AppController;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;
use yii\helpers\Json;

/**
 * PartyController implements the CRUD actions for Party model.
 */
class PartyController extends AppController
{

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
					'importer' => ['post'],
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
						'actions'=>array('update','create','index','view','delete','dhtmlxgrid','window','jsonlist'),
				    'roles'=>array('@'),
					)
				]
			],
			'disableCSRF' => [
        // required to disable csrf validation on OpenID requests
        'class' => \app\modules\app\behaviours\CSRFdisableBehaviour::className(),
        'only' => array('importer'),
      ],
		];
	}

	/**
	 * Lists all Party models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->render('index');
		/*$searchModel = new PartySearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);*/
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
		$modelClassName = '\\app\\modules\\parties\\models\\'.ucfirst($winparams[0]);
		$model = new $modelClassName;

		if($winparams[1]=='update')
		{
			$model = $model->find($id);
		}

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $mainid]);
		} else {
			if(ucfirst($winparams[0]) != 'Party')
			{
				$model->party_id = $mainid; //assign the party id upfront			
			}
			$showform = '../'.$winparams[0].'/_form';
			return $this->renderPartial('windows/base_window',[
					'model' => $model,
					'showform' => $showform
			]);
		}
	}

	/**
	 * Displays a single Party model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view_online', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Party model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Party;

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			if (\Yii::$app->request->isAjax) {
				header('Content-type: application/json');
				echo Json::encode(['status'=>'DONE','model'=>$model]);
				exit();
			}else{
				return $this->redirect(['view', 'id' => $model->id]);
			}
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Party model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Party model.
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
	 * Finds the Party model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Party the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Party::find($id)) !== null) {
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
	public function actionDhtmlxgrid($un=NULL, $posStart=0, $count=0,$search=NULL){
		$currentPage = 0;
		$pageSize = 100;
		
		if($posStart>0){
			$currentPage = round(($posStart / $pageSize),0);
		}

		$query = new Query;
		if(is_Null($search))
		{
			$mainQuery = $query->select('tbl_party.id AS id,system_name, system_key, organisationName,taxNumber,country_code')
			->from('tbl_party')
      ->leftJoin('tbl_country', 'tbl_country.id = tbl_party.registrationCountryCode')
			->all(); //just all records
		}
		else
		{
			$mainQuery = $query->select('tbl_party.id AS id,system_name, system_key,organisationName,taxNumber,country_code')
			->from('tbl_party')
      ->leftJoin('tbl_country', 'tbl_country.id = tbl_party.registrationCountryCode')
			->where('organisationName LIKE "%'.$search.'%"')
			->all(); 
		}
		$provider = new ArrayDataProvider([
			'allModels' => $mainQuery,
 			'sort' => [
 				'attributes' => ['id', 'organisationName'],
 			],
 			'pagination' => [
 				'pageSize' => $pageSize,
 				'page' => $currentPage
 			],
 		]);

		//the grid header to pass over total count
		$clean = ['total_count'=>Party::find()->count(),'pos'=>$posStart];
		foreach($provider->getModels() AS $record){
			if(!is_null($record))
			{
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
			$mainQuery = $query->select('id, organisationName AS text')
				->distinct()
				->from('tbl_party')
				->where('UPPER(organisationName) LIKE "%'.strtoupper($search).'%"');

			$command = $mainQuery->createCommand();
			$rows = $command->queryAll();
			$clean['results'] = array_values($rows);
		}
		else
		{			
			if(!is_null($id))
			{
				$clean['results'] = ['id'=> $id,'text' => Party::find($id)->organisationName];
			}else
			{
				$clean['results'] = ['id'=> 0,'text' => 'None found'];
			}
		}
		echo Json::encode($clean);
		exit();
	}

	/**
	 * [actionImporter description]
	 * @return [type] [description]
	 */
	public function actionImporter(){
		//checks for update/create a new party
		$model = $this->checkExisting($_POST['Party']['system_name'],$_POST['Party']['system_key']);
		$model->load(Yii::$app->request->post());
		$model->registrationCountryCode = \app\modules\parties\models\Country::getCountryIdByCode($model->registrationCountryCode);
		$model->save();

		//adding a new address to the party
		if(isset($_POST['Address']))
		{
			$address = $this->checkExistingAddress($_POST['Address']['system_name'],$_POST['Address']['system_key'],$model->id);
			$address->load(\Yii::$app->request->post());
			$address->party_id = $model->id;
			if($address->addressLine != '' AND $address->cityName != '')
			{
				$address->save();
			}
		}
		exit();
	}

	/**
	 * checks if a party record already exists so it will be updated
	 * @param  VARCHAR $system_name reference to the system that tries to import the record
	 * @param  VARCHAR $system_key  the internal key within the foreign system referenced by system_name
	 * @return OBJECT wether an existing or new party model, that will be filled with the post variables
	 */
	private function checkExisting($system_name,$system_key)
	{
		$testModel = Party::find()->where(['system_name' => $system_name, 'system_key' => $system_key])->One();
		if(is_object($testModel))
		{
			return $testModel;
		}
		$model = new Party;
		return $model;	
	}

	/**
	 * checks if a address record already exists so it will be updated
	 * @param  VARCHAR $system_name reference to the system that tries to import the record
	 * @param  VARCHAR $system_key  the internal key within the foreign system referenced by system_name
	 * @return OBJECT wether an existing or new address model, that will be filled with the post variables
	 */
	private function checkExistingAddress($system_name,$system_key,$party_id)
	{
		$testModel = Address::find()->where([
			'system_name' => $system_name, 
			'system_key' => $system_key,
			'party_id'=>$party_id
		])->One();
		if(is_object($testModel))
		{
			return $testModel;
		}
		$model = new Address;
		return $model;	
	}

}
