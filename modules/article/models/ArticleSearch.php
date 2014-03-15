<?php

namespace app\modules\article\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\article\models\Article;

/**
 * ArticleSearch represents the model behind the search form about Article.
 */
class ArticleSearch extends Model
{
	public $id;
	public $article;
	public $article_number;
	public $status;
	public $system_key;
	public $system_name;
	public $system_upate;
	public $creator_id;
	public $time_deleted;
	public $time_create;

	public function rules()
	{
		return [
			[['id', 'system_upate', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['article', 'article_number', 'status', 'system_key', 'system_name'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'article' => 'Article',
			'article_number' => 'Article Number',
			'status' => 'Status',
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
		$query = Article::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'article', true);
		$this->addCondition($query, 'article_number', true);
		$this->addCondition($query, 'status', true);
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
			$query->andWhere(['like', $attribute, $value]);
		} else {
			$query->andWhere([$attribute => $value]);
		}
	}
}
