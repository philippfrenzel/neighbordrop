<?php

namespace app\modules\messaging\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\messaging\models\Messages;

/**
 * MessagesSearch represents the model behind the search form about Messages.
 */
class MessagesSearch extends Model
{
	public $id;
	public $sender_id;
	public $reciever_id;
	public $subject;
	public $body;
	public $is_read;
	public $deleted_by;
	public $date_create;
	public $module;

	public function rules()
	{
		return [
			[['id', 'sender_id', 'reciever_id'], 'integer'],
			[['subject', 'body', 'deleted_by', 'date_create', 'module'], 'safe'],
			['is_read', 'boolean'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'sender_id' => 'Sender ID',
			'reciever_id' => 'Reciever ID',
			'subject' => 'Subject',
			'body' => 'Body',
			'is_read' => 'Is Read',
			'deleted_by' => 'Deleted By',
			'date_create' => 'Date Create',
			'module' => 'Module',
		];
	}

	public function search($params)
	{
		$query = Messages::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'sender_id');
		$this->addCondition($query, 'reciever_id');
		$this->addCondition($query, 'subject', true);
		$this->addCondition($query, 'body', true);
		$this->addCondition($query, 'is_read');
		$this->addCondition($query, 'deleted_by', true);
		$this->addCondition($query, 'date_create');
		$this->addCondition($query, 'module', true);
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
