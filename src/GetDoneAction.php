<?php

namespace Delta\TaskForce;

class GetDoneAction extends Action
{
    const ACTION = 'Выполнено';
    const NAME = 'get done';

    public function getAction(): string
    {
        return self::ACTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    //буду сравнивать с idCustomer
    public static function isAvailable(int $userCurrentId, int $idCustomer): bool
    {
        if ($userCurrentId === $idCustomer) {
            return true;
        } else {
            return false;
        }
    }

    public function getClass(): string
    {
        return 'button button--pink action-btn';
    }

    public function getDataAction(){
        return 'completion';
    }

    public function getUrlName()
    {
        return ['/tasks/done'];
    }
}
