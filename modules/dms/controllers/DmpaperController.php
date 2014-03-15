<?php

namespace app\modules\dms\controllers;

use Yii;

use app\modules\dms\models\Dmpaper;
use app\modules\dms\models\DmpaperSearch;
use app\modules\app\controllers\AppController;
use yii\web\NotFoundHttpException;
use yii\web\VerbFilter;

use yii\db\Query;
use yii\helpers\Json;

use \DateTime;

/**
 * DmpaperController implements the CRUD actions for Dmpaper model.
 */
class DmpaperController extends AppController
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
		];
	}

	/**
	 * Lists all Dmpaper models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new DmpaperSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Lists all Dmpaper models.
	 * @return mixed
	 */
	public function actionAssistant()
	{
		$searchModel = new DmpaperSearch;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('assistant', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Lists all Dmpaper models.
	 * @return mixed
	 */
	public function actionReviewer()
	{
		$searchModel = new DmpaperSearch;
		$dataProvider = $searchModel->searchReviewer($_GET);

		return $this->render('reviewer', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single Dmpaper model.
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
	 * Displays a single Dmpaper model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionReview($id)
	{
		return $this->render('review', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Dmpaper model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Dmpaper;
		$model->save();

		return $this->redirect(['update', 'id' => $model->id]);

		/*if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}*/
	}

	/**
	 * Updates an existing Dmpaper model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['/dms/dmpaper/index']);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Dmpaper model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
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
	 * Finds the Dmpaper model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Dmpaper the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if ($id !== null && ($model = Dmpaper::find($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	/**
	 * This will create a new Form, based upon the passed parameters.
	 * Inside base window, we only have the "light" version of the needed stuff
	 * @param  [type] $id  [description]
	 * @param  [type] $win [description]
	 * @return [type]      [description]
	 */
	public function actionWindow($win, $id=NULL,  $mainid=NULL)
	{
		$winparams = explode('_',$win);
		$modelClassName = '\\app\\modules\\dms\\models\\'.ucfirst($winparams[0]).'Form';
		$model = new $modelClassName;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $mainid]);
		} 
		else 
		{
			$showform = 'windows/_form_'.$winparams[0];
			return $this->renderAjax('windows/base_window',[
					'model' => $model,
					'showform' => $showform
			]);
		}
	}

	/**
   * actionJsonlistdepartment will return the mails available on contacts
   * @param  [type] $search Text for the lookuk
   * @return [type]         [description]
   */
  public function actionJsonlistfield($fieldname, $search = NULL,$id=NULL)
  {
    header('Content-type: application/json');
    $clean['more'] = false;

    $query = new Query;
    if(!is_Null($search))
    {
      $mainQuery = $query->select($fieldname.' AS id, '. $fieldname.' AS text')
        ->from('tbl_dmpaper')
        ->where('UPPER('.$fieldname.') LIKE "%'.strtoupper($search).'%"');

      $command = $mainQuery->createCommand();
      $rows = $command->queryAll();
      $clean['results'] = array_values($rows);
    }
    else{
    	$clean['results'] = ['id'=> $id,'text' => $id];
    }
    if(is_Null($id))
    {
    	$clean['results'][] = ['id'=> $search,'text' => $search];
    }
    echo Json::encode($clean);
    exit();
  }

}
