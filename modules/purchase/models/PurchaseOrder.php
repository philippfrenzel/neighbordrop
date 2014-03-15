<?php

namespace app\modules\purchase\models;

use app\modules\parties\models\Contact;
use \DateTime;

/**
 * This is the model class for table "tbl_purchaseorder".
 *
 * @property integer $id
 * @property integer $contact_id
 * @property string $order_number
 * @property integer $creator_id
 * @property integer $time_deleted
 * @property integer $time_create
 *
 * @property Party $party
 * @property Purchaseordergroup[] $purchaseordergroups
 */
class PurchaseOrder extends \yii\db\ActiveRecord
{
  
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_purchaseorder';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['contact_id'], 'required'],
			[['contact_id', 'creator_id', 'time_deleted', 'time_create','approver_id'], 'integer'],
			[['order_number'], 'string', 'max' => 200],
      [['status'], 'string', 'max' => 200],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'           => \Yii::t('app','ID'),
			'contact_id'   => \Yii::t('app','Main Entity'),
			'order_number' => \Yii::t('app','Order Number'),
			'creator_id'   => \Yii::t('app','Creator ID'),
      'approver_id'   => \Yii::t('app','Approval by'),
			'time_deleted' => \Yii::t('app','Time Deleted'),
			'time_create'  => \Yii::t('app','Time Create'),
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getContact()
	{
		return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getPurchaseordergroups()
	{
		return $this->hasMany(PurchaseOrderGroup::className(), ['purchaseorder_id' => 'id']);
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
