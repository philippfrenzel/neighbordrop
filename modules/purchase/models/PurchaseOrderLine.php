<?php

namespace app\modules\purchase\models;

use app\modules\workflow\models\Workflow;
use app\modules\parties\models\Party;

use \DateTime;

/**
 * This is the model class for table "tbl_purchaseorderline".
 *
 * @property integer $id
 * @property integer $purchaseordergroup_id
 * @property integer $party_id
 * @property double $order_amount
 * @property double $order_price
 * @property integer $article_id
 * @property string $status
 * @property integer $creator_id
 * @property integer $time_deleted
 * @property integer $time_create
 *
 * @property Purchaseordergroup $purchaseordergroup
 */
class PurchaseOrderLine extends \yii\db\ActiveRecord
{

  /**
   * this is the list of known unit of meassures, can be extended by development
   * @property array uom_properties the container for the meassures
   */
  const UOM_UOM = 1;
  const UOM_BRANCH = 2;
  const UOM_CORE = 3;
  const UOM_DC = 4;
  const UOM_GB = 5;
  const UOM_GRP = 6;
  const UOM_IMPULSE = 7;
  const UOM_KG = 8;
  const UOM_KM = 9;
  const UOM_KWH = 10;
  const UOM_LICENSE = 25;
  const UOM_LUMSUM = 11;
  const UOM_QM = 12;
  const UOM_QQM = 13;
  const UOM_MWH = 14;
  const UOM_PACKAGE = 15;
  const UOM_RATED = 16;
  const UOM_RATEH = 17;
  const UOM_RATEMIN = 18;
  const UOM_RATEM = 19;
  const UOM_RATEY = 20;
  const UOM_TB = 21;
  const UOM_TRANSA = 22;
  const UOM_UNIT = 23;
  const UOM_WORKPL = 24; //25 is already taken
  
  public static $uom_properties = array(
      self::UOM_UOM   => 'UoM',
      self::UOM_BRANCH   => 'branch',
      self::UOM_CORE       => 'core',
      self::UOM_DC         => 'data connection',
      self::UOM_GB => 'GB',
      self::UOM_GRP => 'Gross Rating Point',
      self::UOM_IMPULSE => 'impulse',
      self::UOM_KG => 'kg',
      self::UOM_KM => 'km',
      self::UOM_KWH => 'KWh',
      self::UOM_LICENSE => 'license',
      self::UOM_LUMSUM => 'lump sum',
      self::UOM_QM => 'm²',
      self::UOM_QQM => 'm³',
      self::UOM_MWH => 'MWh',
      self::UOM_PACKAGE => 'package/bundle price',
      self::UOM_RATED => 'rate, daily',
      self::UOM_RATEH => 'rate, hourly',
      self::UOM_RATEMIN => 'rate, minute',
      self::UOM_RATEM => 'rate, monthly',
      self::UOM_RATEY => 'rate, yearly',
      self::UOM_TB => 'TB',
      self::UOM_TRANSA => 'transaction',
      self::UOM_UNIT => 'unit',
      self::UOM_WORKPL => 'workplace',
  );

  public static function getUomPropertiesOptions()
  {
      return self::$uom_properties;
  }

  /**
   * this is the list of known currencies, can be extended by development
   * @property array cur_properties the container for the currencies
   */
  const CUR_BAM = 'BAM';
  const CUR_BGN = 'BGN';
  const CUR_CHF = 'CHF';
  const CUR_CZK = 'CZK';
  const CUR_GBP = 'GBP';
  const CUR_HRK = 'HRK';
  const CUR_HFT = 'HFT';
  const CUR_JPY = 'JPY';
  const CUR_MKD = 'MKD';
  const CUR_PLN = 'PLN';
  const CUR_RON = 'RON';
  const CUR_UAH = 'UAH';
  const CUR_USD = 'USD';
  const CUR_RSD = 'RSD';
  const CUR_EUR = 'EUR';

  public static $cur_properties = array(
      self::CUR_BAM => 'BAM',
      self::CUR_BGN => 'BGN',
      self::CUR_CHF => 'CHF',
      self::CUR_CZK => 'CZK',
      self::CUR_GBP => 'GBP',
      self::CUR_HRK => 'HRK',
      self::CUR_HFT => 'HFT',
      self::CUR_JPY => 'JPY',
      self::CUR_MKD => 'MKD',
      self::CUR_PLN => 'PLN',
      self::CUR_RON => 'RON',
      self::CUR_UAH => 'UAH',
      self::CUR_USD => 'USD',
      self::CUR_RSD => 'RSD',
      self::CUR_EUR => 'EUR',
  );

  public static function getCurPropertiesOptions()
  {
      return self::$cur_properties;
  }


  public $purchaseorder_id = NULL;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_purchaseorderline';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['purchaseordergroup_id'], 'required'],
			[['purchaseordergroup_id', 'article_id', 'creator_id', 'time_deleted', 'time_create', 'party_id','order_uom'], 'integer'],
      [['purchaseorder_id'],'integer'],
      [['date_delivery'],'date'],
			[['order_amount', 'order_price'], 'number'],
			[['status','article'], 'string', 'max' => 255],
      [['order_currency'], 'string', 'max' => 3]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                    => \Yii::t('app','ID'),
			'purchaseordergroup_id' => \Yii::t('app','Requested for'),
      'party_id'              => \Yii::t('app','Supplier'),
			'order_amount'          => \Yii::t('app','Quantity'),
			'order_price'           => \Yii::t('app','Price'),
      'order_uom'             => \Yii::t('app','UoM'),
      'order_currency'        => \Yii::t('app','Currency'),
			'article_id'            => \Yii::t('app','Article ID'),
      'article'               => \Yii::t('app','Product'),
			'status'                => \Yii::t('app','Status'),
      'date_delivery'         => \Yii::t('app','Delivery Date'),
			'creator_id'            => \Yii::t('app','Creator ID'),
			'time_deleted'          => \Yii::t('app','Time Deleted'),
			'time_create'           => \Yii::t('app','Time Create'),
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getPurchaseordergroup()
	{
		return $this->hasOne(PurchaseOrderGroup::className(), ['id' => 'purchaseordergroup_id']);
	}

  /**
   * @return \yii\db\ActiveRelation
   */
  public function getSupplier()
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


  public function afterSave($insert,$changedAttributes)
  {
    if($insert)
    {
      $workflow = Workflow::addRecordIntoWorkflow(Workflow::MODULE_PURCHASE,$this->id);
      $this->status = $workflow->status_to; //need to add the insert into workflow part here
      $this->save();
    }
    parent::afterSave($insert,$changedAttributes);
  }
}
