<?php

namespace app\modules\messaging\models;

/**
 * This is the model class for table "tbl_messages".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $reciever_id
 * @property string $subject
 * @property string $body
 * @property boolean $is_read
 * @property string $deleted_by
 * @property string $date_create
 * @property string $module
 */
class Messages extends \yii\db\ActiveRecord
{
	const DELETED_BY_RECEIVER = 'reciever';
	const DELETED_BY_SENDER = 'sender';

	public $unreadMessagesCount;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_messages';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['sender_id', 'reciever_id'], 'integer'],
			[['body', 'deleted_by'], 'string'],
			['is_read', 'boolean'],
			['date_create', 'required'],
			['date_create', 'safe'],
			['subject', 'string', 'max' => 255],
			['module', 'string', 'max' => 50]
		];
	}

	public function beforeSave($insert)
	{
		if ($insert) {
				$this->date_create = Date('Y-m-d H:i:s');
				$this->sender_id = Yii::$app->user->identity->id;
				return true;
		}else{
			return true;
		}
	}

	/**
	* @return model \app\models\user reciever
	*/
	public function getReciever(){
		return $this->hasOne('\app\models\User', ['id' => 'reciever_id']);
	}

	/**
	* @return model \app\models\user sender
	*/
	public function getSender(){
		return $this->hasOne('\app\models\User', ['id' => 'sender_id']);
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

	public static function getAdapterForInbox($userId) {
		return static::find()
							->where('(reciever_id = '.$userId.' or sender_id = '.$userId.') AND (deleted_by <> "'.self::DELETED_BY_RECEIVER.'" OR deleted_by IS NULL)')
							->orderBy('date_create DESC');
	}
}
