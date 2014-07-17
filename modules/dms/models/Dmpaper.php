<?php

namespace app\modules\dms\models;

use \DateTime;
use app\modules\tags\models\Tag;
use app\modules\parties\models\Contact;
use app\modules\parties\models\Party;
use app\modules\comments\models\Comment;
use app\modules\workflow\models\Workflow;

use yii\helpers\Html;
use yii\db\Query;

/**
 * This is the model class for table "tbl_dmpaper".
 *
 * @property integer $id The primary key of this record
 * @property integer $party_id Who send in the document?
 * @property string $description A short description of the documents send in.
 * @property string $name the name of the docuemnt
 * @property string $tags how can the content be tagged
 * @property string $documenttype what kind of document is it
 * @property string $department to which department it was send
 * @property string $status what kind of status has the document right now
 * @property integer $creator_id who created the document
 * @property integer $time_deleted when was it delted
 * @property integer $time_create whe was it created
 * @property Party $party the related model for the party, pls check parties model for more details
 */
class Dmpaper extends \yii\db\ActiveRecord
{

  /**
   * List of statistical fields... dont't use them for anything else
   */
  public $Day = NULL;
  public $Inbox = NULL;

  /**
   * will include the custom scopes for this model
   * @return object enhanced query object
   */
  public static function createQuery($config = [])
  {
    $config['modelClass'] = get_called_class();
    return new DmpaperQuery($config);
  }

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_dmpaper';
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
	private $_oldTags;
  private $_oldRecipients;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['party_id', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['description'], 'string'],
			//[['creator_id'], 'required'], not requiered as it will be filled automatically before save
			[['name', 'status','documenttype','department'], 'string', 'max' => 255],
			['tags', 'match', 'pattern'=>'/^[\w\s,]+$/', 'message'=>'Tags can only contain word characters.'],
			['tags', 'normalizeTags'],
      ['recipients', 'normalizeRecipients'],
		];
	}

	/**
	 * @inheritdoc
	 * translate the labels so they will look correct
	 */
	public function attributeLabels()
	{
		return [
      'id'           => \Yii::t('app','ID'),
      'party_id'     => \Yii::t('app','Document Supplier'),
      'description'  => \Yii::t('app','Description'),
      'name'         => \Yii::t('app','Subject'),
      'status'       => \Yii::t('app','Status'),
      'department'   => \Yii::t('app','For Department'),
      'documenttype' => \Yii::t('app','Type of doc'),
      'creator_id'   => \Yii::t('app','Creator ID'),
      'time_deleted' => \Yii::t('app','Time Deleted'),
      'time_create'  => \Yii::t('app','Time Create'),
      'tags'         => \Yii::t('app','Tags'),
		];
	}

	/**
	 * This will return the party from whom the document was send in
	 * @return \yii\db\ActiveRelation
	 */
	public function getParty()
	{
		return $this->hasOne(Party::className(), ['id' => 'party_id']);
	}

	/**
   * [beforeSave description]
   * @param  [type] $insert [description]
   * @return [type]         [description]
   */
  public function beforeSave($insert)
  {
    $date = new DateTime('now');
    if($this->isNewRecord)
    {
      if(\Yii::$app->user->isGuest)
      {
        $this->creator_id = 0; //external system writer
      }
      else
      {
        $this->creator_id = \Yii::$app->user->identity->id;
      }      
    }
    if(is_null($this->time_create))
    {
      $this->time_create = $date->format("U");
    }
    return parent::beforeSave($insert);
  }

  /**
   * everything that has todo with the tags to this paper
   */
  
  /**
	 * @return array a list of links that point to the post list filtered by every tag of this post
	 */
	public function getTagLinks()
	{
		$links=array();
		foreach(Tag::string2array($this->tags) as $tag)
			$links[]=Html::a(Html::encode($tag), array('post/index', 'tag'=>$tag), array('class'=>'label'));
		return $links;
	}

  /**
   * @return array a list of links that point to the post list filtered by every tag of this post
   */
  public function getTagLabels()
  {
    $labels=array();
    foreach(Tag::string2array($this->tags) as $tag)
    {
      $labels[] = Html::tag('div', Html::encode($tag),['class'=>'label label-default']);
    }
    return $labels;
  }

	/**
	 * Normalizes the user-entered tags.
	 */
	public function normalizeTags($attribute,$params)
	{
		$this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
	}

  /**
   * Normalizes the user-entered recipients.
   */
  public function normalizeRecipients($attribute,$params)
  {
    $this->recipients = Contact::array2string(array_unique(Contact::string2array($this->recipients)));
  }

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	public function afterFind()
	{
		parent::afterFind();
		$this->_oldTags=$this->tags;
    $this->recipients = $this->getRecipientsList();
    $this->_oldRecipients=$this->recipients;    
	}

  private function getRecipientsList(){
    $query = new Query;
    $query->select('tbl_user.email AS recipient')
      ->distinct()
      ->from('tbl_workflow')
      ->innerJoin('tbl_user','tbl_workflow.next_user_id = tbl_user.id')
      ->where(['wf_table'=> Workflow::MODULE_DMPAPER, 'wf_id'=>$this->id])
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
	 * This is invoked after the record is saved.
	 */
	public function afterSave($insert,$changedAttributes)
	{
		parent::afterSave($insert,$changedAttributes);
		Tag::updateFrequency($this->_oldTags, $this->tags);
    self::updateRecipients($this->_oldRecipients, $this->recipients,$this->id);
	}

  /**
   * updateRecipients will add or remove recipients to the workflow for this document
   * @param  array $oldRecipients an array of the old Recipients linked to the workflow
   * @param  array $newRecipients an array of the new Recipients linked to the workflow
   * @return null
   * Implement the remove logic for the recipients
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
          Workflow::addRecordIntoWorkflow(WORKFLOW::MODULE_DMPAPER,$mainid,WORKFLOW::STATUS_REQUESTED,$id,WORKFLOW::ACTION_CHANGE);
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
        Workflow::removeRecordFromWorkflow(WORKFLOW::MODULE_DMPAPER,$mainid,$id);
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

  /**
  * @return query to get the workflow logs for a special entry
  * @param integer the id of the module - workflow table - see static params from Workflow Model
  * @param integer the primarey key value of the record within the linked table
  */
  public static function getStatisticForInbox()
  {
    return static::find()
      ->select([
        'FROM_UNIXTIME(time_create,"%Y %d %m") AS Day' //maybe FROM_UNIXTIME() needed
        ,'COUNT(id) AS Inbox'
        ])
      ->active()
      ->GroupBy(['FROM_UNIXTIME(time_create,"%Y %d %m")'])
      ->orderBy('time_create ASC');
  }

  public static function getStatisticForInboxByDays($days = 1)
  {
    return static::find()
      ->select([
        //'MAX(time_create)'
        'COUNT(id) AS Inbox'
      ])
      ->where('FROM_UNIXTIME(time_create) > NOW() - INTERVAL '.$days.' DAY')
      ->active();
  }

}
