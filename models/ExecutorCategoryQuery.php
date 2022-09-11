<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ExecutorCategory]].
 *
 * @see ExecutorCategory
 */
class ExecutorCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ExecutorCategory[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ExecutorCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
