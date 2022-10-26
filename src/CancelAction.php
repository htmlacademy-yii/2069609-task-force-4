<?php

namespace Delta\TaskForce;

use app\models\Task;
use Yii;
use yii\helpers\Url;

class CancelAction extends Action
{
    const ACTION = 'Отменить';
    const NAME = 'cancel';

    public function getAction(): string
    {
        return self::ACTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    //Если автор задачи = тек пользователь возвращаем true
    public static function isAvailable(Task $task, int $userCurrentId): bool
    {
        return $task->user_id === $userCurrentId;
    }

    public function getClass(): string
    {
        return 'button button--yellow action-btn';
    }

    public function getDataAction(): string
    {
        return '';
    }

    public function getUrlName(): string
    {
        $taskId = Yii::$app->request->get('id');
        return Url::to(['/tasks/cancel', 'id' => $taskId]);
    }
}
