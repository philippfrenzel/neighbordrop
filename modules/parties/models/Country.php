<?php

namespace app\modules\parties\models;

/**
 * This is the model class for table "tbl_country".
 *
 * @property integer $id
 * @property string $country_code
 * @property string $country_name
 *
 * @property Address[] $addresses
 * @property Party[] $parties
 */
class Country extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_country';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['country_code'], 'string', 'max' => 2],
			[['country_name'], 'string', 'max' => 100]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'country_code' => 'Country Code',
			'country_name' => 'Country Name',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getAddresses()
	{
		return $this->hasMany(Address::className(), ['countryCode' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getParties()
	{
		return $this->hasMany(Party::className(), ['registrationCountryCode' => 'id']);
	}

  /**
   * [getCountryIdByCode description]
   * @param  string $code [description]
   * @return [type]       [description]
   */
  public static function getCountryIdByCode($code='AT')
  {
    return Country::find()->where(['country_code'=>$code])->One()->id;
  }

}
