<?php

namespace app\modules\purchase\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\purchase\models\PurchaseOrder;

/**
 * PurchaseOrderSearch represents the model behind the search form about PurchaseOrder.
 */
class PurchaseOrderSearch extends Model
{
	public $id;
	public $party_id;
	public $order_number;
	public $creator_id;
	public $time_deleted;
	public $time_create;

	public function rules()
	{
		return [
			[['id', 'party_id', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['order_number'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'party_id' => 'Party ID',
			'order_number' => 'Order Number',
			'creator_id' => 'Creator ID',
			'time_deleted' => 'Time Deleted',
			'time_create' => 'Time Create',
		];
	}

	public function search($params)
	{
		$query = PurchaseOrder::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'party_id');
		$this->addCondition($query, 'order_number', true);
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
