<?php

namespace app\modules\categories\models;

use yii\db\Query;

use app\modules\workflow\models\Workflow;

/**
 * This is the model class for table "tbl_categories".
 *
 * @property integer $id
 * @property integer $parent
 * @property string $name
 * @property string $status
 * @property integer $cat_module
 * @property integer $creator_id
 * @property integer $time_deleted
 * @property integer $time_create
 */
class Categories extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_categories';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['parent', 'cat_module', 'creator_id', 'time_deleted', 'time_create'], 'integer'],
			[['cat_module'], 'required'],
			[['name', 'status'], 'string', 'max' => 200]
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
			'creator_id' => 'Creator',
			'time_deleted' => 'Time Deleted',
			'time_create' => 'Time Create',
		];
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	public function beforeSave($insert)
	{		
		if (parent::beforeSave($insert)) 
		{
			if ($insert) 
			{
				$this->time_create=time();
				if(!\Yii::$app->user->isGuest)
				{
					$this->creator_id=\Yii::$app->user->identity->id;
				}
				else
				{
					$this->creator_id=1;
				}
			}
			else
			{
				//$this->time_update=time();	
			}
			return true;
		} 
		return false;
	}

	public static function getOptions($module)
	{
		$query = new Query;
    $query->select('id, name AS text')
      ->distinct()
      ->from('tbl_categories')
      ->where(['cat_module'=> $module])
      ->all();
    
    $command = $query->createCommand();
    $rows = $command->queryAll();

    $options = array();

    foreach($rows as $row)
    {
    	$options[$row['id']]=$row['text'];
    }

		return $options;
	}

}
