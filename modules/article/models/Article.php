<?php

namespace app\modules\article\models;

use \DateTime;

/**
 * This is the model class for table "tbl_article".
 *
 * @property integer $id
 * @property string $article
 * @property string $article_number
 * @property string $status
 * @property string $system_key
 * @property string $system_name
 * @property integer $system_upate
 * @property integer $creator_id
 * @property integer $time_deleted
 * @property integer $time_create
 * @property integer $countryCode
 *
 * @property Price[] $prices
 */
class Article extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_article';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			//[['system_key', 'system_name'], 'required'],
			[['system_upate', 'creator_id', 'time_deleted', 'time_create', 'countryCode'], 'integer'],
			[['article', 'article_number'], 'string', 'max' => 200],
			[['status'], 'string', 'max' => 255],
			[['system_key', 'system_name'], 'string', 'max' => 100]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'article' => 'Article',
			'article_number' => 'Article Number',
			'status' => 'Status',
			'system_key' => 'System Key',
			'system_name' => 'System Name',
			'system_upate' => 'System Upate',
			'creator_id' => 'Creator ID',
			'time_deleted' => 'Time Deleted',
			'time_create' => 'Time Create',
			'countryCode' => 'Country Code',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getPrices()
	{
		return $this->hasMany(Price::className(), ['article_id' => 'id']);
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
      $this->system_key = $this->id . $date->format("U");
    }
    if(is_null($this->time_create))
    {
      $this->time_create = $date->format("U");
    }
    $this->system_upate = $date->format("U");
    return parent::beforeSave($insert);    
  }

}
