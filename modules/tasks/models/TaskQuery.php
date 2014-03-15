<?php

namespace app\modules\tasks\models;

use yii\db\ActiveQuery;

/**
 * Scope class for dmpaper which allows to view only none deleted records
 */

class TaskQuery extends ActiveQuery
{

    /**
     * active returns only the currently ative records from the dataset
     * @param  integer $status constant of workflow status
     * @return [type]         [description]
     */
    public function active($status = Workflow::STATUS_ARCHIVED)
    {
        $this->andWhere('status <> :status', [':status' => $status]);
        return $this;
    }
}
