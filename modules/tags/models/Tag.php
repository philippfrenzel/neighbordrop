<?php

namespace app\modules\tags\models;

use Yii;

/**
 * This is the model class for table "tbl_tag".
 * Inside this table we keep the information of existing tags, all over the application
 *
 * @property integer $id
 * @property string $name
 * @property integer $frequency
 */

class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_tag';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('frequency', 'number', 'integerOnly'=>true),
            array('name', 'string', 'max'=>128),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'        => 'Id',
            'name'      => 'Name',
            'frequency' => 'Frequency',
        );
    }

    /**
     * Returns tag names and their corresponding weights.
     * Only the tags with the top weights will be returned.
     * @param integer the maximum number of tags that should be returned
     * @return array weights indexed by tag names.
     */
    public static function findTagWeights($limit=20)
    {
        $models = static::find()->limit($limit)->orderBy('frequency DESC')->all();

        $total=0;
        foreach($models as $model)
            $total+=$model->frequency;

        $tags=array();
        if($total>0)
        {
            foreach($models as $model)
                $tags[$model->name]=8+(int)(16*$model->frequency/($total+10));
            ksort($tags);
        }
        return $tags;
    }

    /**
     * Suggests a list of existing tags matching the specified keyword.
     * @param string the keyword to be matched
     * @param integer maximum number of tags to be returned
     * @return array list of matching tag names
     */
    public static function suggestTags($keyword,$limit=20)
    {
        $tags =  static::find()->where(
                    array('like', 'name', '%'.strtr($keyword,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%')
                )
                ->limit($limit)->orderBy('frequency DESC, Name')->all();
        $names=array();
        foreach($tags as $tag)
            $names[]=$tag->name;
        return $names;
    }

    /**
     * [string2array description]
     * @param  [type] $tags [description]
     * @return [type]       [description]
     */
    public static function string2array($tags)
    {
        return explode(',',trim($tags));
    }

    /**
     * [array2string description]
     * @param  [type] $tags [description]
     * @return [type]       [description]
     */
    public static function array2string($tags)
    {
        return implode(',',$tags);
    }

    /**
     * [updateFrequency description]
     * @param  [type] $oldTags [description]
     * @param  [type] $newTags [description]
     * @return [type]          [description]
     */
    public static function updateFrequency($oldTags, $newTags)
    {
        $oldTags=self::string2array($oldTags);
        $newTags=self::string2array($newTags);
        self::addTags(array_values(array_diff($newTags,$oldTags)));
        if(count($oldTags)>0){
            self::removeTags(array_values(array_diff($oldTags,$newTags)));    
        }
    }

    /**
     * [addTags description]
     * @param [type] $tags [description]
     */
    public static function addTags($tags)
    {
        
        if (count($tags)>0) {
            $inTags = preg_replace('/(\S+)/i', '\'\1\'', $tags);
            if(join(",", $inTags)!=''){
                $sql = "UPDATE {{tbl_tag}} SET frequency=frequency+1 WHERE name IN (". join(",", $inTags) .' ) ';
                Yii::$app->db->createCommand($sql)->execute();
            
                foreach($tags as $name) {
                    $model = static::find()->where('name=:name',array(':name'=>$name))->one();
                    if ($model === null) {
                        $tag=new Tag();
                        $tag->name=$name;
                        $tag->frequency=1;
                        $tag->save();
                    }
                }
            }
        }
    }

    /**
     * [removeTags description]
     * @param  [type] $tags [description]
     * @return [type]       [description]
     */
    public static function removeTags($tags)
    {
        if(count($tags)==0)
            return;
        $inTags = preg_replace('/(\S+)/i', '\'\1\'', $tags);
        
        if(join(",", $inTags)!=''){
            $sql = "UPDATE {{tbl_tag}} SET frequency=frequency-1 WHERE name IN (". join(",", $inTags) .' ) '; 
            Yii::$app->db->createCommand($sql)->execute();

            $sql = "DELETE FROM {{tbl_tag}} WHERE frequency<=0";
            Yii::$app->db->createCommand($sql)->execute();
        }
    }
}
