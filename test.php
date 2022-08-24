<?php
use Delta\TaskForce\Task;

require_once 'vendor/autoload.php';

$newTask = new Task(4,6);
$mapAction = $newTask->getActionMap();
$mapStatus = $newTask->getStatusMap();

$statusNew = 'new';
$statusDone = 'done';
$availableActionsNewCustomer = $newTask->getAvailableActions($statusNew, 4);
$availableActionsNewExecutor = $newTask->getAvailableActions($statusNew, 6);

$availableActionsDoneCustomer = $newTask->getAvailableActions($statusDone, 6);
$availableActionsDoneExecutor = $newTask->getAvailableActions($statusDone, 4);

var_dump($availableActionsNewCustomer, $availableActionsNewExecutor, $availableActionsDoneCustomer, $availableActionsDoneExecutor);

/*
$idCustomer = $newTask->idCustomer;
var_dump($idCustomer);

$nextStatus = $newTask->getNextStatus('cancel', 'new', 4);
var_dump($nextStatus);
$nextStatus = $newTask->getNextStatus('respond', 'new', 6);
var_dump($nextStatus);
$nextStatus = $newTask->getNextStatus('get done', 'work', 4);
var_dump($nextStatus);
$nextStatus = $newTask->getNextStatus('refuse', 'work', 6);
var_dump($nextStatus);
$actionMap = $newTask->getActionMap();
var_dump($actionMap);
*/
