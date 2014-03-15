<?php

namespace app\modules\parties\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\parties\models\Country;

/**
 * CountrySearch represents the model behind the search form about Country.
 */
class CountrySearch extends Model
{
	public $id;
	public $country_code;
	public $country_name;

	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['country_code', 'country_name'], 'safe'],
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

	public function search($params)
	{
		$query = Country::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'country_code', true);
		$this->addCondition($query, 'country_name', true);
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
