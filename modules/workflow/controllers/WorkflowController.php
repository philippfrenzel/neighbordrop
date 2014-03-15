<?php

namespace app\modules\workflow\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use app\modules\workflow\models\Workflow;
use app\modules\workflow\models\WorkflowForm;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\VerbFilter;

/**
 * WorkflowController implements the CRUD actions for Workflow model.
 */
class WorkflowController extends AppController
{

	/**
	* @var string layout as default for the rendering
	*/
	public $layout='column3';

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
	 * Lists all Workflow models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new WorkflowForm;

		$query = Workflow::getAdapterForUserWorkflow();

		$dpWorkflow = new ActiveDataProvider(array(
		      'query' => $query,
		      'pagination' => array(
		          'pageSize' => 10,
		      ),
	  	));
		//$dataProvider = $searchModel->search($_GET);

		return $this->render('index', array(
			'dataProvider' => $dpWorkflow,
			//'searchModel' => $searchModel,
		));
	}

	/**
	 * Displays a single Workflow model.
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
	 * Creates a new Workflow model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Workflow;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(array('view', 'id' => $model->id));
		} else {
			return $this->render('create', array(
				'model' => $model,
			));
		}
	}

	/**
	 * Updates an existing Workflow model.
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
	 * Deletes an existing Workflow model.
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
	 * Finds the Workflow model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Workflow the loaded model
	 * @throws HttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Workflow::find($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}
}
