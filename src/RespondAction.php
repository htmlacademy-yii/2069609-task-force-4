<?php

namespace Delta\TaskForce;

use app\models\User;
use yii\helpers\ArrayHelper;

class RespondAction extends Action
{
    const ACTION = 'Откликнуться';
    const NAME = 'respond';

    public function getAction(): string
    {
        return self::ACTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    //буду сравнивать с $idExecutor
    public static function isAvailable(int $userCurrentId): bool
    {
        $executors = User::find()->where(['role' => User::ROLE_EXECUTOR])->all();
        $idsExecutors = ArrayHelper::getColumn($executors, 'id');

        if (ArrayHelper::isIn($userCurrentId, $idsExecutors)) {
            return true;
        } else {
            return false;
        }
    }

    public function getClass(): string
    {
        return 'button button--blue action-btn';
    }

    public function getDataAction()
    {
        return 'act_response';
    }
    public function getUrlName()
    {
        return '#';
    }
}
