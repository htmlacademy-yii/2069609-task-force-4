<?php
require_once 'classes.php';

$newTask = new Task(4,6);
$mapAction = $newTask->getActionMap();
$mapStatus = $newTask->getStatusMap();

$statusNew = 'new';
$statusDone = 'done';
$availableActionsNew = $newTask->getAvailableActions($statusNew);
$availableActionsDone = $newTask->getAvailableActions($statusDone);

$action = 'cancel';
$currentStatus = $newTask->getCurrentStatus($action);

var_dump($mapAction, $mapStatus, $availableActionsNew, $availableActionsDone, $currentStatus);

$idCustomer = $newTask->idCustomer;
var_dump($idCustomer);
