<?php
use Delta\TaskForce\Task;
use Delta\TaskForce\exceptions\IncomingDataException;

require_once 'vendor/autoload.php';

$newTask = new Task(4,6);
$mapAction = $newTask->getActionMap();
$mapStatus = $newTask->getStatusMap();

$statusNew = 'new';
$statusDone = 'done';
$statusRespond = 'respond';

try {
    $availableActionsNewCustomer = $newTask->getAvailableActions($statusNew, 6);
    echo('тест №1 ');
    var_dump($availableActionsNewCustomer);
} catch (IncomingDataException $e) {
    error_log('Бамс!'. $e->getMessage());
}
try {
    $availableActionsNewCustomer = $newTask->getAvailableActions($statusNew, 7);
    echo('тест №2 ');
    var_dump($availableActionsNewCustomer);
} catch (IncomingDataException $e) {
    error_log('Бамс!'. $e->getMessage());
}
try {
    $availableActionsNewCustomer = $newTask->getAvailableActions('олала', 7);
    echo('тест №3 ');
    var_dump($availableActionsNewCustomer);
} catch (IncomingDataException $e) {
    error_log('Бамс!'. $e->getMessage());
}
try {
    $nextStatus = $newTask->getNextStatus('respond', 'new', 6);
    echo('тест №4 ');
    var_dump($nextStatus);
} catch (IncomingDataException $e) {
    error_log('Бамс!'. $e->getMessage());
}
try {
    $nextStatus = $newTask->getNextStatus('ЛАЛАЛАЛА', 'new', 6);
    echo('тест №5 ');
    var_dump($nextStatus);
} catch (IncomingDataException $e) {
    error_log('Бамс!'. $e->getMessage());
}
/*
$actionMap = $newTask->getActionMap();
var_dump($actionMap);
*/

