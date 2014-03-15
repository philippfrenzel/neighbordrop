<?php

namespace app\modules\comments\controllers;

use Yii;

use app\modules\comments\models\Comment;
use app\modules\comments\models\CommentSearch;

use yii\data\ActiveDataProvider;
use app\modules\app\controllers\AppController;
use yii\web\HttpException;
use yii\web\VerbFilter;

use yii\helpers\Json;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends AppController
{
	public function behaviors()
	{
		return array(
			'verbs' => array(
				'class' => VerbFilter::className(),
				'actions' => array(
					'delete' => array('post'),
				),
			),
		);
	}

	/**
	 * Lists all Comment models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new CommentSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', array(
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		));
	}

	/**
	 * Displays a single Comment model.
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
	 * Creates a new Comment model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Comment;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(array('view', 'id' => $model->id));
		} else {
			return $this->render('create', array(
				'model' => $model,
			));
		}
	}

	/**
	 * Updates an existing Comment model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(array('view', 'id' => $model->id));
		} else {
			return $this->render('update', array(
				'model' => $model,
			));
		}
	}

	/**
	 * Deletes an existing Comment model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		if (\Yii::$app->request->isAjax) {
					header('Content-type: application/json');
					echo Json::encode(['status'=>'DONE']);
					exit();
		}else{
			return $this->redirect(['index']);
		}
	}

	/**
	 * Finds the Comment model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Comment the loaded model
	 * @throws HttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Comment::find($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}
}
