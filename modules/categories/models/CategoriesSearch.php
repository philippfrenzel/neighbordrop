<?php

namespace app\modules\categories\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\categories\models\Categories;

/**
 * CategoriesSearch represents the model behind the search form about `app\modules\categories\models\Categories`.
 */
class CategoriesSearch extends Model
{
	public $id;
	public $parent;
	public $name;
	public $status;
	public $cat_module;
	public $creator_id;
	public $time_deleted;
	public $time_create;

	public function rules()
	{
		return [
			[['id', 'parent', 'cat_module', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['name', 'status'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'parent' => 'Parent',
			'name' => 'Name',
			'status' => 'Status',
			'cat_module' => 'Cat Module',
			'creator_id' => 'Creator ID',
			'time_deleted' => 'Time Deleted',
			'time_create' => 'Time Create',
		];
	}

	public function search($params)
	{
		$query = Categories::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'parent');
		$this->addCondition($query, 'name', true);
		$this->addCondition($query, 'status', true);
		$this->addCondition($query, 'cat_module');
		$this->addCondition($query, 'creator_id');
		$this->addCondition($query, 'time_deleted');
		$this->addCondition($query, 'time_create');
		return $dataProvider;
	}

	protected function addCondition($query, $attribute, $partialMatch = false)
	{
		if (($pos = strrpos($attribute, '.')) !== false) {
			$modelAttribute = substr($attribute, $pos + 1);
		} else {
			$modelAttribute = $attribute;
		}

		$value = $this->$modelAttribute;
		if (trim($value) === '') {
			return;
		}
		if ($partialMatch) {
			$query->andWhere(['like', $attribute, $value]);
		} else {
			$query->andWhere([$attribute => $value]);
		}
	}
}
