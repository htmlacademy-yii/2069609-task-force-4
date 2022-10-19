<?php

namespace app\widgets;

use Delta\TaskForce\Action;
use yii\base\Widget;
use yii\helpers\Html;

class TaskActionWidget extends Widget
{
    public Action $actionObject;

    public function run()
    {
        return HTML::a(
            $this->actionObject->getAction(),
            $this->actionObject->getUrlName(),
            [
                'className' => $this->actionObject->getClass(),
                'data-action' => $this->actionObject->getDataAction()
            ]
        );
    }

}