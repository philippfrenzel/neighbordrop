<?php

namespace app\modules\parties\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\parties\models\Party;

/**
 * PartySearch represents the model behind the search form about Party.
 */
class PartySearch extends Model
{
	public $id;
	public $organisationName;
	public $partyNote;
	public $taxNumber;
	public $registrationNumber;
	public $registrationCountryCode;
	public $party_type;
	public $system_key;
	public $system_name;
	public $system_upate;
	public $creator_id;
	public $time_deleted;
	public $time_create;

	public function rules()
	{
		return [
			[['id', 'registrationCountryCode', 'party_type', 'system_upate', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['organisationName', 'partyNote', 'taxNumber', 'registrationNumber', 'system_key', 'system_name'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'organisationName' => 'Organisation Name',
			'partyNote' => 'Party Note',
			'taxNumber' => 'Tax Number',
			'registrationNumber' => 'Registration Number',
			'registrationCountryCode' => 'Registration Country Code',
			'party_type' => 'Party Type',
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
		$query = Party::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'organisationName', true);
		$this->addCondition($query, 'partyNote', true);
		$this->addCondition($query, 'taxNumber', true);
		$this->addCondition($query, 'registrationNumber', true);
		$this->addCondition($query, 'registrationCountryCode');
		$this->addCondition($query, 'party_type');
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
