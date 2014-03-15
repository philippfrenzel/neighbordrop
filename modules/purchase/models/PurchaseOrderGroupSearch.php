<?php

namespace app\modules\purchase\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\purchase\models\PurchaseOrderGroup;

/**
 * PurchaseOrderGroupSearch represents the model behind the search form about PurchaseOrderGroup.
 */
class PurchaseOrderGroupSearch extends Model
{
	public $id;
	public $contact_id;
	public $purchaseorder_id;
	public $creator_id;
	public $time_deleted;
	public $time_create;

	public function rules()
	{
		return [
			[['id', 'contact_id', 'purchaseorder_id', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'contact_id' => 'Contact ID',
			'purchaseorder_id' => 'Purchaseorder ID',
			'creator_id' => 'Creator ID',
			'time_deleted' => 'Time Deleted',
			'time_create' => 'Time Create',
		];
	}

	public function search($params)
	{
		$query = PurchaseOrderGroup::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'contact_id');
		$this->addCondition($query, 'purchaseorder_id');
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
