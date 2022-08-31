<?php
declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

use Delta\TaskForce\classes_for_DB\ConversionCategory;
use Delta\TaskForce\classes_for_DB\ConversionCity;
use Delta\TaskForce\exceptions\FileFormatException;
use Delta\TaskForce\exceptions\SourceFileException;

require_once 'vendor/autoload.php';

$test1 = new ConversionCategory();

try {
    $test1->getConversion();
}
catch (SourceFileException $e) {
    error_log("Не удалось обработать csv файл: " . $e->getMessage());
}
catch (FileFormatException $e) {
    error_log("Неверная форма файла импорта: " . $e->getMessage());
}

/*
$test2 = new ConversionCity();

try {
    $test2->getConversion();
}
catch (SourceFileException $e) {
    error_log("Не удалось обработать csv файл: " . $e->getMessage());
}
catch (FileFormatException $e) {
    error_log("Неверная форма файла импорта: " . $e->getMessage());
}
*/
