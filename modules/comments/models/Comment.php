<?php

/**
 * This is the model class for table "tbl_comment".
 *
 * @property integer $id
 * @property string $content
 * @property string $status
 * @property integer $author_id
 * @property integer $time_create
 * @property integer $comment_table
 * @property integer $comment_id
 *
 * @property  $author
 */

namespace app\modules\comments\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

use app\modules\workflow\models\Workflow;

class Comment extends ActiveRecord
{
	/**
     * @return string the associated database table name
     */
	public static function tableName()
    {
        return '{{tbl_comment}}';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
    {
        return array(
            array('content', 'string'),
            array(['content', 'comment_table', 'comment_id'], 'required'),
						array(['author_id', 'time_create', 'comment_table', 'comment_id'], 'integer'),
            array(['status','anonymous'], 'string', 'max' => 255)
        );
    }

  /**
  * @param ActiveQuery $query
  */
  public static function active($query,$module=NULL)
  {
      $query->andWhere('(special <> -1 OR special IS NULL)');
  }

	//the author
	public function getAuthor() {
		return $this->hasOne('app\models\User', array('id' => 'author_id'));
	}

	//for each module we allow comments, we need to add a dynamic reference

	public function getPost() {
		return $this->hasOne('app\modules\posts\models\Post', array('id' => 'comment_id'));
								//->where('t1.comment_table = '.Workflow::MODULE_BLOG); //,'comment_table'=>'tbl_post'
	}

	public function getPage() {
		return $this->hasOne('app\modules\pages\models\Pages', array('id' => 'comment_id'));
			//->where('t1.comment_table = '.Workflow::MODULE_CMS); //,'comment_table'=>'tbl_post'
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'content' => Yii::t('app','Comment'),
			'status' => Yii::t('app','Status'),
			'time_create' => Yii::t('app','Create Time'),
			'author_id' => Yii::t('app','Name'),
			'anonymous' => Yii::t('app','Email'),
			'comment_id' => Yii::t('app','Reference ID'),
			'comment_table' => Yii::t('app','Module'),
		);
	}

	/**
	 * Approves a comment.
	 */
	public function approve()
	{
		$this->status=Workflow::STATUS_APPROVED;
		$this->update(array('status'));
	}

	/**
	 * @param Post the post that this comment belongs to. If null, the method
	 * will query for the post.
	 * @return string the permalink URL for this comment
	 */
	public function getUrl($post=null)
	{
		if($post===null)
			$post=$this->post;
		return $post->url.'#c'.$this->id;
	}

	/**
	 * @return string the hyperlink display for the current comment's author
	 */
	public function getAuthorLink()
	{
		/*if(!empty($this->url))
			return Html::a(Html::encode($this->author),$this->url);
		else*/
		if(!Yii::$app->user->isGuest)
		{
			return Html::encode($this->Author->username);
		}
		else
		{
			return Html::encode($this->anonymous);
		}
	}

	/**
	 * get the current comment count
	 * @param  [type] $module [description]
	 * @param  [type] $id     [description]
	 * @return [type]         [description]
	 */
	public static function getPendingCommentCount($module,$id)
	{
		return static::find()->where('status="'.Workflow::STATUS_PENDING.'" AND comment_table = '.$module.' AND comment_id = '.$id)->count();
	}

	/**
	 * @param integer the maximum number of comments that should be returned
	 * @return array the most recently added comments
	 */
	public static function findRecentComments($module,$id,$limit=10)
	{
		return static::find()->where('status IN ("'.Workflow::STATUS_APPROVED.'","'.Workflow::STATUS_CREATED.'") AND comment_table = '.$module.' AND comment_id = '.$id)
					->orderBy('time_create DESC')
					->limit($limit)
					->with('post');
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($insert) {
				if(Yii::$app->user->isGuest)
				{
					//for anonymous posts, we need to define a special rule...
					$this->author_id = NULL;
				}
				{
					$this->author_id = Yii::$app->user->identity->id;
				}				
				$this->time_create=time();
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * here we return the current number of comments for the passed over module and id
	 * @param  integer $module [description]
	 * @param  integer $id     [description]
	 * @return integer         [description]
	 */
	public static function getAdapterForCommentCount($module,$id)
	{
		return static::find()->where('status IN ("'.Workflow::STATUS_APPROVED.'","'.Workflow::STATUS_CREATED.'") AND comment_table = '.$module.' AND comment_id = '.$id)->Count();
	}

}
