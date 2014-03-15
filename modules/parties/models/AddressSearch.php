<?php

namespace app\modules\parties\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\parties\models\Address;

/**
 * AddressSearch represents the model behind the search form about Address.
 */
class AddressSearch extends Model
{
	public $id;
	public $party_id;
	public $postCode;
	public $streetDescription;
	public $addressLine;
	public $postBox;
	public $cityName;
	public $region;
	public $countryCode;
	public $system_key;
	public $system_name;
	public $system_upate;
	public $creator_id;
	public $time_deleted;
	public $time_create;

	public function rules()
	{
		return [
			[['id', 'party_id', 'countryCode', 'system_upate', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['postCode', 'streetDescription', 'addressLine', 'postBox', 'cityName', 'region', 'system_key', 'system_name'], 'safe'],
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
			'postCode' => 'Post Code',
			'streetDescription' => 'Street Description',
			'addressLine' => 'Address Line',
			'postBox' => 'Post Box',
			'cityName' => 'City Name',
			'region' => 'Region',
			'countryCode' => 'Country Code',
			'system_key' => 'System Key',
			'system_name' => 'System Name',
			'system_upate' => 'System Upate',
			'creator_id' => 'Creator ID',
			'time_deleted' => 'Time Deleted',
			'time_create' => 'Time Create',
		];
	}

	public function search($params)
	{
		$query = Address::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'party_id');
		$this->addCondition($query, 'postCode', true);
		$this->addCondition($query, 'streetDescription', true);
		$this->addCondition($query, 'addressLine', true);
		$this->addCondition($query, 'postBox', true);
		$this->addCondition($query, 'cityName', true);
		$this->addCondition($query, 'region', true);
		$this->addCondition($query, 'countryCode');
		$this->addCondition($query, 'system_key', true);
		$this->addCondition($query, 'system_name', true);
		$this->addCondition($query, 'system_upate');
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
