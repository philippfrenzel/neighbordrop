<?php

namespace app\modules\parties\models;

use \DateTime;

/**
 * This is the model class for table "tbl_contact".
 *
 * @property integer $id
 * @property integer $party_id
 * @property string $contactName
 * @property string $department
 * @property string $email
 * @property string $parent_mail
 * @property string $backup_mail
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property integer $user_id
 * @property string $system_key
 * @property string $system_name
 * @property integer $system_upate
 * @property integer $creator_id
 * @property integer $time_deleted
 * @property integer $time_create
 *
 * @property Party $party
 */
class Contact extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_contact';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['party_id', 'contactName'], 'required'],
			[['party_id', 'user_id', 'system_upate', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['contactName'], 'string', 'max' => 255],
			[['department', 'phone', 'mobile', 'fax', 'system_key', 'system_name'], 'string', 'max' => 100],
			[['email','parent_mail','backup_mail'], 'string', 'max' => 200]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'           => \Yii::t('app','ID'),
			'party_id'     => \Yii::t('app','Party ID'),
			'contactName'  => \Yii::t('app','Contact Name'),
			'department'   => \Yii::t('app','Department'),
			'email'        => \Yii::t('app','Email'),
      'backup_mail'  => \Yii::t('app','Backuped by'),
      'parent_mail'  => \Yii::t('app','Reports to'),
      'email'        => \Yii::t('app','Email'),
			'phone'        => \Yii::t('app','Phone'),
			'mobile'       => \Yii::t('app','Mobile'),
			'fax'          => \Yii::t('app','Fax'),
			'user_id'      => \Yii::t('app','User ID'),
			'system_key'   => \Yii::t('app','System Key'),
			'system_name'  => \Yii::t('app','System Name'),
			'system_upate' => \Yii::t('app','System Upate'),
			'creator_id'   => \Yii::t('app','Creator ID'),
			'time_deleted' => \Yii::t('app','Time Deleted'),
			'time_create'  => \Yii::t('app','Time Create'),
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getParty()
	{
		return $this->hasOne(Party::className(), ['id' => 'party_id']);
	}

  /**
   * @return \yii\db\ActiveRelation
   */
  public function getReportsTo()
  {
    return $this->hasOne(Contact::className(), ['email' => 'parent_mail']);
  }

  /**
   * @return \yii\db\ActiveRelation
   */
  public function getBackupedBy()
  {
    return $this->hasOne(Contact::className(), ['email' => 'backup_mail']);
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
    if($this->system_name == '' OR is_Null($this->system_name))
    {
    	$this->system_name = 'PUREPO';
    }
    if(is_null($this->time_create))
    {
      $this->time_create = $date->format("U");
    }
    $this->system_upate = $date->format("U");
    return parent::beforeSave($insert);
  }

  /**
   * [string2array description]
   * @param  [type] $recipients [description]
   * @return [type]       [description]
   */
  public static function string2array($recipients)
  {
      return explode(',',trim($recipients));
  }

  /**
   * [array2string description]
   * @param  [type] $recipients [description]
   * @return [type]       [description]
   */
  public static function array2string($recipients)
  {
      return implode(',',$recipients);
  }

  public static function getDepartments()
  {
    
  }
  
}
