<?php

namespace app\modules\tasks\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use yii\db\Query;
use yii\web\VerbFilter;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

use yii\data\Sort;
use yii\data\ActiveDataProvider;

use app\modules\workflow\models\Workflow;
use app\modules\tasks\models\Task;

class DefaultController extends AppController
{
	//container for the current model
	private $_model = NULL;

	public function behaviors() {
		return array(
			'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['post'],
        ],
      ],
			'AccessControl' => array(
				'class' => '\yii\web\AccessControl',
				'rules' => array(
					array(
						'allow'=>true, 
						'roles'=>array('@'), // allow authenticated users to access all actions
					),
					array(
						'allow'=>false
					),
				)
			)
		);
	}

	/**
	 * [actionIndex description]
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$query= new Query();

		$query->select('task.*,task.status AS status ,workflow.status_to AS wf_status')
			  ->from('tbl_task task')
			  ->join('LEFT JOIN','tbl_workflow workflow','workflow.wf_id = (SELECT max(id) FROM tbl_workflow WHERE task.id = tbl_workflow.wf_id AND workflow.wf_table = '.Workflow::MODULE_TASKS.')')
			  ->where('status <> :status',array(':status'=>Workflow::STATUS_ARCHIVED));

		$sort = new Sort(array(
          'attributes' => array(
              'id',
              'task_table'
        	),
      	));

     $dpTasks = new ActiveDataProvider(array(
		      'query' => $query,
		      'pagination' => array(
		          'pageSize' => 10,
		      ),
		      'sort' => $sort
	  	));

		return $this->render('index',array(
			'dpTasks' => $dpTasks,
		));

	}

	/**
	 * [actionView description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionView($id){
		$model = $this->findModel($id);
		return $this->render('_form',array(
			'model'=>$model,
		));
	}

	/**
	 * [actionViewwindow description]
	 * @param  [type] $id     [description]
	 * @param  [type] $module [description]
	 * @return [type]         [description]
	 */
	public function actionViewwindow($id,$module)
	{
		echo $this->renderPartial('windows/view',array(
			'module' => $module,
			'id'     => $id,
		));
	}

	/**
	 * [actionUpdate description]
	 * @param  integer $id [description]
	 * @return [type]     [description]
	 */
	public function actionUpdate($id)
	{
    $model = $this->findModel($id);

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$query = Task::getAdapterForTasksLog($model->task_table, $model->task_id);
			$dpTasks = new ActiveDataProvider(array(
			  'query' => $query,
		  ));
			return $this->renderAjax('@app/modules/tasks/widgets/views/_tasks_nonepjax',[
				'dpTasks' 	 => $dpTasks,
				'module'     => $model->task_table,
				'id'         => $model->task_id
			]);
		} 
		else {
			$myForm = '_form_create';
			if (!\Yii::$app->request->isAjax) {
				$myForm = '_form';
			}
			return $this->renderAjax($myForm, array(
	        'model' => $model,
	    ));
	  }
	}

	/**
	 * [actionCreate description]
	 * @param  [type] $id     [description]
	 * @param  [type] $module [description]
	 * @return [type]         [description]
	 */
	public function actionCreate($id=NULL,$module=NULL)
	{
    $model = new Task;	    

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$query = Task::getAdapterForTasksLog($model->task_table, $model->task_id);
			$dpTasks = new ActiveDataProvider(array(
			  'query' => $query,
		  ));
			return $this->renderPartial('@app/modules/tasks/widgets/views/_tasks_nonepjax',[
				'dpTasks' 	 => $dpTasks,
				'module'     =>$model->task_table,
				'id'         =>$model->task_id
			]);
		} else {
			$model->task_id = $id;
			$model->task_table = $module;
			$model->creator_id = Yii::$app->user->id;

			return $this->renderAjax('_form_create', array(
				//'showform' => '_form_create',
				'model' => $model,
			));
		}
	}

	/**
	 * [actionCreatewindow description]
	 * @param  [type] $id     [description]
	 * @param  [type] $module [description]
	 * @return [type]         [description]
	 */
	public function actionCreatewindow($id=NULL,$module=NULL){		
		//define the request target		
		$requestUrl = Url::to(array('default/createwindow','id'=>$id,'module'=>$module));
		
		$model=new Task();
		if ($model->load(Yii::$app->request->post())) {
			if($model->save()){
				$myCounter = Task::getAdapterForTaskLogCount($module,$id);
				header('Content-type: application/json');
				$myResponse = array('info'=>'Done!','id'=>$model->id,'content'=>$model->content,'newCount'=>$myCounter);
				echo Json::encode($myResponse);
				exit;
			}
			else{
				throw new \yii\web\HttpException(404,'ERROR happened, pls contact '.Yii::$app->params[adminEmail].'.');
			}
		}

		$this->layout = '/main';
		
		$model->task_id = $id;
		$model->task_table = $module;
		$model->creator_id = Yii::$app->user->id;
		
		return $this->renderPartial('windows/update',array(
			'model'=>$model,
			'requestUrl' => $requestUrl,
		));
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
		if (($model = Task::find($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
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
		Workflow::deleteAll(['wf_table'=>Workflow::MODULE_TASKS, 'wf_id'=>$id]);
		$this->findModel($id)->delete();
		if (\Yii::$app->request->isAjax) {
					header('Content-type: application/json');
					echo Json::encode(['status'=>'DONE']);
					exit();
		}else{
			return $this->redirect(['index']);
		}
	}
}
