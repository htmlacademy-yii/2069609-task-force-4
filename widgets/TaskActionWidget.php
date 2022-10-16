<?php

namespace app\widgets;

use app\src\Action;
use app\src\CancelAction;
use app\src\GetDoneAction;
use app\src\RefuseAction;
use app\src\RespondAction;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class TaskActionWidget extends Widget
{
    public Action $actionObject;

    public function run()
    {
        $taskId = Yii::$app->request->get('id');
        $action = $this->actionObject->getName();
        $className = '';
        $urlName = '';
        $dataAction = '';
        switch ($action) {
            case CancelAction::NAME: $className = 'button button--yellow action-btn'; $urlName = ['/tasks/cancel', 'id' => $taskId]; break;
            case GetDoneAction::NAME: $className = 'button button--pink action-btn'; $urlName = ['/tasks/done'];  $dataAction = 'completion'; break;
            case RefuseAction::NAME: $className = 'button button--orange action-btn'; $urlName = ['/tasks/refuse']; $dataAction = 'refusal'; break;
            case RespondAction::NAME: $className = 'button button--blue action-btn'; $urlName = ['/tasks/respond', 'id_task' => $taskId]; $dataAction = 'act_response'; break;
        }

        $result = HTML::a($this->actionObject->getAction(),
            $urlName,
            ['class' => $className, 'data-action' => $dataAction]
        );
        return $result;
    }

}