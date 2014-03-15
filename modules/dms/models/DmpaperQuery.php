<?php

namespace app\modules\dms\models;

use yii\db\ActiveQuery;
use yii\db\Query;

use app\modules\workflow\models\Workflow;

/**
 * Scope class for dmpaper which allows to view only none deleted records
 */

class DmpaperQuery extends ActiveQuery
{
    
    /**
     * retrun only none deleted records
     * a deleted record has an unix timestamp as integer, a none delted stays null
     * @param  integer $state defaults to null
     * @return ActiveQuery where condition for none deleted records or for the passed over time
     */
    public function active($state = NULL)
    {
        $this->andWhere(['time_deleted' => $state]);
        return $this;
    }

    /**
     * will filter the result on the records where an workflow entry is logged for
     * the current signed in user or if an user is passed over by email, for him
     * @param  string $email the email of the user, that will be checked within workflow table
     * @return ActiveQuery where id in (array of allowed id's)
     */
    public function responsible($email = NULL)
    {  
      if(is_null($email))
      {
        $email = \Yii::$app->user->identity->email;
      }

      $subQuery = new Query;
      $subQuery->select(['wf_id AS id'])
        ->distinct()
        ->from('tbl_workflow')
        ->innerJoin('tbl_user','tbl_workflow.next_user_id = tbl_user.id')
        ->where([
          'wf_table'=>[Workflow::MODULE_DMPAPER,Workflow::MODULE_TASKS]
          ,'email' => $email
        ]);
      $command = $subQuery->createCommand();
      $rows = $command->queryAll();
      
      $this->andWhere(['id' => array_values($rows)]);
      return $this;
    }
}
