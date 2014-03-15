<?php

namespace app\modules\article\models;

use yii\db\ActiveQuery;

/**
 * Scope class for dmpaper which allows to view only none deleted records
 */

class PriceQuery extends ActiveQuery
{
    public function active($state = NULL)
    {
        $this->andWhere(['time_deleted' => $state]);
        return $this;
    }
}
