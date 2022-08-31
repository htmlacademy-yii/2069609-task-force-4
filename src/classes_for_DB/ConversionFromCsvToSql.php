<?php

namespace Delta\TaskForce\classes_for_DB;

use Delta\TaskForce\exceptions\FileFormatException;
use Delta\TaskForce\exceptions\SourceFileException;
use RuntimeException;

abstract class ConversionFromCSVtoSQL
{
   protected function getFileObjectSQl(string $filename): \SplFileObject
    {
        $fileObjectInfo = new \SplFileInfo($filename);
        $fileObjectPath = $fileObjectInfo->getPath();
        $fileObjectBasename = $fileObjectInfo->getBasename('.csv');
        $filenameSql = $fileObjectPath . '/' . $fileObjectBasename . '.sql';
        return new \SplFileObject($filenameSql, 'w+');
    }

    private function validateColumns(array $columns): bool
    {
        $result = true;
        if (count($columns)) {
            foreach ($columns as $column) {
                if (!is_string($column)) {
                    $result = false;
                }
            }
        } else {
            $result = false;
        }
        return $result;
    }

    private function getHeaderData($fileObjectCsv): ?array
    {
        //устанавливаем курсор файла в начало
        $fileObjectCsv->rewind();
        //получаем ПЕРВУЮ строку из файла и её разбираем ее как поля CSV
        return $fileObjectCsv->fgetcsv();
    }

    protected function getNextLine($fileObjectCsv): ?iterable
    {
        while (!$fileObjectCsv->eof()) {
            yield $fileObjectCsv->fgetcsv();
        }
        return null;
    }

    /**
     * @throws SourceFileException
     * @throws FileFormatException
     */
    protected function getFileObjectCsv($columns, $filename): \SplFileObject
    {
        //проверяем, заголовки имеют строчный тип или нет
        if (!$this->validateColumns($columns)) {
           throw new FileFormatException("Заданы неверные заголовки столбцов");
        }

        if (!file_exists($filename)) {
            throw new SourceFileException("Файл не существует");
        }

        try {
            $fileObjectCsv = new \SplFileObject($filename);
        } catch (RuntimeException $exception) {
            var_dump('Не удалось открыть файл');
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }
        //находим заголовки csv файла
        $header_data = $this->getHeaderData($fileObjectCsv);

        if ($header_data !== $columns) {
            var_dump('Исходный файл не содержит');
            throw new FileFormatException("Исходный файл не содержит необходимых столбцов");
        }
        return $fileObjectCsv;

    }
}
