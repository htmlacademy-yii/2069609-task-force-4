<?php

namespace app\src;

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

    //буду сравнивать с $idCustomer
    public static function isAvailable(int $userCurrentId, int $idCustomer): bool
    {
        if ($userCurrentId === $idCustomer) {
            return true;
        } else {
            return false;
        }
    }
}
