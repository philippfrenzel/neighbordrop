<?php

namespace app\modules\parties\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\parties\models\Contact;

/**
 * ContactSearch represents the model behind the search form about Contact.
 */
class ContactSearch extends Model
{
	public $id;
	public $party_id;
	public $contactName;
	public $department;
	public $email;
	public $phone;
	public $mobile;
	public $fax;
	public $user_id;
	public $system_key;
	public $system_name;
	public $system_upate;
	public $creator_id;
	public $time_deleted;
	public $time_create;

	public function rules()
	{
		return [
			[['id', 'party_id', 'user_id', 'system_upate', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['contactName', 'department', 'email', 'phone', 'mobile', 'fax', 'system_key', 'system_name'], 'safe'],
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
			'contactName' => 'Contact Name',
			'department' => 'Department',
			'email' => 'Email',
			'phone' => 'Phone',
			'mobile' => 'Mobile',
			'fax' => 'Fax',
			'user_id' => 'User ID',
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
		$query = Contact::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'party_id');
		$this->addCondition($query, 'contactName', true);
		$this->addCondition($query, 'department', true);
		$this->addCondition($query, 'email', true);
		$this->addCondition($query, 'phone', true);
		$this->addCondition($query, 'mobile', true);
		$this->addCondition($query, 'fax', true);
		$this->addCondition($query, 'user_id');
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
