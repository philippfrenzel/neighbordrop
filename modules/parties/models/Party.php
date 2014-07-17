<?php

namespace app\modules\parties\models;

use \DateTime;

/**
 * New party class, adapted by Frenzel Gmbh 2013
 *
 * @author Philipp Frenzel <philipp@frenzel.net>
 * @version 0.1
 */

/**
 * This is the model class for table "tbl_party".
 *
 * @property integer $id
 * @property string $organisationName
 * @property string $partyNote
 * @property string $taxNumber
 * @property string $registrationNumber
 * @property integer $registrationCountryCode
 * @property integer $party_type
 * @property string $system_key
 * @property string $system_name
 * @property integer $system_upate
 * @property integer $creator_id
 * @property integer $time_deleted
 * @property integer $time_create
 *
 * @property Address[] $addresses
 * @property Contact[] $contacts
 */
class Party extends \yii\db\ActiveRecord
{

	/**
	* Here I create the constants for the party types
	*
	* @property party_type
	*/

  const TYPE_VENDOR  	 = 1;
  const TYPE_CUSTOMER  = 2;
  const TYPE_INTERNAL  = 3;

  public static $pTypes = [
    self::TYPE_VENDOR   => 'Vendor',
    self::TYPE_CUSTOMER => 'Customer',
    self::TYPE_INTERNAL => 'System'
  ];

  public static function getPartyTypeOptions()
  {
  	return self::$pTypes;
  }

  public function getPartyTypeAsString($type=NULL)
  {
    if(is_NULL($type))
    {
      $type = $this->party_type;
    }
    $options = self::getPartyTypeOptions();
    return isset($options[$type]) ? $options[$type] : '';
  }

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_party';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['organisationName'], 'required'],
			[['partyNote','taxNumber'], 'string'],
			[['registrationCountryCode', 'party_type', 'system_upate', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['organisationName'], 'string', 'max' => 255],
			[['taxNumber', 'registrationNumber', 'system_key', 'system_name'], 'string', 'max' => 100]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
      'id'                      => \Yii::t('app','ID'),
      'organisationName'        => \Yii::t('app','Organisation Name'),
      'partyNote'               => \Yii::t('app','Party Note'),
      'taxNumber'               => \Yii::t('app','Tax Number'),
      'registrationNumber'      => \Yii::t('app','Registration Number'),
      'registrationCountryCode' => \Yii::t('app','Registration Country Code'),
      'party_type'              => \Yii::t('app','Party Type'),
      'partyTypeAsString'       => \Yii::t('app','Party Type'),
      'system_key'              => \Yii::t('app','System Key'),
      'system_name'             => \Yii::t('app','System Name'),
      'system_upate'            => \Yii::t('app','System Upate'),
      'creator_id'              => \Yii::t('app','Creator ID'),
      'time_deleted'            => \Yii::t('app','Time Deleted'),
      'time_create'             => \Yii::t('app','Time Create'),
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getAddresses()
	{
		return $this->hasMany(Address::className(), ['party_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getContacts()
	{
		return $this->hasMany(Contact::className(), ['party_id' => 'id']);
	}

  /**
   * @return \yii\db\ActiveRelation
   */
  public function getCountry()
  {
    return $this->hasOne(Country::className(), ['id' => 'registrationCountryCode']);
  }

  /**
   * @return string name of country
   */
  public function getCountryName()
  {
    return $this->country->country_name;
  }

  /**
   * [beforeSave description]
   * @param  [type] $insert [description]
   * @return [type]         [description]
   */
  public function afterSave($insert,$changedAttributes)
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
      if(is_null($this->party_type) OR $this->party_type == '')
      {
        $this->party_type = self::TYPE_VENDOR;
      }      
    }
    if($this->system_name == '' OR is_Null($this->system_name))
    {
      $this->system_name = 'PUREPO';
      $this->system_key = $this->id;
    }
    if(is_null($this->time_create))
    {
      $this->time_create = $date->format("U");
    }
    $this->system_upate = $date->format("U");
    return parent::afterSave($insert,$changedAttributes);    
  }

}
