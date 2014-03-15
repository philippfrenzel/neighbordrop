<?php

namespace app\modules\parties\models;

use \DateTime;

/**
 * This is the model class for table "tbl_address".
 *
 * @property integer $id
 * @property integer $party_id
 * @property string $postCode
 * @property string $streetDescription
 * @property string $addressLine
 * @property string $postBox
 * @property string $cityName
 * @property string $region
 * @property integer $countryCode
 * @property string $system_key
 * @property string $system_name
 * @property integer $system_upate
 * @property integer $creator_id
 * @property integer $time_deleted
 * @property integer $time_create
 *
 * @property Party $party
 */
class Address extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_address';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['party_id'], 'required'],
			[['party_id', 'countryCode', 'system_upate', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['postCode', 'postBox', 'cityName', 'region', 'system_key', 'system_name'], 'string', 'max' => 100],
			[['streetDescription', 'addressLine'], 'string', 'max' => 200]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                => \Yii::t('app','ID'),
			'party_id'          => \Yii::t('app','Party ID'),
			'postCode'          => \Yii::t('app','Post Code'),
			'streetDescription' => \Yii::t('app','Street Description'),
			'addressLine'       => \Yii::t('app','Address Line'),
			'postBox'           => \Yii::t('app','Post Box'),
			'cityName'          => \Yii::t('app','City Name'),
			'region'            => \Yii::t('app','Region'),
			'countryCode'       => \Yii::t('app','Country Code'),
			'system_key'        => \Yii::t('app','System Key'),
			'system_name'       => \Yii::t('app','System Name'),
			'system_upate'      => \Yii::t('app','System Upate'),
			'creator_id'        => \Yii::t('app','Creator ID'),
			'time_deleted'      => \Yii::t('app','Time Deleted'),
			'time_create'       => \Yii::t('app','Time Create'),
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
  public function getCountry()
  {
    return $this->hasOne(Country::className(), ['id' => 'countryCode']);
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

}
