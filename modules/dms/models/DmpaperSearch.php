<?php

namespace app\modules\dms\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dms\models\Dmpaper;

/**
 * DmpaperSearch represents the model behind the search form about Dmpaper.
 */
class DmpaperSearch extends Model
{
	public $id;
	public $party_id;
	public $description;
	public $name;
	public $status;
	public $creator_id;
	public $time_deleted;
	public $time_create;
	public $tags;

	public function rules()
	{
		return [
			[['id', 'party_id', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['description', 'name', 'status','tags'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'           => \Yii::t('app','ID'),
			'party_id'     => \Yii::t('app','Document Supplier'),
			'description'  => \Yii::t('app','Description'),
			'name'         => \Yii::t('app','Subject'),
			'status'       => \Yii::t('app','Status'),
      'department'   => \Yii::t('app','For Department'),
      'documenttype' => \Yii::t('app','Type of doc'),
			'creator_id'   => \Yii::t('app','Creator ID'),
			'time_deleted' => \Yii::t('app','Time Deleted'),
			'time_create'  => \Yii::t('app','Time Create'),
			'tags'         => \Yii::t('app','Tags'),
		];
	}

	public function search($params)
	{
		$query = Dmpaper::find()->active();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'party_id');
		$this->addCondition($query, 'description', true);
		$this->addCondition($query, 'name', true);
		$this->addCondition($query, 'status', true);
		$this->addCondition($query, 'tags', true);
		$this->addCondition($query, 'creator_id');
		$this->addCondition($query, 'time_deleted');
		$this->addCondition($query, 'time_create');
		return $dataProvider;
	}

	public function searchReviewer($params)
	{
		$query = Dmpaper::find()->active()->responsible();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'party_id');
		$this->addCondition($query, 'description', true);
		$this->addCondition($query, 'name', true);
		$this->addCondition($query, 'status', true);
		$this->addCondition($query, 'tags', true);
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
