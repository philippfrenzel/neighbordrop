<?php

namespace app\modules\dms\models;

use yii\db\ActiveQuery;

/**
 * Scope class for dmpaper which allows to view only none deleted records
 */

class DmsysQuery extends ActiveQuery
{
    public function active($status=NULL)
    {
        $this->andWhere(['time_deleted'=>$status]);
        return $this;
    }
}
