<?php

namespace app\modules\purchase\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\purchase\models\PurchaseOrderLine;

/**
 * PurchaseOrderLineSearch represents the model behind the search form about PurchaseOrderLine.
 */
class PurchaseOrderLineSearch extends Model
{
	public $id;
	public $purchaseordergroup_id;
	public $order_amount;
	public $order_price;
	public $article_id;
	public $status;
	public $creator_id;
	public $time_deleted;
	public $time_create;

	public function rules()
	{
		return [
			[['id', 'purchaseordergroup_id', 'article_id', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['order_amount', 'order_price'], 'number'],
			[['status'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'purchaseordergroup_id' => 'Purchaseordergroup ID',
			'order_amount' => 'Order Amount',
			'order_price' => 'Order Price',
			'article_id' => 'Article ID',
			'status' => 'Status',
			'creator_id' => 'Creator ID',
			'time_deleted' => 'Time Deleted',
			'time_create' => 'Time Create',
		];
	}

	public function search($params)
	{
		$query = PurchaseOrderLine::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'purchaseordergroup_id');
		$this->addCondition($query, 'order_amount');
		$this->addCondition($query, 'order_price');
		$this->addCondition($query, 'article_id');
		$this->addCondition($query, 'status', true);
		$this->addCondition($query, 'creator_id');
		$this->addCondition($query, 'time_deleted');
		$this->addCondition($query, 'time_create');
		return $dataProvider;
	}

	protected function addCondition($query, $attribute, $partialMatch = false)
	{
		$value = $this->$attribute;
		if (trim($value) === '') {
			return;
		}
		if ($partialMatch) {
			$value = '%' . strtr($value, ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']) . '%';
			$query->andWhere(['like', $attribute, $value]);
		} else {
			$query->andWhere([$attribute => $value]);
		}
	}
}
