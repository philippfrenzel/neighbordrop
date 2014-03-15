<?php

namespace app\modules\article\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use app\modules\article\models\Article;
use app\modules\article\models\Price;
use app\modules\article\models\ArticleSearch;
use app\modules\parties\models\Party;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;

use yii\db\Query;
use yii\helpers\Json;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends AppController
{

	public $layout = '/main';

	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
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
						'actions'=>array('update','create','index','view','delete','jsonlist'),
				    'roles'=>array('@'),
					)
				]
			],
			'disableCSRF' => [
        // required to disable csrf validation on OpenID requests
        'class' => \app\behaviours\CSRFdisableBehaviour::className(),
        'only' => array('importer'),
      ],
		];
	}

	/**
	 * Lists all Article models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ArticleSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single Article model.
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
	 * Creates a new Article model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Article;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Article model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		//change layout
		$this->layout = 'column3';
		
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
	 * Deletes an existing Article model.
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
	 * Finds the Article model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Article the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Article::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * [actionImporter description]
	 * @return [type] [description]
	 */
	public function actionImporter(){
		//logic for creating an article
		$model = $this->checkExisting($_POST['Article']['system_name'],$_POST['Article']['system_key']);
		$model->load(Yii::$app->request->post());
		$model->countryCode = \app\modules\parties\models\Country::getCountryIdByCode($model->countryCode);
		$model->save();

    $party_id = is_null($party = $this->checkExistingParty($_POST['Price']['system_name'],$_POST['Price']['system_key']))?$party->id:0;
		//logic for assigning or updating the related price
		$price = $this->checkPrice($model->id,$party_id,$_POST['Price']['price']);
		$price->load(Yii::$app->request->post());
    $price->party_id = $party_id;
		$price->article_id = $model->id;
		$price->save();

		exit();
	}

	/**
   * checks if an article record already exists so it will be updated
   * @param  string $system_name the name of the source system
   * @param  string $system_key  the id of the foreign system source
   * @return object model which is holding an found record or a new created on              
   */
  private function checkExisting($system_name,$system_key)
  {
    $testModel = Article::find()->where(['system_name' => $system_name, 'system_key' => $system_key])->One();
    if(is_object($testModel))
    {
      return $testModel;
    }
    $model = new Article;
    return $model;  
  }

  /**
   * checks if a party record already exists so it will be updated
   * @param  string $system_name the name of the source system
   * @param  string $system_key  the id of the foreign system source
   * @return object model which is holding an found record or a new created on              
   */
  private function checkExistingParty($system_name,$system_key)
  {
    $testModel = Party::find()->where(['system_name' => $system_name, 'system_key' => $system_key])->One();
    if(is_object($testModel))
    {
      return $testModel;
    }
    $model = new Party;
    $model->system_name = $system_name;
    $model->system_key = $system_key;
    $model->organisationName = $system_name . ' ' . $system_key;
    $model->save();
    return $model;
  }

  /**
   * checks if a record already exists so it will be updated
   * @param  string $system_name the name of the source system
   * @param  string $system_key  the id of the foreign system source
   * @param  integer $time_create unix timestamp as integer, of when this price was "created"
   * @return object model which is holding an found record or a new created on              
   */
  private function checkPrice($article_id,$party_id,$price)
  {
    $testModel = Price::find()->where(['article_id'=>$article_id, 'party_id' => $party_id, 'price' => $price])->One();
    if(is_object($testModel))
    {
      return $testModel;
    }
    $model = new Price;
    return $model;  
  }

  /**
   * [actionJsonlist description]
   * @param  [type] $search Text for the lookuk
   * @return [type]         [description]
   */
  public function actionJsonlist($search = NULL)
  {
    header('Content-type: application/json');

    $query = new Query;
    if(!is_Null($search))
    {
      $mainQuery = $query->select([
          "article as value"
          ,'tbl_price.party_id AS party_id'
          ,"organisationName"
          ,"GROUP_CONCAT(price,',') AS price"
        ])->distinct()
        ->from('tbl_article')
        ->leftJoin('tbl_price','tbl_price.article_id = tbl_article.id')
        ->leftJoin('tbl_party','tbl_price.party_id = tbl_party.id')
        ->where('UPPER(article) LIKE "%'.strtoupper($search).'%"')
        ->groupBy('article, tbl_price.party_id')
        ->limit(10);

      $command = $mainQuery->createCommand();
      $rows = $command->queryAll();
      foreach($rows AS $row){
        $clean[] = $row;
      }
    }
    $clean[] = ['value'=>$search,'party_id'=>NULL,'price'=>'0.00','organisationName'=>'No Supplier'];
    echo Json::encode($clean);
    exit();
  }

}
