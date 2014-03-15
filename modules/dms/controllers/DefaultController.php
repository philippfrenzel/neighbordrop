<?php

namespace app\modules\dms\controllers;

use \Yii;

use app\modules\dms\models\Dmsys;
use app\modules\dms\models\Dmpaper;

use app\modules\app\controllers\AppController;
use yii\web\VerbFilter;

use yii\helpers\Json;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

use \DateTime;

//all that has to do with the filesystem
//use Gaufrette\Filesystem;
//use Gaufrette\Adapter\Local as LocalAdapter;


class DefaultController extends AppController
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
              'form',
              'attachfile',
              'downloadattachement',
              'window',
              'delete',
              'getthumb',
              'getlatestthumb',
              'getlatestattachement',
              'jsonbarchartinbox'
            ),
            'roles'=>array('@'),
          ]
        ]
      ]
    ];
  } 	

  public function actionIndex()
	{
		return $this->render('index');
	}

  public function actionForm($id)
  {
      $model = $this->findModel($id);

      if ($model->load(Yii::$app->request->post())) {
          $model->fileattachement=UploadedFile::getInstance($model,'fileattachement');
          if ($model->validate()) {
              // form inputs are valid, do something here
              return;
          }
      }
      return $this->render('_form', [
          'model' => $model,
      ]);
  }

  /**
   * Will create and attach a new file to the related model
   * @return [type] [description]
   */
  public function actionAttachfile(){
    $model = new Dmsys;
    if($model->load(Yii::$app->request->post())) {
        $model->fileattachement=UploadedFile::getInstance($model,'fileattachement');
        if ($model->validate()) {
            $model->filename = $model->fileattachement->name;
            $model->filetype = $model->fileattachement->type;
            $model->used_space = $model->getReadableFileSize($model->fileattachement->size);
            
            //create dir if not exists
            $FHelper = new FileHelper;
            $FHelper->createDirectory(\Yii::$app->basePath."/attachements/".$model->dms_module);

            if($model->save() && $model->fileattachement->saveAs(\Yii::$app->basePath."/attachements/".$model->dms_module."/".$model->id))
            {              
              $model::generateThumb($model);
              return $this->redirect([$model::getModuleAsController($model->dms_module), 'id' => $model->dms_id]);
            }
            else
            {
              echo "upload failed";
            }
        }
        exit;
    }
    return $this->render('_form', [
        'model' => $model,
    ]);
  }

  /**
   * As files are not accessible directly, they can only be viewd by allowed users, so this will forward it...
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function actionDownloadattachement($id){
    $model = $this->findModel($id);

    // open the file in a binary mode
    $name = \Yii::$app->basePath."/attachements/".$model->dms_module."/".$model->id;
    $fp = fopen($name, 'rb');

    // send the right headers
    header("Content-Type: ".$model->filetype);
    header("Content-Length: " . filesize($name));

    // dump the picture and stop the script
    fpassthru($fp);
    exit;
  }

  /**
   * [actionGetthumb description]
   * @param  [type] $id   [description]
   * @param  string $size can be sm for small or lg for large
   * @return [type]       [description]
   */
  public function actionGetthumb($id,$size='sm'){
    $model = Dmsys::find($id);

    // open the file in a binary mode
    $name = \Yii::$app->basePath."/attachements/".$model->dms_module."/".$model->id."_thumb_".$size.".jpg";
    $fp = fopen($name, 'rb');

    // send the right headers
    header("Content-Type: image/jpeg");
    header("Content-Length: " . filesize($name));

    // dump the picture and stop the script
    fpassthru($fp);
    exit;
  }

  /**
   * [actionGetlatestthumb description]
   * @param  [type] $id   [description]
   * @param  string $size can be sm for small or lg for large
   * @return [type]       [description]
   */
  public function actionGetlatestthumb($id,$module,$size='sm'){
    //here i have to add the grepping the latest entry
    $model = Dmsys::getAdapterForFiles($module,$id)->One();
    if(!is_Null($model))
    {
      // open the file in a binary mode
      $name = \Yii::$app->basePath."/attachements/".$model->dms_module."/".$model->id."_thumb_".$size.".jpg";
      $fp = fopen($name, 'rb');
      // send the right headers
      header("Content-Type: image/jpeg");
      header("Content-Length: " . filesize($name));
      // dump the picture and stop the script
      fpassthru($fp);
    }
    
    exit;
  }

  /**
   * [actionGetlatestattachement description]
   * @param  [type] $id   [description]
   * @param  string $size can be sm for small or lg for large
   * @return [type]       [description]
   */
  public function actionGetlatestattachement($id,$module){
    //here i have to add the grepping the latest entry
    $model = Dmsys::getAdapterForFiles($module,$id)->One();
    if(!is_Null($model))
    {
      // open the file in a binary mode
      $name = \Yii::$app->basePath."/attachements/".$model->dms_module."/".$model->id;
      $fp = fopen($name, 'rb');
      // send the right headers
      header("Content-Type: ".$model->filetype);
      header("Content-Length: " . filesize($name));
      // dump the picture and stop the script
      fpassthru($fp);
    }

    exit;
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
    $modelClassName = '\\app\\modules\\dms\\models\\'.ucfirst($winparams[0]);
    $model = new $modelClassName;

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $mainid]);
    } 
    else 
    {
      if($winparams[1]=='delete' || $winparams[1]=='onlineview')
      {
        $model->id = $id;
      }
      $showform = 'windows/_'.$winparams[1];
      return $this->renderPartial('windows/base_window',[
          'model' => $model,
          'showform' => $showform
      ]);
    }
  }

  /**
   * will delete the record from database and remove the file from filesystem
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function actionDelete($id)
  {
    $date = new DateTime('now');
    $model = $this->findModel($id);
    $model->time_deleted = $date->format("U");
    $model->save();
    if (\Yii::$app->request->isAjax) {
      header('Content-type: application/json');
      echo Json::encode(['status'=>'DONE','model'=>$model]);
      exit();
    }else{
      return $this->redirect(['/dms/dmpaper/index']);
    }
  }

  /**
   * Finds the Dmsys model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Dmsys the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if ($id !== null && ($model = Dmsys::find($id)) !== null) {
      return $model;
    } else {
      throw new NotFoundHttpException('The requested page does not exist.');
    }
  }

  public function actionJsonbarchartinbox()
  {
    $series = Dmpaper::getStatisticForInbox()->asArray()->All();
    //var_dump($series);exit;
    header('Content-type: application/json');
    echo Json::encode($series);
    exit();
  }

}
