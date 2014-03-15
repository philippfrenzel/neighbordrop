<?php

namespace app\modules\workflow\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\workflow\models\Workflow;

/**
 * WorkflowForm represents the model behind the search form about Workflow.
 */
class WorkflowForm extends Model
{
	public $id;
	public $previous_user_id;
	public $next_user_id;
	public $module;
	public $wf_table;
	public $wf_id;
	public $status_from;
	public $status_to;
	public $actions_next;
	public $date_create;

	public function rules()
	{
		return array(
			array(['id', 'previous_user_id', 'next_user_id', 'wf_table', 'wf_id'], 'integer'),
			array(['module', 'status_from', 'status_to', 'actions_next', 'date_create'], 'safe'),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'previous_user_id' => 'Previous User ID',
			'next_user_id' => 'Next User ID',
			'module' => 'Module',
			'wf_table' => 'Wf Table',
			'wf_id' => 'Wf ID',
			'status_from' => 'Status From',
			'status_to' => 'Status To',
			'actions_next' => 'Actions Next',
			'date_create' => 'Date Create',
		);
	}

	public function search($params)
	{
		$query = Workflow::find();
		$dataProvider = new ActiveDataProvider(array(
			'query' => $query,
		));

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'previous_user_id');
		$this->addCondition($query, 'next_user_id');
		$this->addCondition($query, 'module', true);
		$this->addCondition($query, 'wf_table');
		$this->addCondition($query, 'wf_id');
		$this->addCondition($query, 'status_from', true);
		$this->addCondition($query, 'status_to', true);
		$this->addCondition($query, 'actions_next', true);
		$this->addCondition($query, 'date_create');
		return $dataProvider;
	}

	protected function addCondition($query, $attribute, $partialMatch = false)
	{
		$value = $this->$attribute;
		if (trim($value) === '') {
			return;
		}
		if ($partialMatch) {
			$value = '%' . strtr($value, array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')) . '%';
			$query->andWhere(array('like', $attribute, $value));
		} else {
			$query->andWhere(array($attribute => $value));
		}
	}
}
