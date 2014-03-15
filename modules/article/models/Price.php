<?php

namespace app\modules\article\models;

use app\modules\parties\models\Party;

use \DateTime;

/**
 * This is the model class for table "tbl_price".
 *
 * @property integer $id the id of the current record
 * @property integer $article_id
 * @property double $price
 * @property string $status
 * @property integer $creator_id
 * @property integer $time_deleted
 * @property integer $time_create
 * @property integer $party_id
 *
 * @property Article $article
 */
class Price extends \yii\db\ActiveRecord
{

  /**
   * will include the custom scopes for this model
   * @return object enhanced query object
   */
  public static function createQuery($config = [])
  {
    $config['modelClass'] = get_called_class();
    return new PriceQuery($config);
  }

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_price';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['article_id'], 'required'],
			[['article_id', 'creator_id', 'time_deleted', 'time_create', 'party_id'], 'integer'],
			[['price'], 'number'],
			[['status'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'article_id' => 'Article ID',
			'price' => 'Price',
			'status' => 'Status',
			'creator_id' => 'Creator ID',
			'time_deleted' => 'Time Deleted',
			'time_create' => 'Time Create',
			'party_id' => 'Party ID',
		];
	}

	/**
	 * @return \yii\db\ActiveRelation Article
	 */
	public function getArticle()
	{
		return $this->hasOne(Article::className(), ['id' => 'article_id']);
	}

  /**
   * @return \yii\db\ActiveRelation Party
   */
  public function getVendor()
  {
    return $this->hasOne(Party::className(), ['party_id' => 'id']);
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
}
