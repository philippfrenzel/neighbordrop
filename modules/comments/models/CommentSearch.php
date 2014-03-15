<?php

namespace app\modules\comments\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\comments\models\Comment;

/**
 * CommentSearch represents the model behind the search form about Comment.
 */
class CommentSearch extends Model
{
	public $id;
	public $content;
	public $status;
	public $author_id;
	public $time_create;
	public $comment_table;
	public $comment_id;

	public function rules()
	{
		return array(
			array(['id', 'author_id', 'time_create', 'comment_table', 'comment_id'], 'integer'),
			array(['content', 'status'], 'safe'),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => 'Content',
			'status' => 'Status',
			'author_id' => 'Author ID',
			'time_create' => 'Time Create',
			'comment_table' => 'Comment Table',
			'comment_id' => 'Comment ID',
		);
	}

	public function search($params)
	{
		$query = Comment::find();
		$dataProvider = new ActiveDataProvider(array(
			'query' => $query,
		));

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$this->addCondition($query, 'id');
		$this->addCondition($query, 'content', true);
		$this->addCondition($query, 'status', true);
		$this->addCondition($query, 'author_id');
		$this->addCondition($query, 'time_create');
		$this->addCondition($query, 'comment_table');
		$this->addCondition($query, 'comment_id');
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
