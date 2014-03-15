<?php

namespace app\modules\parties\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use app\modules\parties\models\Country;
use app\modules\parties\models\CountrySearch;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;

use yii\db\Query;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends AppController
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
	 * Lists all Country models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new CountrySearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single Country model.
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
	 * Creates a new Country model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Country;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Country model.
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
	 * Deletes an existing Country model.
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
	 * Finds the Country model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Country the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Country::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
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
			$mainQuery = $query->select('id, country_name AS text')
				->from('tbl_country')
				->where('UPPER(country_name) LIKE "%'.strtoupper($search).'%"');

			$command = $mainQuery->createCommand();
			$rows = $command->queryAll();
			$clean['results'] = array_values($rows);
		}
		else
		{			
			if(!is_null($id))
			{
				$clean['results'] = ['id'=> $id,'text' => Country::find($id)->country_name];
			}else
			{
				$clean['results'] = ['id'=> 0,'text' => 'None found'];
			}
		}
		echo Json::encode($clean);
		exit();
	}
}
