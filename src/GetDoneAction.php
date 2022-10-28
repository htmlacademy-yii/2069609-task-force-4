<?php

namespace Delta\TaskForce;

use app\models\Task;

class GetDoneAction extends Action
{
    const ACTION = 'Завершить';
    const NAME = 'get done';

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
        return 'button button--pink action-btn';
    }

    public function getDataAction(): string
    {
        return 'completion';
    }

    public function getUrlName(): string
    {
        return '#';
    }
}
