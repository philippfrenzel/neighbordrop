<?php

namespace app\modules\tasks\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\workflow\models\Workflow;
use app\modules\parties\models\Contact;

use yii\db\Query;

/**
 * This is the model class for table "tbl_task".
 *
 * @property integer $id
 * @property string $content
 * @property string $status
 * @property integer $creator_id
 * @property integer $time_create
 * @property integer $task_table
 * @property integer $task_id
 *
 * @property  $creator
 */
class Task extends \yii\db\ActiveRecord
{
  /**
   * will include the custom scopes for this model
   * @return object enhanced query object
   */
  public static function createQuery($config = [])
  {
    $config['modelClass'] = get_called_class();
    return new TaskQuery($config);
  }

  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return 'tbl_task';
  }

  /**
   * an array of recipients which will be build from entries within the workflowtable
   * @var array recipients
   */
  public $recipients = NULL;

  /**
   * storing the old tags into this variable
   * @var [type]
   */
  private $_oldRecipients;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['content', 'string'],
			// not needed here, as it will be set in before Save! ['creator_id', 'required'],
			[['creator_id', 'time_create', 'task_table', 'task_id'], 'integer'],
			['status', 'string', 'max' => 255],
      ['recipients', 'normalizeRecipients'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
      'id'          => 'ID',
      'content'     => Yii::t('app','Todo'),
      'status'      => Yii::t('app','Status'),
      'recipients'  => Yii::t('app','Recipients'),
      'creator_id'  => Yii::t('app','Creator'),
      'time_create' => Yii::t('app','Created'),
      'task_table'  => Yii::t('app','Module'),
      'task_id'     => Yii::t('app','Module PK'),
		];
	}

  /**
   * This is invoked when a record is populated with data from a find() call.
   */
  public function afterFind()
  {
    parent::afterFind();
    $this->recipients = $this->getRecipientsList();
    $this->_oldRecipients=$this->recipients;
  }

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	public function beforeSave($insert)
	{
		if ($this->isNewRecord) {
			$this->creator_id  = Yii::$app->user->id;
			$this->time_create = time();
		}
    return parent::beforeSave($insert);
	}

	/**
  * This is invoked after the record is saved. 
  * @todo needs to be programmed later in detail, as we gain more knowledge about user needs!
  */
  public function afterSave($insert){
    parent::afterSave($insert);
    self::updateRecipients($this->_oldRecipients, $this->recipients,$this->id);
  }

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getCreator()
	{
		return $this->hasOne('User', ['id' => 'creator_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getWorkflow()
	{
		return $this->hasOne('\app\modules\workflow\models\Workflow', ['wf_id' => 'id'])
					->where('wf_table = '.Workflow::MODULE_TASKS)
					->orderBy('date_create DESC');
	}

	  /**
    * @return query to get the workflow logs for a special entry
    * @param integer the id of the module - workflow table - see static params from Workflow Model
    * @param integer the primarey key value of the record within the linked table
    */
    public static function getAdapterForTasksLog($module,$id)
    {
        return static::find()->where('task_table = '.$module.' AND task_id = '.$id);
    }

    /**
    * @return query to get the number of workflow logs for a special entry
    * @param integer the id of the module - workflow table - see static params from Workflow Model
    * @param integer the primarey key value of the record within the linked table
    */
    public static function getAdapterForTaskLogCount($module,$id)
    {
        return static::find()->where('task_table = '.$module.' AND task_id = '.$id)->Count();
    }

    /**
    * @return string the URL that shows the detail of the pages
    */
    public static function getUrl($module,$id,$action='view')
    {      
      return Url::to(['/'.Workflow::getModuleAsController($module).'/'.$action,
        'id'=>$id,
      ]);
    }

  /**
   * Normalizes the user-entered recipients.
   */
  public function normalizeRecipients($attribute,$params)
  {
    $this->recipients = Contact::array2string(array_unique(Contact::string2array($this->recipients)));
  }

  private function getRecipientsList(){
    $query = new Query;
    $query->select('tbl_user.email AS recipient')
      ->distinct()
      ->from('tbl_workflow')
      ->innerJoin('tbl_user','tbl_workflow.next_user_id = tbl_user.id')
      ->where(['wf_table'=> Workflow::MODULE_TASKS, 'wf_id'=>$this->id])
      ->all();
    
    $command = $query->createCommand();
    $rows = $command->queryAll();

    $recipients = array();
    foreach($rows AS $row){
      $recipients[] = $row['recipient'];
    }
    return self::array2string($recipients);
  }

  /**
   * updateRecipients will add or remove recipients to the workflow for this document
   * @param  array $oldRecipients an array of the old Recipients linked to the workflow
   * @param  array $newRecipients an array of the new Recipients linked to the workflow
   * @return null
   * @todo Implement the remove logic for the recipients
   */
  public static function updateRecipients($oldRecipients, $newRecipients, $id)
  {
      $oldRecipients=self::string2array($oldRecipients);
      $newRecipients=self::string2array($newRecipients);
      self::addRecipients(array_values(array_diff($newRecipients,$oldRecipients)),$id);
      if(count($oldRecipients)>0){
          self::removeRecipients(array_values(array_diff($oldRecipients,$newRecipients)),$id);    
      }
  }

  /**
   * [addRecipients description]
   * @param array $recipients a list of emails, stored in an array to be walked through
   */
  public static function addRecipients($recipients,$mainid)
  {      
    if (count($recipients)>0) {
      foreach($recipients as $email) {
        $id = \Yii::$app->user->getUserByEmailId($email);
        if(!is_null($id))
        {
          $nextActions = Workflow::ACTION_REJECT.','.Workflow::ACTION_APPROVE.','.Workflow::ACTION_CHANGE;
          Workflow::addRecordIntoWorkflow(WORKFLOW::MODULE_TASKS,$mainid,WORKFLOW::STATUS_REQUESTED,$id,$nextActions);
        }        
      }
    }
  }

  /**
   * [removeRecipients description]
   * @param array $recipients a list of emails, stored in an array to be walked through
   * @return [type]             [description]
   */
  public static function removeRecipients($recipients,$mainid)
  {
    if(count($recipients)==0)
        return;
    foreach($recipients as $email) {
      $id = \Yii::$app->user->getUserByEmailId($email);
      if(!is_null($id))
      {
        Workflow::removeRecordFromWorkflow(WORKFLOW::MODULE_TASKS,$mainid,$id);
      }
    }
  }

  /**
   * [string2array description]
   * @param  [type] $tags [description]
   * @return [type]       [description]
   */
  public static function string2array($tags)
  {
      return explode(',',trim($tags));
  }

  /**
   * [array2string description]
   * @param  [type] $tags [description]
   * @return [type]       [description]
   */
  public static function array2string($tags)
  {
      return implode(',',$tags);
  }


}
