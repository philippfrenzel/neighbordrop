<?php

namespace app\modules\posts\controllers;

use Yii;
use app\modules\app\controllers\AppController;

use app\modules\posts\models\Post;
use app\modules\posts\models\PostForm;
use yii\web\HttpException;
use yii\web\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends AppController
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
			'AccessControl' => [
        'class' => '\yii\web\AccessControl',
        'rules' => [
          [
            'allow'=>true,
            'actions'=>array(
              'index',
              'view',
              'create',
              'update',
              'delete',
              'disqus',
              'onlineview'
            ),
            'roles'=>array('@'),
          ],
          [
            'allow'=>true,
            'actions'=>array(
              'onlineview',
              'disqus'
            ),
            'roles'=>array('?'),
          ]
        ]
      ]
		];
	}

	/**
	 * Lists all Post models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PostForm;
		$dataProvider = $searchModel->search($_GET);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single Post model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('onlineview', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Displays a single Post model in online view.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionOnlineview($id)
	{
		return $this->render('onlineview', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Displays the comments for single Post model inside an Iframe...
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDisqus($id)
	{
		$this->layout = 'base_window';

		return $this->render('disqus', [
			'model' => $this->findModel($id)
		]);
	}

	/**
	 * Creates a new Post model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Post;

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Post model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Post model.
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
	 * Finds the Post model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Post the loaded model
	 * @throws HttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Post::find($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}
}
