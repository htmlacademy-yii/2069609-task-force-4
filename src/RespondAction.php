<?php

namespace Delta\TaskForce;

use app\models\Response;
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
    public static function isAvailable(int $userCurrentId, $idTask): bool
    {
        $executors = User::find()->where(['role' => User::ROLE_EXECUTOR])->all();
        $idsExecutors = ArrayHelper::getColumn($executors, 'id');

        $isResponseAlreadyNotExists = true;
        $isUserAvailable = true;

        $executor = User::findOne($userCurrentId);

        if ($executor->availability !== 1){
            $isUserAvailable = false;
        }

        if (Response::find()->where([
            'task_id' => $idTask,
            'user_id' => $userCurrentId
        ])->exists()) {
            $isResponseAlreadyNotExists = false;
        }

        if (ArrayHelper::isIn($userCurrentId, $idsExecutors) && $isResponseAlreadyNotExists && $isUserAvailable) {
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
