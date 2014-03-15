<?php
namespace app\modules\revision\models;

use Yii;
use \yii\db\ActiveRecord;
use \yii\helpers\Html;

use \app\modules\workflow\models\Workflow;

class Revision extends ActiveRecord
{
	/**
     * @return string the associated database table name
     */
	public static function tableName()
    {
        return '{{tbl_revision}}';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(['content', 'revision_table', 'revision_id'], 'required'),
			array(['revision_table','revision_id', 'creator_id'],'integer'),
			array('status','string')
		);
	}

	//the author
	public function getCreator() {
		return $this->hasOne('User', array('id' => 'creator_id'));
	}

	//for each module we allow comments, we need to add a dynamic reference
	public function getTimeTable() {
		return $this->hasOne('TimeTable', array('id' => 'revision_id'));
			//->where('t1.revision_table = '.Workflow::MODULE_BLOG); //,'revision_table'=>'tbl_post'
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'content' => Yii::t('app','Comment'),
			'status' => Yii::t('app','Status'),
			'time_create' => Yii::t('app','Create Time'),
			'creator_id' => Yii::t('app','Name'),
			'revision_id' => Yii::t('app','Reference ID'),
			'revision_table' => Yii::t('app','Module'),
		);
	}

	/**
	 * @return string the hyperlink display for the current comment's author
	 */
	public function getCreatorLink()
	{
		/*if(!empty($this->url))
			return Html::a(Html::encode($this->author),$this->url);
		else*/
		return Html::encode($this->Creator->username);
	}


	/**
  * before we save the record, we will md5 the password
  */
  public function beforeSave($insert)
  {
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
        $this->time_create=time();
      }
      return parent::beforeSave($insert);        
  }

	/**
	* @return query to get the revision logs for a special entry
	* @param integer the id of the module - revsion_table - see static params from Workflow Model
	* @param integer the primarey key value of the record within the linked table
	*/
	public static function getAdapterForRevisionLog($module,$id)
	{
		return static::find()->where('revision_table = '.$module.' AND revision_id = '.$id);
	}

	/**
	* @return query to get the number of revision logs for a special entry
	* @param integer the id of the module - revsion_table - see static params from Workflow Model
	* @param integer the primarey key value of the record within the linked table
	*/
	public static function getAdapterForRevisionLogCount($module,$id)
	{
		return static::find()->where('revision_table = '.$module.' AND revision_id = '.$id)->Count();
	}

}
